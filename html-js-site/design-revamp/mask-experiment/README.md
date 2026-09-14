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
5. **Keep scrolling past the hero** and the page proper emerges as a
   fade, not a scroll-driven reveal — see "The page below the hero" for
   why. For now that's the live homepage's first three paragraphs (a
   growing drop-cap effect on the opening paragraph's first letter) plus
   a small grid of three real projects below them — see "Projects grid".
   Past this point, scrolling further scrolls *inside* that fixed box,
   not the page.

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
   you actually see at rest. It fades to `opacity: 0` exactly as `.wash`
   fades to `opacity: 1`, so the crossfade reads as the white letters
   "filling in" with the photo.
4. **`#outlineGroup`** — a thin stroked outline traced around the same
   letterforms, sitting on top of everything. Guarantees the pinned title
   stays legible as a shape even when the photo showing through it
   happens to land close in tone to `--wash-color` (see "Wash color"
   below). Unlike the other three layers, it doesn't animate alongside
   the 2s rise: its `transform` has no transition at all (snaps straight
   to the title's current position every frame, so the browser isn't
   re-stroking it for 2s of motion it doesn't need to -- a stroke repaints
   more expensively than a fill), and its opacity fade is delayed via
   `transitionDelay` until that rise has actually finished. Tying its
   opacity to the same trigger as `.wash` (both `2s`, both starting
   together) made it visibly trail behind the moving letters instead.

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
2. That wasn't the whole bug: the drop-cap `<canvas>` (inside
   `.page-content`, see "The page below the hero") was *also* painting
   its own opaque rectangle every frame (`ctx.fillRect` with `--page-bg`,
   needed to clear the previous frame's text) -- on a context created
   with `{ alpha: false }`, so it was never capable of showing anything
   behind it no matter what color that fill used. Same mistake, just in
   canvas pixels instead of CSS. Fixed by dropping `{ alpha: false }` (so
   the canvas can genuinely be transparent) and swapping the colored
   `fillRect` for a plain `clearRect` -- it now paints only the drop cap
   and text, nothing else, and the wash shows through everywhere else on
   the canvas exactly like it does everywhere off the canvas.

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
| `CONTENT_REVEAL_DELAY_MS` | How long `.page-content` (and the drop cap) wait after the title starts rising before they fade in -- see "The page below the hero" |
| body height, set in `layout()` | `vh + max(ACTIVATE_AT * 6, 300)` — just enough scroll room to comfortably cross `ACTIVATE_AT` and confirm the pin holds after. Was `vh * 2.2`, a leftover from an earlier version that scrubbed the whole animation across the scroll distance; once it became a threshold crossing, that left ~1000px of dead scroll space below the already-pinned title. |
| the `2s cubic-bezier(0.16, 1, 0.3, 1)` in each `transition` rule | Animation duration/easing — all five animated layers (image, wash, title, wash's title group, quote) share this so they stay in sync |

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

`.hero` is `position: fixed`, so it takes up no space in normal document
flow — the actual scroll room for its own interaction comes from
`.hero-spacer`, a plain empty div right after it whose height is set by
`layout()` (just enough to trigger and confirm the pin, the same formula
that used to be set directly on `<body>`).

`<main class="page-content" id="pageContent">` comes right after the
spacer, but it's *not* something scrolled into view. First version had it
in normal document flow, sliding up over the fixed hero as you scrolled —
but that meant a long stretch of extra scrolling with nothing happening
between the title pinning and the page actually appearing. It's a fixed
overlay instead: a plain opacity fade, triggered by the same
`activated` boolean as everything else in `.hero`. Getting the *timing*
of that fade right took three tries:

- **First: delayed with `transitionDelay`**, waiting for the title's 2s
  rise to finish before fading in (like `#outlineGroup` still does). No
  extra scrolling needed, but the paragraph only appeared a beat after
  the title had already settled — asked to fix that.
- **Second: no delay, but added a `transform: translateY()` slide** so
  the box would rise into place rather than just appear -- reasoning:
  `.page-content`'s box already sat at its final position (`top:
  var(--title-height)`, covering everything below that) the whole time,
  invisible; fading it in with zero delay meant it spent most of the
  title's 2s rise sitting on top of (hiding) the still-rising title. The
  slide was meant to dodge that by starting off-screen and easing up in
  step with the title. It technically worked, but a visibly sliding white
  panel is a stranger, more distracting thing to watch than a plain fade
  — this was the actual complaint (not the title-hiding bug it was fixing).
- **Current: `CONTENT_REVEAL_DELAY_MS` (900ms), no transform at all.**
  Plain opacity fade, `transitionDelay` set from this one constant. The
  delay is short -- much shorter than the original 2000ms -- because the
  title's `cubic-bezier(0.16, 1, 0.3, 1)` easing is heavily front-loaded
  (most of its motion happens in roughly the first second; see the
  position samples in "Why the animation doesn't scrub"). By 900ms the
  title has already visually settled into its safe zone above
  `--title-height`, so the fade can start well before the nominal 2s mark
  without covering it, and with no motion of its own to look strange.
  `notifyDropcap()` uses the same constant so the drop cap starts growing
  right as the paragraph appears.

`--title-height` itself is set by JS in `layout()` from the title's own
rendered `scaledHeight`, recomputed on resize same as everything else
there. The title is meant to persist as a header once the page below it
is showing, not disappear once `.page-content` arrives -- and since the
title itself is unaffected by any of this (the wash/outline mechanism
keeps working exactly as before), it keeps reading as a window onto the
hero photo even while sitting above an otherwise ordinary page.

Since it's `position: fixed` and covers the whole remaining viewport at
all times, it needs `pointer-events: none` while
invisible — otherwise, even at `opacity: 0`, it would sit on top of
everything (its `z-index: 2` is what lets it cover `.hero`, which never
sets its own) and intercept clicks and scroll-wheel input, breaking the
very scroll gesture that's supposed to reveal it.

`.page-content` now holds the live homepage's first three paragraphs (WP
page ID 7, pulled via `wp post get 7 --field=post_content`): the opening
paragraph gets a growing drop-cap effect, reused from
`design-revamp/pretext-experiment/index.html` (one level up) almost
unchanged — same `@chenglou/pretext` canvas-layout library (loaded from
`esm.sh`, this page's second external dependency alongside Google Fonts),
same grow animation, same `layoutNextLine` reflow-without-DOM-thrash
technique. The other two paragraphs are plain HTML in a `.page-copy` div
right after `dropcap-container` — no drop cap or per-frame reflow needed
once the opening paragraph has already settled, so there's no reason to
route them through the canvas too. Four changes from the original demo
(all in the drop-cap paragraph specifically):

- **Real copy.** The demo's paragraph described the effect itself; this one
  is the actual opening paragraph of the live site's homepage, so the
  effect can be judged against real lab copy instead of filler text.
- **No accent color.** The original animates the drop cap from dark gray
  to an amber accent (`#d2691e`) as it grows. Here it stays `--page-text`
  (the same color as the surrounding paragraph) throughout — asked for
  explicitly, so the effect could be judged on its own without also
  introducing a new accent color to the page.
- **Typefaces.** Drop cap: **Instrument Serif** (already loaded above for
  the quote/byline — reusing it here rather than adding a fourth
  typeface). Body copy: **Georgia** — a serif specifically drawn by
  Matthew Carter for legibility on screens at small sizes, and already
  present as a fallback everywhere else on this page, so it needed no
  additional font load. The pairing follows ordinary editorial practice:
  a display face for the one big letter, a dedicated text face for actual
  reading, rather than stretching one face to do both jobs.
- **Trigger mechanism.** The original demo watches its own container with
  an `IntersectionObserver`, which made sense when the container scrolled
  into view. Once `.page-content` became a fixed overlay (see above), that
  signal broke silently: the container sits within the viewport's bounds
  geometrically from the very first frame, opacity aside, so the observer
  fired immediately on page load and the drop cap finished growing to
  full size *before the paragraph was ever visible* — the whole effect
  was happening, just invisibly, with nothing left to animate by the time
  you could actually see it. Replaced with an explicit `hero-activated`
  custom event, dispatched from `notifyDropcap()` in the hero's own
  `onScroll()` after the same `CONTENT_REVEAL_DELAY_MS` delay
  `.page-content`'s own fade uses (see "The page below the hero" for the
  full history of that timing), and listened for here instead of
  observing anything geometrically. The drop cap now starts growing right
  as the paragraph fades in, rather than before or long after it.

One thing not carried over from the original demo: real content-based
height measurement, at the canvas level. `dropcap-container`'s canvas
still draws into a fixed-size buffer (`CANVAS_HEIGHT`, 420px, sized by eye
generously enough for a few paragraph lengths) rather than measuring text
before drawing it. But once `.page-copy`'s plain paragraphs started
sitting right after it, that fixed buffer's unused space at the bottom
showed up as a visible gap before "However, the Spanish term..." — so
`render()` now also computes the actual drawn content's height each frame
(text bottom, or the drop cap's own visual bottom if that's taller) and
sets `dropcap-container`'s own CSS height to match; `overflow: hidden` on
the container then crops the canvas's unused buffer space away without
touching its draw resolution. If the drop-cap paragraph's copy changes
meaningfully in length, the crop still tracks it automatically — only
`CANVAS_HEIGHT` itself (the draw buffer's ceiling) would need revisiting,
and only if a much longer paragraph actually exceeded it.

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

`.page-content`'s `overflow-y: auto` — previously a defensive fallback
"untested, since the current single paragraph doesn't need it" — is now
actually exercised: the grid makes `.page-content` taller than the
viewport, and scrolling further (once activated) scrolls *inside* that
fixed box rather than the document, since `.hero-spacer` only reserves
scroll room for the hero interaction itself. Confirmed working via a
headless-Chrome check — the pinned title stays put above the content
while the grid scrolls underneath it.

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
