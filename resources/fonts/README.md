# Licensed fonts

Everything else the site uses — Manrope, Cormorant Garamond, Playfair Display —
is freely licensed and downloaded at build time by the `fonts` block in
`vite.config.js`. This folder is for faces that cannot be fetched that way
because they have to be bought.

## Juana Alt Medium

The display face in the Figma file ("Landing Page redesign", node 50:2119) is
**Juana Alt Medium** by Latinotype. It is a commercial family: free for personal
use only, so a **webfont** licence has to be purchased before it can be served
from this site. Desktop licences do not cover `@font-face` — buy the webfont
kit, not just the OTFs.

Sold at [Latinotype](https://www.latinotype.com/), [MyFonts](https://www.myfonts.com/collections/juana-font-latinotype/)
and [Fontspring](https://www.fontspring.com/fonts/latinotype/juana).

### Installing it

Drop the two files from the webfont kit in here, named exactly:

```
resources/fonts/juana-alt-medium.woff2
resources/fonts/juana-alt-medium.woff
```

and delete `fffont.otf`, which is the demo OTF as supplied and is only in the
`src` list as a last-resort format fallback.

Then two things, both required:

1. Delete the `unicode-range` line from the `'Juana Alt'` `@font-face` in
   `resources/css/app.css`. It exists only to route the demo's broken glyphs to
   Playfair (see below); the licensed cut draws them, and leaving the guard in
   would keep punctuation and the digit `4` on the wrong face.
2. Run `npm run build`. These files sit in `resources/fonts` and are pulled in
   by relative `url()`, which puts them in Vite's asset graph — they get
   content-hashed into `public/build/assets`, so a new file means a new hash and
   the CSS has to be rebuilt to point at it.

### Why they are not in `public/fonts`

They were, with absolute `url('/fonts/…')` paths, and it did not work in
development. `php artisan serve` runs the page on `:8000` while Vite serves the
stylesheet from `:5173`; a root-relative `url()` in that stylesheet resolves
against the Vite origin, and Laravel's `public/` is not mounted there
(`laravel-vite-plugin` sets `publicDir: false`). Every face 404'd and the
headings silently fell back to Playfair Display, which looks like nothing having
changed at all rather than like an error. Relative paths out of `resources/`
resolve in both dev and build. Do not move them back.

Only weight 500 is required: `.display` and `.editorial-heading` both set
`font-weight: 500` and nothing overrides it. If the kit ships other weights and
you want them, add a `@font-face` per weight rather than letting the browser
synthesise one.

### Where the face is used

It is opt-in per element — there is no global override, and `--font-sans`
(Manrope) still covers body copy, navigation, buttons, labels, form fields,
captions and footer text. Two classes apply it:

- `.editorial-heading` — the large uppercase slabs. Line-height `0.88`,
  tracking `-0.04em`. On the hero `<h1>`, the Precision word, and the Projects
  and Process section headings.
- `.display` — the mid-size serif tier at normal leading. On the About,
  Inquiry and Services section headings, the process step numbers and titles,
  the service card titles, and the overlay menu links.

### What is in here right now — a DEMO, not the licensed font

`juana-alt-medium.woff2` / `.woff` were converted from the **Fontspring demo**
(`FSP DEMO - Juana Alt Medium`, name ID 1). They are here so the design can be
previewed in the real face; they are **not licensed to be served from a public
site**, and they cannot be, because the demo replaces most non-letter glyphs
with a "DEMO" flower.

Exactly 28 of the 95 mapped characters share one identical outline — that
flower. Verified by comparing glyph outlines, not by eye:

```
! " # $ % & ' ( ) * + - / 4 < = > @ [ \ ] ^ _ ` { | } ~
```

The two that show up in real copy are the digit `4` (the fourth process step
would read `0❦`) and `-` ("Construction And Fit-Out" would break in the
middle). Letters, `0-3`, `5-9`, `, . : ; ?` and space are clean. The demo also
has no `’` (U+2019), so "Let’s Build the Future Together." falls through for
that one glyph.

This is why the `@font-face` in `app.css` carries a `unicode-range` listing only
the clean glyphs: the 28 broken ones never request Juana and render in Playfair
Display instead. A different serif mid-word is a small blemish; a dingbat
mid-word is a bug. Replace the files with the purchased kit and drop that line,
per "Installing it" above.

### Before the demo was dropped in

Playfair Display stood in, and it still does anywhere the demo has no glyph. It
is the closest freely-licensed match — it sets within about 1% of Juana's width
at the same size — but it is not the same face,
and it runs roughly 5% wider glyph for glyph. The hero headline is built to
absorb that (see the comment on the `<h1>` in `resources/views/sections/hero.blade.php`),
so swapping in Juana widens the gap between "Building" and "With Precision"
back toward the spacing in the design without anything else having to move.

Note that with the files removed the `url()`s become dangling references and
`npm run build` fails, rather than the browser 404ing at runtime — that is the
trade for having Vite hash them. Delete the `@font-face` block along with the
files if you ever want to go back to Playfair alone.

## Manrope

Self-hosted from this folder as well, from the supplied 2018 OTF set — the
`bunny('Manrope', …)` line in `vite.config.js` was removed in favour of it. The
four weights the site actually uses (400/500/600/700) have `@font-face` rules in
`app.css`; `manrope-thin.otf`, `-light.otf` and `-extrabold.otf` are kept here
unreferenced in case a weight is added later, and cost nothing until they are.

Cormorant Garamond and Playfair Display are still downloaded from Bunny at build
time by that same `fonts` block.

## Bodoni Moda

`bodoni-moda-latin.woff2` — a guard, and nothing else.

It carried the footer lock-up until that was set in Juana Alt Medium, which is
the face the brand artwork is drawn in. It now sits behind Juana on
`.service-title` and no copy reaches it: the headlines spell out "And" and drop
the hyphen from "Fit Out", which is what keeps them on one face, since `&` and
`-` are both in the demo cut's broken-glyph list below. Bodoni is second in that
stack rather than Playfair so that a headline which *does* gain one lands on a
high-contrast serif whose ampersand is the one in the reference artwork.

`unicode-range` means the browser never downloads it while that stays true.

**When the licensed Juana Alt kit lands, this file can go.** Deleting the
`unicode-range` on the Juana `@font-face` puts those two characters back on the
real cut, and nothing else references `--font-logo`.

One file covers it because it is the variable build: 46KB carrying `wght`
400-900 and `opsz` 6-96 — far more than two glyphs need, but it is the smallest
thing Google Fonts ships for the family. Taken from the latin subset (Open Font
License); the `unicode-range` in `app.css` is the guard for that, so anything
outside latin falls to the next serif in `--font-logo` rather than rendering
.notdef.
