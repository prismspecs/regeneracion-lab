# Mask Experiment

A standalone prototype (`index.html`, no build step) for a hero
interaction: **the site title starts as plain white
text at the bottom of the screen, and scrolling sends it gliding up to a
pinned position at the top, where it stops being "white text" and becomes a
window showing the background photo through the letter shapes.**

This is a design experiment living in `html-js-site/` (archived prototype
territory, not part of the live WordPress build). It is not wired into
`regen_wp/` yet.

## The effect, state by state

1. **At rest (page load):** the background is a full-bleed desert photo.
   The title `REGENERACIÓN LAB` sits in plain white at the very bottom of
   the viewport, flush to the edge. A short quotation (a dicho attributed
   to the 1910s Mexican revolution) sits faintly in the upper-middle of
   the frame.
2. **Scroll a little:** once you scroll past a small threshold, one smooth
   ~2s animation takes over — you don't control it frame-by-frame, it just
   glides to the finished state no matter how much further you scroll. The
   quote fades out on the same trigger, at the same time, as the title
   starts rising — it reads as the whole "at rest" scene leaving together.
3. **Pinned (post-scroll):** the title is flush to the top of the screen
   and stays there. The screen behind it is now solid `--wash-color`
   (currently white — see "Wash color" below; was `#765163`, the plum-
   purple used elsewhere in this repo). The photo is still visible *inside
   the letters themselves* — the title reads as a cutout window onto the
   image — and a thin outline traces the letter edges on top of that, so
   they stay legible as shapes even where the photo showing through
   happens to be close in tone to the wash color. The quote is gone.
4. Scrolling back up near the top reverses the animation, quote included.
5. **The page proper comes up together with the title**, not after
   further scrolling -- a fade, not a scroll-driven reveal, timed to
   arrive right as the title settles. See "The page below the hero" for
   why. For now that's the live homepage's first three paragraphs (a
   growing drop-cap effect on the opening paragraph's first letter) plus
   a small grid of three real projects below them — see "Projects grid".
   It's normal document flow, so it scrolls up underneath the still-pinned
   title as you keep going -- the title stays legible and on top the
   whole time, the same way any ordinary sticky header stays above
   content scrolling past it (see "The page below the hero" for why it's
   the title covering content here, not the other way around).

## How it's built

Three layers sit on top of each other inside a `position: fixed` `.hero`
container that fills the viewport for the whole page:

1. **`.hero-image`** — the photo, `object-fit: cover`, always fully
   visible, never itself hidden.
2. **`.wash`** — an SVG rectangle filled with `var(--wash-color)`, with an
   `<svg><mask>` that punches a title-shaped hole out of it (a `<rect>`
   for "everywhere" plus the title glyphs as a black cutout). This layer
   is transparent (`opacity: 0`) at rest, so you just see the photo under
   it. When pinned, its opacity goes to `1` — so it covers the whole
   screen in `--wash-color` *except* through the title-shaped hole, which
   is where the photo peeks through.
3. **`.title-white`** — the same title glyphs again, but drawn as plain
   solid white shapes (no mask), sitting on top of `.wash`. This is what
   you actually see at rest. It fades to `opacity: 0` while the
   photo-filled layer fades in, so the letters read as "filling in" with
   the photo. Fade timing is flag-controlled: 2s (the shared ease) by
   default, or `0.5s` under `FAST_FILL` — see "Two renderers" below for
   why that option exists.
4. **`#outlineGroup`** — an optional thin stroked outline around the
   letterforms, controlled by the `SHOW_TITLE_OUTLINE` flag (default: `false`).
   When off (default), the pinned title remains a clean, pure window onto the photo
   without any added stroke outline appearing after landing. When enabled (`true`),
   it lives in `.title-outline` (z-index 4, above `.page-content`) and fades in with
   a 2s delay to ensure contrast on pale photos.

### Two renderers for the mask effect (`TITLE_RENDERER`)

The photo-through-the-letters effect ships with two implementations,
switchable with `TITLE_RENDERER` at the top of the `<script>` block.
Both produce the same four states; they differ in *how* the pinned
title's window onto the photo is drawn, and that difference turned out
to be the whole story behind a visible lag.

- **`'window'` (option B, default).** The wash rect loses its SVG
  `mask` attribute at startup — it becomes a plain solid rectangle whose
  opacity fade is GPU-composited, with nothing re-rasterized per frame.
  The title-shaped window is `.title-window` instead: a title-sized div
  clipped to the letterforms by an element-local `mask-image`
  (generated at startup from the same DOM paths the SVG mask holds, so
  the letterforms still have exactly one source of truth). Inside it, a
  full-viewport copy of the hero photo is counter-translated by the
  exact inverse of the title's own transform, keeping the photo
  registered to the viewport while the glyphs rise, and it mirrors the
  hero image's `1.06` activation zoom with a matching scale about the
  same center. Every moving part is a composited element transform and
  the mask is static relative to its own layer, so the fill can never
  shear away from the glyphs — the two can't run on different clocks
  because there is only one clock. Layering: `.title-window` (z-index 3)
  and `.title-outline` (4) are top-level fixed layers ABOVE
  `.page-content` (2) — `.hero` forms its own stacking context, so a
  title inside it could never be raised above the content — so scrolling
  text passes UNDER the letters, visible only in the gaps between them,
  never double-exposed over the glyph fills. The "title on top, content
  underneath" rule, without the solid cap.
- **`'mask'` (option A's architecture, kept working as a fallback).**
  The original three-layer design described in the numbered list above:
  the SVG mask punches a title-shaped hole out of the wash rect, and
  `#washTitleGroup` carries the hole's transform. The catch: the hole is
  mask *content*, so the full-viewport mask re-rasterizes on the CPU
  every frame of the rise while `.title-white` animates on the GPU
  compositor. Under load the rasterized side trails by a few frames,
  and mid-crossfade — when both glyph layers are visible — that reads
  as one title lagging behind the other (worse in Firefox than Chrome
  in testing, and unresponsive to the `FAST_FILL` mitigation in
  practice). This is the mode to fall back to if `'window'` ever
  misbehaves in some browser; it is exactly the pre-option-B behavior.

`FAST_FILL` (option A's timing tweak) works in either renderer: it
swaps the 2s crossfade between `.title-white` and the photo-filled
layer for a 0.5s in-place fade (a `.fast-fill` class on `<html>`, set
once at startup). It was the first, cheaper attempt at hiding the
`'mask'` renderer's pipeline lag; in `'window'` mode there is no lag to
hide, so it defaults to off — but it's a one-word change if the faster
"fill" beat is ever preferred on its own merits.

### Wash color

`--wash-color`, set once at the top of the `<style>` block (`:root`),
drives `.hero`'s background and the wash rectangle's fill — a single edit
changes the hero's pinned-state color. Currently white, matching the
title's own resting color exactly; the previous plum-purple (`#765163`,
used elsewhere in this repo) is saved right above it as a commented-out
line.

**There used to be a `--page-bg` variable too, and it caused the same
mistake twice in a row**, worth recording so it doesn't happen a third
time: `.hero`'s wash (a few blocks up) already fades `--wash-color` in
across the *entire* viewport on the `activated` trigger -- it's the only
background this page needs, anywhere below the title.

1. `.page-content` painted its own separate `background: var(--page-bg)`
   on top of that. Two independent white fades stacked on the same spot,
   on two different timings, read as the background visibly sweeping in
   twice. Fixed by deleting `.page-content`'s `background` entirely.
2. The drop-cap `<canvas>` (which previously drew into an HTML5
   canvas via `@chenglou/pretext`) has since been completely replaced
   with a native semantic HTML `<p class="dropcap-paragraph">` and
   `<span class="dropcap-letter">R</span>`. This ensures that all text
   is natively selectable and copyable, screen-reader accessible, and
   responsive without needing an opaque canvas buffer.

`--page-bg` and `--page-text` are what's left: `--page-text` for the
paragraph's actual ink color, `--page-bg`... isn't used anywhere anymore
and has been deleted. If something ever again seems to need "the page's
background color" as an explicit value, check first whether it can just
let the wash underneath show through instead, the way everything else
here does.

White wasn't a clean swap, though: on `JumpinCholla.jpg` specifically (and
potentially other pale-sky photos), the title nearly disappeared — white
letters showing a near-white photo through them, on a white background.
Purple never had this problem since it contrasts with literally any
photo. `#outlineGroup` (above) is what makes white actually work: a
guaranteed-visible edge regardless of what tone the photo and the wash
happen to share.

One implementation trap worth flagging: `#outlineGroup`'s `<use>`
elements reference the *same* `<path>`s the mask hole uses (`#glyph0`
through `#glyph14`, one per letter — see "Why there are 15 separate
letter paths" below), so there's one source of truth for the letterforms.
The
first version gave those paths their own `fill="black"` attribute (needed
for the mask) and tried to override it to `fill: none` on the outline via
`#outlineGroup use { fill: none; ... }` — which silently did nothing, and
every letter rendered as a solid black blob instead of a thin stroke. A
value specified directly on an element (even a low-specificity
presentation attribute like `fill="black"`) always wins over an inherited
value, regardless of how specific the inheriting rule's selector is. Fix:
the `fill: black` the mask needs now lives on `#washTitleGroup` (the
paths' parent) via CSS instead of on the paths themselves, so neither
instance has a directly-specified fill blocking the other from getting
its own via inheritance.

Both title layers use the **same path data**, positioned by the same
`(x, y, scale)` numbers computed in JS each time the layout changes, so
the hole in the wash and the white glyphs on top of it always land in
exact pixel registration.

### Why there are 15 separate letter paths, not one string

The title glyphs are vector outlines (traced letterforms, not real text —
see `html-js-site/images/title-text.svg` for the canonical, un-split,
original-kerning version), and they exist in **three** places that must
stay in sync if the letterforms ever change: filled black inside the
`<mask>` (the hole), filled white as the resting glyph layer
(`.title-white`), and referenced by `#outlineGroup`'s `<use>` elements
(the pinned-state edge stroke).

Each letter is its own `<path id="glyphN">` (mask copy) /
`<path fill="white">` (white copy), individually positioned with its own
`transform="translate(dx, 0)"`, rather than the whole word as one path
string. That's for kerning: the source letterforms came with wildly
inconsistent spacing baked in (some letters touching with zero gap, a
couple actually *overlapping* — `R`→`A` in `REGENERACIÓN` sat 4.8 units
into `R`, not after it), which isn't fixable by manipulating one merged
path's coordinates. Fixing it required splitting every letter out
individually.

The kerning fix itself: each letter's real bounding box was measured with
a headless-browser `getBBox()` pass (not eyeballed), then every letter was
repositioned so consecutive bounding boxes sit 6 viewBox units apart by
default (`GAP` in `/tmp/genkern.js` at the time this was done — the
generation script isn't kept in the repo, just its output). The gap
between the two words stays the larger, separately-chosen 70 units (see
below).

A uniform gap is only an *approximation* of optical kerning — true optical
kerning (what Photoshop's "Optical" mode does) varies the gap by the
visual shape of each pair, because identical numeric gaps don't read as
identical visual gaps when the letter shapes differ. `A`→`C` was the
visible case here: `A`'s open diagonal side plus `C`'s open round side
made the uniform 6-unit gap look distinctly wider than its neighbors, even
though the number was the same. Fixed with a per-pair override
(`GAP_OVERRIDES = { 8: 2 }` — index 8 is `C`, the letter right after `A`
— tightened to 2 units instead of 6) rather than changing the global gap,
since every other pair already read fine. If another pair looks off by
eye, the fix is the same: add that letter's index to `GAP_OVERRIDES`
rather than touching the global `GAP`.

The word gap (`REGENERACIÓN` → `LAB`) is a separate, larger value (70
units) added once at the word boundary — the same word-gap-only fix from
before this full re-kern, now applied to letter 12 (`L`) specifically
instead of "the whole second path."

### Why the animation doesn't scrub with the scroll wheel

Earlier versions of this file tied the title's position directly to
`scrollY` (a "scrub" — move it exactly as far as the page has scrolled).
That felt stepped/staccato, especially with a mouse wheel's discrete
notches, because the position update was driven by the `scroll` event's
own (irregular) firing rate.

The current version instead treats the interaction as a **two-state
toggle**: `activated = false` (bottom, white) or `activated = true` (top,
image-filled). Crossing a scroll threshold just flips that boolean; the
actual motion is a plain CSS `transition` on `transform`/`opacity`, which
the browser's compositor animates smoothly regardless of how choppy the
triggering scroll events were. (SVG elements' `transform` is a real CSS
property in modern browsers, so `#washTitleGroup` transitions the same
way the HTML layers do.)

One wrinkle: the very first style write on page load would otherwise also
get caught by that same `transition` rule (the browser sees it as a
"change" from the stylesheet's default position and animates in
uninvited). `applyState(instant)` works around this by zeroing
`transitionDuration` for one update, forcing a layout flush, then
restoring it — so only scroll-triggered changes actually animate.

## Tunables (top of the `<script>` block)

| Constant | What it controls |
|---|---|
| `IMAGE_POOL` | Candidate background photos (see below) |
| `TITLE_MAX_WIDTH` / `TITLE_SIDE_MARGIN` | How large the title renders — `min(viewport width - 2 × side margin, max px)`. Side margin is a fixed 16px, not a vw-based fraction: a fraction leaves a gap that scales with viewport width, which read as an almost-but-not-quite-edge-to-edge mistake at some widths rather than a deliberate margin. The max-px cap only matters on ultra-wide monitors. |
| `BOTTOM_MARGIN_FRACTION` | Gap from the bottom edge at rest (`0` = flush; the glyphs already reach the edge of their own viewBox, so no margin is needed to avoid clipping) |
| `ACTIVATE_AT` / `DEACTIVATE_AT` | Scroll distance (px) that triggers the rise / the reverse (two different thresholds avoid flicker right at the boundary) |
| `LANDING_SCROLL_Y` / `GATE_DURATION_MS` | The first-scroll gate: docking position (60px) and gate duration (1200ms) that absorbs runaway multi-notch wheel momentum on initial scroll so the content lands cleanly below the title without overshooting |
| `CONTENT_REVEAL_DELAY_MS` | How long `.page-content` (and the drop cap) wait after the title starts rising before they fade in -- see "The page below the hero" |
| `FAST_FILL` | Option A's timing tweak: fades the white title / photo-filled layer over 0.5s instead of 2s. Currently off -- see "Two renderers" |
| `SOLID_TITLE_CAP` | The "title turns totally black at the top" feature, on a switch. `true` fades `.title-cap` (an opaque solid duplicate of the pinned title) in over the mask window 2s after activation, for deep-scroll legibility (see "The page below the hero"); `false` (current preference) never shows it, so the pinned title stays the live photo-through-the-letters mask with `#outlineGroup`'s edge stroke instead. |
| `TITLE_RENDERER` | `'window'` (default, option B) or `'mask'` (option A's architecture, kept as a fallback) — how the photo-through-the-letters effect is produced; see "Two renderers" under "How it's built" |
| `.hero-spacer` height, set in `layout()` | `Math.round(scaledHeight + 130)` — sized dynamically with title height so docking at `LANDING_SCROLL_Y` leaves a tight, elegant ~100px gap below the pinned title to the tagline and menu |
| the `2s cubic-bezier(0.16, 1, 0.3, 1)` in each `transition` rule | Animation duration/easing — all the position-animated layers (image, wash, title, wash's title group) plus the quote's opacity share this so they stay in sync; `.title-white`'s opacity (0.5s — see "How it's built" bullet 3, hides the compositor/raster pipeline gap between the two glyph layers), `.page-content`, and `.title-cap` fade on their own shorter timers instead (see "The page below the hero") |
| `--title-height`, set in `layout()` | The title's own rendered height, in px -- the one thing that reads it is `.title-cap`'s `height` (clips it to exactly the title's row, see "The page below the hero" for why that element exists) |

Two more tunables live as CSS custom properties on `:root` instead, since
they're colors rather than layout numbers: `--wash-color` (see "Wash
color" above) and `--outline-color` (the pinned title's edge stroke).

## The quote

`<blockquote class="quote" id="quote">` sits above the title at rest —
`top: 34%`, centered, `width: min(80vw, 640px)` so it wraps to a readable
column instead of stretching edge-to-edge like the title does. It's added
to the same `ANIMATED_ELS` array as the title/wash/image, so it picks up
the shared 2s transition and the load-time "don't animate in" fix
automatically; `applyState()` just sets its opacity opposite to the wash's
(visible at rest, gone once `activated`).

**Typeface:** one family, Instrument Serif, loaded from Google Fonts
(this page's one external dependency) — not a new choice, it's
`--font-serif` in the sibling `design-revamp/new-test/styles.css`
prototype. The quote line is italic; the byline is the *same* family set
upright, uppercase, and tracked out (`letter-spacing: 0.18em`) so it
reads as a clearly distinct, legible register without being a second
typeface. Originally tried pairing it with IBM Plex Mono for the byline
(matching `.quote-label` in the live theme, `regen_wp/style.css`) — reads
well on its own, but combined with the quote's serif and the title's own
separate vector lettering, that's three typefaces on one screen, which
felt like one too many. Georgia stays in the `font-family` stack as the
fallback if the Google Fonts request fails.

**Legibility over an arbitrary random photo:** `adaptQuoteInk()` in the
`<script>` runs once, when `heroImage` finishes loading. It works out
(via `object-fit: cover`'s own crop/scale math) exactly which rectangle of
the *source photo* is showing at the quote's on-screen position, draws
just that rectangle into a tiny offscreen `<canvas>`, averages its
luminance, and sets two CSS custom properties (`--ink`, `--ink-shadow`) to
either white-on-dark or near-black-on-light. No visible background
treatment at all — it's just picking plain type color, the way a designer
would eyeball it per photo, automated into a one-time pixel sample. Falls
back to the CSS default (white) inside a `try/catch` if canvas sampling
ever throws (e.g. a tainted canvas under some `file://` setups).

Two earlier attempts, kept here so they don't get re-tried by accident:

1. `mix-blend-mode: difference` (invert the text against whatever's
   behind it, no image reading needed) — looked great over a single-tone
   backdrop (a dark rock face, pale sand) but fell apart on mixed-tone
   ones: a horizon half blue sky / half gray mountain turned the inverted
   text a muddy, low-contrast brown, since the invert is computed per
   pixel against two very different regions at once inside one small text
   block.
2. A blurred dark radial-gradient scrim sitting behind the text — fixed
   the contrast problem, but reads as a visible dark smudge/halo sitting
   on top of the photo, which doesn't belong there visually.

Verified the current approach across the whole pool, including that mixed
sky/mountain case and a couple of bright, nearly-cloudless-sky ones that
come out the other way (dark ink).

## The page below the hero

The content column opens with the live site's header content: the
blogdescription tagline ("Laboratory for community-based and research
justice orientated media, creative-critical research, and decolonial
praxis.") and the primary menu (Home / About / Projects / Residents /
Students / Support, in WP menu order), pulled from the local install
(`wp option get blogdescription`, `wp menu item list 2`) to keep the
prototype honest to the real thing. Translated into this page's
two-typeface system rather than copied: the tagline keeps the live
tagline's italic-serif-in-accent-green look (Instrument Serif standing
in for Libre Baskerville, #1e4a26 verbatim), and the nav borrows the
project-meta's tracked-uppercase Georgia voice since Inter is banned
here (see the .page-projects comment). The giant pinned title IS this
page's site-title, so only tagline + nav are needed. Links are href="#"
-- their real targets are WP permalinks this static prototype doesn't
have.

`.hero` is `position: fixed`, so it takes up no space in normal document
flow — the actual scroll room for its own interaction comes from
`.hero-spacer`, a plain empty div right after it whose height is set by
`layout()` (just enough to trigger and confirm the pin, the same formula
that used to be set directly on `<body>`).

`<main class="page-content" id="pageContent">` comes right after the
spacer. Where it sits in the stacking order and document flow went
through four versions:

- **First: normal document flow**, sliding up over the fixed hero as you
  scrolled. Worked, but meant a long stretch of extra scrolling with
  nothing happening between the title pinning and the page actually
  appearing (the spacer had to be tall enough to let the title finish
  rising *before* the page could start sliding over it).
- **Second: a fixed overlay, delayed with `transitionDelay`**, waiting
  for the title's 2s rise to finish before fading in (like
  `#outlineGroup` still does). No extra scrolling needed, but the
  paragraph only appeared a beat after the title had already settled —
  asked to fix that.
- **Third: no delay, but added a `transform: translateY()` slide** so
  the box would rise into place rather than just appear -- reasoning:
  the fixed overlay already sat at its final position (`top:
  var(--title-height)`, covering everything below that) the whole time,
  invisible; fading it in with zero delay meant it spent most of the
  title's 2s rise sitting on top of (hiding) the still-rising title. The
  slide was meant to dodge that by starting off-screen and easing up in
  step with the title. It technically worked, but a visibly sliding white
  panel is a stranger, more distracting thing to watch than a plain fade
  — this was the actual complaint (not the title-hiding bug it was fixing).
- **Fourth: a fixed overlay again, no transform, `CONTENT_REVEAL_DELAY_MS`
  (900ms) instead.** Plain opacity fade, `transitionDelay` set from this
  one constant, short enough to feel like it's arriving with the title
  (whose `cubic-bezier(0.16, 1, 0.3, 1)` easing is heavily front-loaded —
  see "Why the animation doesn't scrub") without covering it mid-rise.
  This is also the version that first held more than a short paragraph:
  once `.page-projects` made the box taller than the viewport, its own
  `overflow-y: auto` became a second, independent scroll region nested
  inside the fixed overlay. That's what caused the next problem —
  scrolled-up content disappeared behind the fixed title with no visible
  relationship between the two, which just reads as content blocked by an
  opaque bar, because that's exactly what it was.
- **Fifth: back to normal document flow, but still with the old
  `.hero-spacer` height** (one full viewport plus `~300px`, left over from
  when `.page-content` was a fixed overlay and that height only needed to
  cover the hero's own interaction). That silently reintroduced the exact
  first-attempt problem: opacity already gates when `.page-content` is
  *visible* (`0` until `activated`), so that extra viewport of spacer
  bought nothing but a dead stretch of scrolling -- title pins almost
  immediately (`ACTIVATE_AT` is just 40px), then nothing else happens for
  most of another full viewport of scrolling before any content appears.
  Reported back as literally the thing this section already described
  fixing once.
- **Sixth: shrink `.hero-spacer` down to the bare floor needed to trigger
  the rise and confirm the pin holds** (tried at `300`, close to
  `ACTIVATE_AT * 6`). Fixed the dead-stretch problem, but overcorrected
  into the opposite one: with the spacer this short, an ordinary scroll
  gesture (~150-250px, not an extreme fling) already had the first
  paragraph crowding right up against the title -- reported back as "too
  close."
- **Seventh: `550` as the floor, not `300`.** Chosen by checking
  `.dropcap-container`'s on-screen position at a spread of realistic
  scroll amounts (60px, 150px, 250px, 400px) -- comfortably clear of the
  title across that whole range, while still on screen (not a dead scroll
  stretch) right from the first moment past `ACTIVATE_AT`. Reported back
  as having swung too far the other way -- too much gap now.
- **Current (eighth): `425`, splitting the difference between `300` and
  `550`.** `300`, `425`, and `550` are all "small" next to the original
  `vh + 300` (roughly 1200px on a typical viewport) -- every round of
  this was retuning the floor within that same small range, not
  reversing the decision to drop the added viewport.

  Both this and the previous attempt keep the same underlying idea:
  crossing `ACTIVATE_AT` and scrolling `.page-content`'s top into view
  happen in essentially the same motion, so content reads as arriving
  *with* the title, not some scroll-distance later. Continuing to scroll
  then visually catches up to and covers the title while it may still be
  settling from its own 2s rise, rather than only after a long dead
  stretch, which reads as perfectly ordinary (a page scrolling up over
  its own header is the single most common thing on the web) -- unlike
  the third attempt's *artificial* slide, which had no such precedent to
  read naturally against. One single document scrollbar now, for the
  hero interaction and everything below it -- `overflow-y: auto` is gone
  from `.page-content` entirely, since a second, nested scroll region was
  the actual problem, not a detail to keep tuning.

The title is still meant to persist as a header for as long as there's
room for it -- `.page-content` doesn't have a `top` offset to avoid
sitting under it, because passing underneath it (as you keep scrolling)
is the point, not something to prevent.

- **Ninth: content scrolling underneath the title turned out to look
  broken, not just "covered."** `.page-content` has no background of its
  own (see below), so as its text scrolled up into the title's row, both
  layers -- title glyphs and paragraph glyphs -- were compositing
  together with nothing opaque between them: legible letters directly
  superimposed on other legible letters, an illegible double-exposure.
  Reported back plainly: *"the page title should always be on top.
  content should go underneath it."* Simply raising `.hero`'s z-index
  above `.page-content` was not an option -- `.hero-image` (the photo)
  fills the entire viewport at all times, so that would have hidden the
  *whole page* below the title, not just this one row.
- **Current (tenth): `.title-cap`, a second, simplified copy of the
  pinned title, layered above `.page-content` instead of `.hero` being
  raised wholesale.** Solid `--wash-color` backdrop plus solid
  `--page-text`-filled glyphs (reusing the same `<path>`s as the mask/
  outline via `<use>`, one source of truth for the letterforms, same
  trick `#outlineGroup` already used) -- no mask, no photo-through-the-
  letters here. `--title-height` is back as a CSS variable for this one
  purpose: clipping `.title-cap` to exactly the title's own rendered
  height, so it only ever covers its own row and nothing below it. Its
  glyph group's transform is set from the exact same line that drives
  `#outlineGroup`, so the two stay in perfect registration; its opacity
  follows the same 2s-delayed fade. The trade-off: the window-onto-the-
  photo effect no longer shows through *specifically* where content has
  scrolled up underneath the title -- everywhere else (at rest, right as
  it pins, right as `.page-content` first appears) `.wash` and
  `#outlineGroup` still do the real thing exactly as before. Solid and
  legible beat "technically still the photo effect, but illegible" here.

  **Update -- now on a switch:** `SOLID_TITLE_CAP` (see Tunables),
  default `false`. The cap turned out not to wait for deep scrolling at
  all: it faded in 2s after the title *pinned*, covering the mask
  window outright (the "title turns totally black at the top" report)
  and hiding `#outlineGroup` behind its opaque backdrop at the same
  moment -- two regressions from one layer. With the flag off, the cap
  never fades in: the photo-through-the-letters effect and the outline
  persist at the pinned state, which is the current preference, at the
  accepted cost of the deep-scroll double-exposure described above.
  Flip the flag to `true` to restore the solid-cap behavior exactly as
  written here.

`.page-content` no longer needs `pointer-events: none` while invisible,
either -- that was specifically for the fixed-overlay era, when it
covered the whole remaining viewport at all times regardless of opacity.
Back in normal flow, it's off-screen (below the fold) until scrolled to,
same as any other page content.

One consequence worth knowing, resolved by `.title-cap` while it's
enabled (`SOLID_TITLE_CAP`; off by default, so currently an accepted
trade-off rather than a resolved one): `.page-content`
still has no background of its own (see "Wash color" above for why), and
its actual content — the paragraphs, the projects grid — sits in a
centered column narrower than the full viewport. Before `.title-cap`
existed, that meant the title's outer edges, outside that centered
column's width, stayed visibly *inconsistent* with its own center --
solid where `.page-content` had scrolled underneath it, unobstructed
(still the photo-through-letters effect) everywhere else, in the very
same row. `.title-cap`'s own backdrop is full viewport width regardless
of `.page-content`'s column width, so the title now reads as one
consistent, fully opaque bar end-to-end whenever it's actually needed to
cover something -- no more of that mismatch.

A second consequence: the drop cap's `hero-activated` event still fires
from the hero's own early `ACTIVATE_AT` threshold (40px scrolled), not
from `.page-content` actually entering the viewport. With the spacer this
short, on typical scrolling the drop cap will usually finish growing
before the paragraph has even scrolled into view — so what you actually
see arrive on screen is the already-grown result, not the animation. See
the comment above `notifyDropcap()` in the code for the fix (an
`IntersectionObserver` on `#dropcapContainer`, viable again now that
`.page-content` is real document flow) if that visible growth turns out
to matter more than keeping one single trigger for everything tied to the
hero.

`.page-content` now holds the live homepage's first three paragraphs (WP
page ID 7, pulled via `wp post get 7 --field=post_content`): the opening
paragraph features an editorial drop-cap effect on the opening letter "R".
Originally prototyped on an HTML5 `<canvas>` using `@chenglou/pretext`, this
has been refactored into clean, semantic HTML:
`<p class="dropcap-paragraph" id="dropcapContainer"><span class="dropcap-letter">R</span>egeneration...</p>`
with CSS float and cubic-bezier transitions.

Key advantages of this semantic HTML approach:
- **100% Selectable & Copyable:** Users and assistive technologies can select, copy, and search all paragraph copy naturally without being blocked by a canvas element.
- **Zero Heavy Dependencies:** Eliminates external canvas-layout script bundles (`@chenglou/pretext`) while preserving smooth typography animation.
- **Fully Responsive:** Uses media queries (130px on desktop, 96px on mobile) and native browser text reflow.
- **Trigger Mechanism:** Listens for the `hero-activated` custom event to toggle the `.dropcap-grown` CSS class with smooth cubic-bezier easing.

### Mobile and Touch Support

Mobile browsers don't emit `wheel` events, and touch flicks carry substantial inertia that could fling the viewport past the hero and tagline. The site features dedicated touch handling:
- **Swipe-up Gate:** On mobile devices, an initial swipe-up gesture (`touchmove` with `diffY > 12`) triggers `triggerEntrance()`, docking smoothly at `LANDING_SCROLL_Y = 60px` with momentum absorption during the entrance duration (`GATE_DURATION_MS = 1200ms`).
- **Direction Reversal:** Swiping back down during entrance cancels the gate and returns immediately to the resting state at `scrollY = 0`.
- **Manual Scroll Restoration:** Sets `history.scrollRestoration = 'manual'` to guarantee clean page starts and reloads from the hero screen.

### Projects grid

After the three paragraphs, `.page-projects` holds three real `project`
posts (`#17` Indigenous Border Studies, `#20` Safiya Henderson Holmes
Black Arts & Radicalism Archive, `#30` Museum of Us Exhibit), pulled via
`wp post get <id>` / `wp post meta list <id>` rather than typed by hand —
picked for range: one with no `project_style` set (defaults to
turquoise), one `brown`, one with an external `project_link_url` instead
of an internal permalink. Testing a structurally different kind of
content than body paragraphs — headings, short meta lines, colored card
backgrounds — against the same wash/photo treatment was the point of
adding it.

Colors are the live theme's own accent variables
(`regen_wp/style.css` → `--color-secondary-green` `#2a9d8f`,
`--color-earth-brown` `#9c5424`), not invented ones, so the cards are a
real test of the live brand palette against this page's backgrounds.
Typefaces deliberately stay within this page's existing two — Instrument
Serif for card titles/the section header, Georgia for everything else —
rather than pulling in the live theme's card typefaces (Inter, IBM Plex
Mono). Bringing those in would reintroduce exactly the "too many
typefaces" problem this page already solved once for the quote (see "Why
there are 15 separate letter paths" above for that history) — a card grid
gets to borrow the live theme's colors without also borrowing its type
system.

Two intentional differences from how `front-page.php` actually renders
these:

- **Badges show whenever `project_badge` is set.** The live template
  only ever renders a badge when its text is literally `"Ongoing"`
  (case-insensitive) — `#17` and `#20` both have `project_badge` set to
  `"NEW"`, which never actually shows up on the live site. Looks like an
  unintentional bug there (the badge and the separate `project_meta`
  field, which *does* independently say "Ongoing" for `#17`, seem to have
  gotten crossed), not something worth reproducing here.
- **`#17` and `#20`'s "Explore" links go nowhere** (`href="#"`) since
  their real target is a WordPress permalink this static, database-free
  prototype has no equivalent of. `#30`'s link is real and external
  (`museumofus.org`, opens in a new tab) since that one's `project_link_url`
  already pointed off-site.

Adding this grid is also what first made `.page-content` taller than the
viewport, which is what originally surfaced the fixed-overlay-plus-
internal-scroll problem described in "The page below the hero" (a second,
nested scroll region that clipped scrolled content behind an opaque bar
with no visible relationship to what was behind it). `.page-content` is
back in normal document flow now — one single document scrollbar reaches
all the way through the grid, and it visually covers the still-pinned
title as you scroll past it, the same way any ordinary fixed header gets
covered by content scrolling over it. See that section for the full
history.

## Random background image

`heroImage.src` is picked at random from `IMAGE_POOL` — currently a
short hand-picked shortlist, not "every wide photo in the folder" — on
every page load. **Images in the pool must be wide**, because the photo
is wider than the viewport in aspect ratio, so `object-fit: cover` only
ever crops its *left/right* edges. Its full height always renders,
meaning only the very top strip of the photo is ever visible once pinned
(that's the only part showing through the title). A portrait photo would
crop top/bottom instead and break this assumption; a photo with sky/
negative space along its top edge (e.g. `JumpinCholla.jpg`) will wash
part of the pinned title out blank.

**To lock in a final image:** replace the `chosenImage = IMAGE_POOL[...]`
random pick with a hardcoded filename, and delete the now-unused
`IMAGE_POOL` array.

## Viewing it

Open `index.html` directly in a browser (`file://` works fine — unlike
the SPA prototypes elsewhere in `html-js-site/`, this page doesn't `fetch()`
anything, it only needs an `<img src>` to resolve). A local static server
also works if you prefer one:

```bash
cd html-js-site
python3 -m http.server 8000
# http://localhost:8000/design-revamp/mask-experiment/
```

## Known limitations

- The per-letter kerning offsets, the word-space gap (70 units), and the
  general title scale were all tuned against this specific glyph set; if
  the letterforms change (a new trace, a real webfont, anything), all of
  it needs re-deriving, not just re-checking.
- No mobile-specific layout pass yet — it's responsive (width scales with
  viewport) but hasn't been tuned for touch-scroll behavior or small
  screens specifically.
- Not yet ported into `regen_wp/` — this file is the design sandbox, not
  the implementation.
