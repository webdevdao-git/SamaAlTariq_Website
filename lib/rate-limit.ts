/**
 * Fixed-window rate limiter held in process memory.
 *
 * The site runs as a single `next start` Node process on Hostinger, so one
 * in-memory map covers every request. If the app is ever scaled to multiple
 * instances, swap the Map for Redis — the call signature stays the same.
 */

type Window = { count: number; resetAt: number };

const windows = new Map<string, Window>();
const MAX_KEYS = 5_000;

export type RateLimitResult = {
  ok: boolean;
  remaining: number;
  retryAfterSeconds: number;
};

export function rateLimit(
  key: string,
  { limit = 5, windowMs = 10 * 60 * 1000 } = {},
): RateLimitResult {
  const now = Date.now();
  const existing = windows.get(key);

  if (!existing || existing.resetAt <= now) {
    // Opportunistic sweep so a long-running process can't grow unbounded.
    if (windows.size > MAX_KEYS) {
      for (const [k, w] of windows) if (w.resetAt <= now) windows.delete(k);
    }
    windows.set(key, { count: 1, resetAt: now + windowMs });
    return { ok: true, remaining: limit - 1, retryAfterSeconds: 0 };
  }

  existing.count += 1;
  const retryAfterSeconds = Math.ceil((existing.resetAt - now) / 1000);

  return {
    ok: existing.count <= limit,
    remaining: Math.max(0, limit - existing.count),
    retryAfterSeconds,
  };
}

/** Best-effort client IP behind Hostinger's proxy / LiteSpeed. */
export function clientIp(headers: Headers): string {
  const forwarded = headers.get("x-forwarded-for");
  if (forwarded) return forwarded.split(",")[0].trim();
  return (
    headers.get("cf-connecting-ip") ??
    headers.get("x-real-ip") ??
    "unknown"
  );
}
