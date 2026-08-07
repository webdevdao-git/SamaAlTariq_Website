import "server-only";
import mysql from "mysql2/promise";

/**
 * MySQL connection pool for the Hostinger-managed database.
 *
 * Hostinger creates the database and user in hPanel (Databases → Management);
 * the credentials go in .env as MYSQL_*. A pool — not a connection per request —
 * matters here because shared plans cap concurrent connections (commonly 25),
 * so a small fixed pool is reused instead of dialling per request.
 *
 * The pool is cached on globalThis so a dev hot reload does not leak a new pool
 * on every recompile.
 */

const globalForDb = globalThis as unknown as { __samaPool?: mysql.Pool };

export function isDatabaseConfigured() {
  return Boolean(
    process.env.MYSQL_HOST && process.env.MYSQL_USER && process.env.MYSQL_DATABASE,
  );
}

export function getPool(): mysql.Pool {
  if (globalForDb.__samaPool) return globalForDb.__samaPool;

  const pool = mysql.createPool({
    host: process.env.MYSQL_HOST,
    port: Number(process.env.MYSQL_PORT ?? 3306),
    user: process.env.MYSQL_USER,
    password: process.env.MYSQL_PASSWORD,
    database: process.env.MYSQL_DATABASE,
    waitForConnections: true,
    connectionLimit: Number(process.env.MYSQL_POOL_SIZE ?? 5),
    queueLimit: 0,
    charset: "utf8mb4_general_ci",
    // Hostinger's MySQL is reached over the local network from the Node app, so
    // TLS is off by default. Set MYSQL_SSL=true for a remote/managed host.
    ssl: process.env.MYSQL_SSL === "true" ? { rejectUnauthorized: true } : undefined,
    timezone: "Z",
    dateStrings: ["DATE"],
  });

  globalForDb.__samaPool = pool;
  return pool;
}

/**
 * Values accepted as bound parameters. mysql2's own `ExecuteValues` is narrower
 * than what callers naturally hold (a `Partial<T>` field is `unknown` until it
 * is narrowed), so the helpers take this union and hand it to the driver in one
 * place rather than casting at every call site.
 */
export type SqlValue = string | number | boolean | Date | Buffer | null;

/**
 * `execute` rejects `undefined` outright, which is easy to hit whenever an
 * optional field is simply absent. Treating it as SQL NULL here means callers
 * can pass an optional value straight through instead of writing `?? null`.
 */
function bind(params: unknown[]): SqlValue[] {
  return params.map((value) => (value === undefined ? null : value)) as SqlValue[];
}

/** Typed SELECT helper. Values are always bound, never interpolated. */
export async function query<T>(sql: string, params: unknown[] = []): Promise<T[]> {
  const [rows] = await getPool().query(sql, bind(params));
  return rows as T[];
}

/** Typed single-row SELECT helper. */
export async function queryOne<T>(
  sql: string,
  params: unknown[] = [],
): Promise<T | null> {
  const rows = await query<T>(sql, params);
  return rows[0] ?? null;
}

/** INSERT / UPDATE / DELETE helper returning affectedRows. */
export async function execute(
  sql: string,
  params: unknown[] = [],
): Promise<mysql.ResultSetHeader> {
  const [result] = await getPool().execute(sql, bind(params));
  return result as mysql.ResultSetHeader;
}

/** Runs `fn` inside a transaction, rolling back on any throw. */
export async function transaction<T>(
  fn: (conn: mysql.PoolConnection) => Promise<T>,
): Promise<T> {
  const conn = await getPool().getConnection();
  try {
    await conn.beginTransaction();
    const result = await fn(conn);
    await conn.commit();
    return result;
  } catch (error) {
    await conn.rollback();
    throw error;
  } finally {
    conn.release();
  }
}
