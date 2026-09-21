// Page config (window.REGEN_HOME.imageBase) is printed by the page: the theme
// sets it to its images folder, the static prototype to a relative path.
// ---- Random hero photo ------------------------------------------
// Shortlisted candidates only (picked by hand, not "all wide
// photos" anymore). Re-rolled on every load; hardcode one once a
// favorite is picked.
const IMAGE_POOL = [
    'JumpingCholla3.jpg', 'desertsunset.jpg', 'AsequiasSanLucy.jpg',
    'PaintedRock.jpg', 'ImperialDam.jpg',
    'JumpingCholla1.jpg', 'VikamDoag3.jpg',
    'JumpinCholla.jpg', 'OcotilloLeaves.jpg'
];

// ---- Tunables -----------------------------------------------------
const VB_W = 1281.50, VB_H = 97.00, ASPECT = VB_W / VB_H;
const TITLE_MAX_WIDTH = 2400; // px cap, only matters on ultra-wide monitors
// A fixed px gutter reads as an intentional margin at any screen
// size; a vw-based fraction (tried first) leaves a gap that scales
// with viewport width, which at some widths looked like an almost-
// -but-not-quite-edge-to-edge mistake rather than a deliberate one.
const TITLE_SIDE_MARGIN = 16;
const BOTTOM_MARGIN_FRACTION = 0; // glyphs already reach the viewBox edge -- flush to the window
const ACTIVATE_AT = 40;  // px scrolled before the rise-to-top animation triggers
const DEACTIVATE_AT = 50; // px scrolled below which it drops back down (close to LANDING_SCROLL_Y so reverse triggers cleanly)
// First-scroll gate: absorbs runaway wheel delta on initial entrance from rest
const LANDING_SCROLL_Y = 60; // px: scroll position where hero docks and content rests cleanly
const GATE_DURATION_MS = 1200; // ms: duration to absorb multi-notch wheel impulses during entrance rise
// How long .page-content and the drop cap wait before appearing,
// once the title starts rising. Not 0: the title's ease-out curve
// is front-loaded (most of its motion happens in the first ~1s of
// its own 2s transition -- see the README), so this just waits for
// it to have visually reached its safe top zone first. Short
// enough to read as "arriving with the title," not after it.
const CONTENT_REVEAL_DELAY_MS = 900;
// The "title turns totally black at the top" feature, on a
// switch. .title-cap is a solid duplicate of the pinned title
// (opaque --wash-color backdrop + solid --page-text glyphs)
// layered above .page-content -- added so paragraph text
// scrolling up into the title's row can't double-expose over
// the letters. The cost: while it's faded in it covers the
// real mask window outright, so the photo-through-the-letters
// effect AND the outline traced around it both vanish the
// moment the title pins. false (current preference) keeps the
// mask + outline live at the pinned state and accepts the
// deep-scroll legibility trade-off; flip to true to bring the
// solid cap back.
const SOLID_TITLE_CAP = false;
// Outline traced around the pinned title letters (false = clean mask without stroke, true = stroked)
const SHOW_TITLE_OUTLINE = false;
// ---- Renderer flags (options A and B, both kept available) -----
// TITLE_RENDERER picks how the photo-through-the-letters effect is
// produced:
//   'window' -- option B (default). .wash's rect loses its mask
//     attribute at startup (plain solid wash, nothing rasterized
//     per frame) and .title-window draws the letters instead: a
//     title-sized div masked by an element-local glyph mask, with
//     a viewport-sized copy of the hero photo inside it
//     counter-translated to stay viewport-registered. Every
//     moving part is a composited element transform, so the fill
//     can't trail the glyphs the way the 'mask' renderer's hole
//     does under load.
//   'mask' -- option A's original architecture, kept working as a
//     fallback: the SVG mask punches the title-shaped hole out of
//     the wash rect and #washTitleGroup carries the hole's
//     transform. The hole is mask *content*, so the full-viewport
//     mask re-rasterizes on the CPU every frame of the rise --
//     under load it trails the composited white title, visible
//     mid-crossfade as one title lagging behind the other (worse
//     in Firefox than Chrome in testing).
const TITLE_RENDERER = 'window';
// FAST_FILL -- option A: fade the white title out / the window
// layer in over 0.5s instead of the full 2s, so the "fill" beat
// happens in place at the bottom rather than spread across the
// rise. Built as a cheaper lag mitigation for 'mask' mode;
// harmless but unnecessary in 'window' mode (both layers there
// are composited and stay pixel-aligned even mid-crossfade).
const FAST_FILL = false;

const chosenImage = IMAGE_POOL[Math.floor(Math.random() * IMAGE_POOL.length)];
const heroImage = document.getElementById('heroImage');
heroImage.src = ((window.REGEN_HOME && window.REGEN_HOME.imageBase) || '') + chosenImage;

const quote = document.getElementById('quote');
const heroSpacer = document.getElementById('heroSpacer');

// Once, when the photo has loaded: sample the actual pixels behind
// the quote and pick plain dark or light ink -- like a designer
// eyeballing type color per photo, just automated. Deliberately a
// one-shot measurement (not re-run on scroll/resize) -- the quote
// only needs to be legible at rest, before anything's moved.
function adaptQuoteInk() {
    try {
        const vw = document.documentElement.clientWidth;
        const vh = window.innerHeight;
        const naturalW = heroImage.naturalWidth;
        const naturalH = heroImage.naturalHeight;
        if (!naturalW || !naturalH) return;

        // object-fit: cover math (default center/center position):
        // figure out what region of the source photo is actually
        // showing at the quote's on-screen position.
        const coverScale = Math.max(vw / naturalW, vh / naturalH);
        const displayedW = naturalW * coverScale, displayedH = naturalH * coverScale;
        const offsetX = (vw - displayedW) / 2;
        const offsetY = (vh - displayedH) / 2;

        const r = quote.getBoundingClientRect();
        const imgX = (r.left - offsetX) / coverScale;
        const imgY = (r.top - offsetY) / coverScale;
        const imgW = r.width / coverScale;
        const imgH = r.height / coverScale;

        const canvas = document.createElement('canvas');
        canvas.width = 24;
        canvas.height = 12; // small on purpose -- only need an average, not detail
        const ctx = canvas.getContext('2d');
        ctx.drawImage(heroImage, imgX, imgY, imgW, imgH, 0, 0, canvas.width, canvas.height);

        const { data } = ctx.getImageData(0, 0, canvas.width, canvas.height);
        let total = 0;
        for (let i = 0; i < data.length; i += 4) {
            total += 0.299 * data[i] + 0.587 * data[i + 1] + 0.114 * data[i + 2];
        }
        const avgLuminance = total / (data.length / 4); // 0-255

        const dark = avgLuminance < 150;
        quote.style.setProperty('--ink', dark ? '#fff' : '#211a12');
        quote.style.setProperty('--ink-shadow', dark ? 'rgba(0, 0, 0, 0.6)' : 'rgba(255, 255, 255, 0.65)');
    } catch (e) {
        // Pixel sampling can throw (e.g. a tainted canvas under some
        // file:// setups); keep the CSS default white ink instead
        // of breaking the page over a cosmetic nicety.
    }
}
if (heroImage.complete) adaptQuoteInk();
else heroImage.addEventListener('load', adaptQuoteInk, { once: true });

const wash = document.getElementById('wash');
const washTitleGroup = document.getElementById('washTitleGroup');
const outlineGroup = document.getElementById('outlineGroup');
const outlineLayer = document.getElementById('outlineLayer');
const titleWhite = document.getElementById('titleWhite');
const pageContent = document.getElementById('pageContent');
const titleCap = document.getElementById('titleCap');
const titleCapGlyphs = document.getElementById('titleCapGlyphs');
const washRect = wash.querySelector('.wash-rect');
const titleWindow = document.getElementById('titleWindow');
const titleWindowPhoto = document.getElementById('titleWindowPhoto');
const slimTopbar = document.getElementById('slimTopbar');
const heroNav = document.getElementById('heroNav');

// One-time renderer setup (see the flags above; they're consts by
// design -- changing them needs a reload, not a runtime toggle).
if (FAST_FILL) document.documentElement.classList.add('fast-fill');

if (TITLE_RENDERER === 'window') {
    // Solid wash: the hole is .title-window's job now. Dropping
    // the attribute outright (rather than CSS-overriding it)
    // leaves nothing for the browser to keep re-rasterizing.
    washRect.removeAttribute('mask');
    // The window's fill must be the hero photo itself -- same
    // src, resolved to the same URL, decoded once.
    titleWindowPhoto.src = heroImage.src;
    // Element-local glyph mask, generated from the live mask
    // paths -- one source of truth for the letterforms, the same
    // trick #outlineGroup and #titleCapGlyphs achieve via <use>
    // (which can't be used here: CSS mask-image needs a URL, not
    // a same-document node). White fills + the default luminance
    // mask keep this maximally compatible; the paths carry their
    // own per-glyph kerning transforms.
    const glyphPaths = Array.from(washTitleGroup.querySelectorAll('path'))
        .map(function (p) {
            const t = p.getAttribute('transform');
            return '<path fill="#fff" d="' + p.getAttribute('d') + '"' +
                (t ? ' transform="' + t + '"' : '') + '/>';
        })
        .join('');
    const glyphSVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' + VB_W + ' ' + VB_H + '">' + glyphPaths + '</svg>';
    const glyphMaskURL = 'url("data:image/svg+xml,' + encodeURIComponent(glyphSVG) + '")';
    titleWindow.style.maskImage = glyphMaskURL;
    titleWindow.style.webkitMaskImage = glyphMaskURL;
} else {
    // 'mask' mode: the window layer never paints.
    titleWindow.style.display = 'none';
}

if (!SHOW_TITLE_OUTLINE) {
    outlineLayer.style.display = 'none';
}

function layout() {
    const vw = document.documentElement.clientWidth;
    const vh = window.innerHeight;

    const renderedWidth = Math.min(vw - TITLE_SIDE_MARGIN * 2, TITLE_MAX_WIDTH);
    const scaledHeight = renderedWidth / ASPECT;
    const scale = renderedWidth / VB_W; // viewBox units -> px
    const xOffset = (vw - renderedWidth) / 2;
    const bottomMargin = vh * BOTTOM_MARGIN_FRACTION;

    const yBottom = vh - scaledHeight - bottomMargin; // resting position
    const yTop = 0; // pinned position

    // .hero-spacer reserves scroll room for the hero interaction --
    // NOT a full extra viewport on top of ACTIVATE_AT, though. A
    // full viewport of spacer was tried (reasoning: keep
    // .page-content, right after it, from being scrollable into
    // view before the hero phase is even reachable) and it
    // reintroduced the exact problem that got .page-content moved
    // to a fixed overlay in the first place: opacity already gates
    // when .page-content is *visible* (it's 0 until `activated`),
    // so that extra viewport bought nothing but a dead stretch of
    // scrolling with no content at all on screen.
    //
    // .hero-spacer reserves scroll room for the hero interaction.
    // Sized so that docking at LANDING_SCROLL_Y positions the
    // menu cleanly below the pinned title, eliminating the cavernous gap.
    heroSpacer.style.height = Math.round(scaledHeight + 70) + 'px';

    titleWhite.style.width = renderedWidth + 'px';

    // .title-window matches .title-white's box exactly; the photo
    // inside is viewport-sized, and object-fit: cover makes it
    // render identically to .hero-image at that size.
    titleWindow.style.width = renderedWidth + 'px';
    titleWindow.style.height = scaledHeight + 'px';
    titleWindowPhoto.style.width = vw + 'px';
    titleWindowPhoto.style.height = vh + 'px';
    outlineLayer.style.width = renderedWidth + 'px';
    outlineLayer.style.height = scaledHeight + 'px';

    // .title-cap (see its CSS/markup comments) reads this to clip
    // itself to exactly the title's own rendered height -- the one
    // remaining use of this variable now that .page-content is
    // normal document flow and doesn't need it for positioning.
    document.documentElement.style.setProperty('--title-height', scaledHeight + 'px');

    return { vw, vh, xOffset, scale, yBottom, yTop, scaledHeight };
}

if ('scrollRestoration' in history) {
    history.scrollRestoration = 'manual';
}

let metrics = layout();
let activated = window.scrollY > ACTIVATE_AT;

const ANIMATED_ELS = [titleWhite, titleWindow, titleWindowPhoto, wash, washTitleGroup, outlineGroup, heroImage, quote, pageContent, titleCap];

// The animation itself is a plain CSS transition (see the
// `transition` rules on .hero-image / .wash / .title-white /
// #washTitleGroup). Scrolling only flips this boolean between two
// end states -- it never scrubs an intermediate value -- which is
// what makes the motion glide instead of stepping with the wheel.
//
// instant=true suppresses that transition for this one update (used
// on first paint and on resize) -- otherwise the browser treats the
// very first style write as a change from the stylesheet's default
// position and animates in uninvited.
function applyState(instant) {
    if (instant) ANIMATED_ELS.forEach(el => { el.style.transitionDuration = '0s'; });

    let y = activated ? metrics.yTop : metrics.yBottom;
    if (activated && window.scrollY > LANDING_SCROLL_Y) {
        y = -(window.scrollY - LANDING_SCROLL_Y);
    }

    titleWhite.style.transform = 'translate(-50%, ' + y + 'px)';
    titleWhite.style.opacity = activated ? 0 : 1;

    // .title-window rides the exact same transform as
    // .title-white (the compositor handles both), while its photo
    // child counter-translates by the exact inverse so the fill
    // stays viewport-registered -- its scale mirrors the hero
    // image's activation zoom about the same (viewport) center,
    // so letter content and backdrop stay in lockstep there too.
    if (TITLE_RENDERER === 'window') {
        titleWindow.style.transform = 'translate(-50%, ' + y + 'px)';
        titleWindow.style.opacity = activated ? 1 : 0;
        titleWindowPhoto.style.transform =
            'translate(' + (-metrics.xOffset) + 'px, ' + (-y) + 'px) scale(' + (activated ? 1.06 : 1) + ')';
    }

    washTitleGroup.style.transform =
        'translate(' + metrics.xOffset + 'px, ' + y + 'px) scale(' + metrics.scale + ')';
    wash.style.opacity = activated ? 1 : 0;

    // Snap the outline layer to the target position instantly
    // (no transition on .title-outline) -- it's invisible during
    // the rise anyway (opacity 0 until the delay below), so
    // nothing is seen moving.
    if (SHOW_TITLE_OUTLINE) {
        outlineLayer.style.transform = 'translate(-50%, ' + y + 'px)';
        outlineGroup.style.transitionDelay = activated ? '2s' : '0s';
        outlineGroup.style.opacity = activated ? 1 : 0;
    } else {
        outlineGroup.style.opacity = 0;
    }

    // .title-cap's glyphs snap to the exact same transform as
    // #outlineGroup (same reasoning: no transition, since it's
    // one SVG group mirroring another frame-for-frame) so the two
    // stay in perfect registration if the cap is ever enabled.
    // The cap itself is gated on SOLID_TITLE_CAP (see the
    // tunables above): when off it never fades in, and the
    // pinned title stays the live mask window + outline instead
    // of going solid.
    titleCapGlyphs.style.transform = washTitleGroup.style.transform;
    titleCap.style.transitionDelay = activated ? '2s' : '0s';
    titleCap.style.opacity = (activated && SOLID_TITLE_CAP) ? 1 : 0;

    heroImage.style.transform = 'scale(' + (activated ? 1.06 : 1) + ')';

    // Fades out together with the title-white layer -- same
    // trigger, same timing -- so the whole "at rest" scene leaves
    // as one beat rather than as separate unrelated fades.
    quote.style.opacity = activated ? 0 : 1;

    // Short delay on the way in (see CONTENT_REVEAL_DELAY_MS),
    // none on the way out -- everything should vanish together
    // the instant you scroll back down. No pointer-events toggle
    // needed anymore: back in normal document flow (see the CSS
    // comment on .page-content), this no longer sits over the
    // whole viewport intercepting clicks/scroll while invisible --
    // it's just below-the-fold content like anything else.
    pageContent.style.transitionDelay = activated ? (CONTENT_REVEAL_DELAY_MS / 1000) + 's' : '0s';
    pageContent.style.opacity = activated ? 1 : 0;

    heroNav.classList.toggle('is-hidden', activated);

    const titleOffY = LANDING_SCROLL_Y + (metrics.scaledHeight || 95);
    if (activated && window.scrollY >= titleOffY) {
        slimTopbar.classList.add('is-visible');
    } else {
        slimTopbar.classList.remove('is-visible');
    }

    if (instant) {
        ANIMATED_ELS.forEach(el => void el.offsetHeight); // force layout before re-enabling
        ANIMATED_ELS.forEach(el => { el.style.transitionDuration = ''; });
    }
}

// Real-time scroll tracking for deep scrolling (scrollY > LANDING_SCROLL_Y):
// Scrolls the giant title off the top 1:1 like natural document content,
// and glides down the slim top bar once the giant title has cleared.
function updateScrollTracking() {
    if (!activated) return;
    const y = window.scrollY;
    const titleOffY = LANDING_SCROLL_Y + (metrics.scaledHeight || 95);

    if (y > LANDING_SCROLL_Y) {
        const scrollOff = y - LANDING_SCROLL_Y;
        const negScroll = -scrollOff;
        titleWindow.style.transitionDuration = '0s';
        titleWindow.style.transform = 'translate(-50%, ' + negScroll + 'px)';
        titleWindowPhoto.style.transitionDuration = '0s';
        titleWindowPhoto.style.transform =
            'translate(' + (-metrics.xOffset) + 'px, ' + scrollOff + 'px) scale(1.06)';
        washTitleGroup.style.transitionDuration = '0s';
        washTitleGroup.style.transform =
            'translate(' + metrics.xOffset + 'px, ' + negScroll + 'px) scale(' + metrics.scale + ')';
        titleWhite.style.transitionDuration = '0s';
        titleWhite.style.transform = 'translate(-50%, ' + negScroll + 'px)';
        if (SHOW_TITLE_OUTLINE) {
            outlineLayer.style.transform = 'translate(-50%, ' + negScroll + 'px)';
        }
        titleCapGlyphs.style.transform = washTitleGroup.style.transform;
    } else {
        titleWindow.style.transitionDuration = '';
        titleWindow.style.transform = 'translate(-50%, 0px)';
        titleWindowPhoto.style.transitionDuration = '';
        titleWindowPhoto.style.transform =
            'translate(' + (-metrics.xOffset) + 'px, 0px) scale(1.06)';
        washTitleGroup.style.transitionDuration = '';
        washTitleGroup.style.transform =
            'translate(' + metrics.xOffset + 'px, 0px) scale(' + metrics.scale + ')';
        titleWhite.style.transitionDuration = '';
        titleWhite.style.transform = 'translate(-50%, 0px)';
        if (SHOW_TITLE_OUTLINE) {
            outlineLayer.style.transform = 'translate(-50%, 0px)';
        }
        titleCapGlyphs.style.transform = washTitleGroup.style.transform;
    }

    if (y >= titleOffY) {
        slimTopbar.classList.add('is-visible');
    } else {
        slimTopbar.classList.remove('is-visible');
    }
}

// Tells the drop-cap script (a separate <script type="module">
// below) when to grow/shrink. It used to watch its own container
// with an IntersectionObserver; that broke for the few paragraphs
// .page-content used to hold back when it was a fixed overlay
// (always geometrically "in the viewport", opacity aside, so the
// observer fired immediately and the drop cap finished growing
// before it was ever visible). .page-content is normal document
// flow again now (see its CSS), so that signal would be
// meaningful again -- but this still dispatches the same explicit
// event tied to the hero's own early activation threshold rather
// than switching back, to keep one single trigger for everything
// that reveals with the hero. Same CONTENT_REVEAL_DELAY_MS delay
// as .page-content's own fade on the way in, none on the way out.
//
// Worth knowing: .hero-spacer is short (about one viewport) and
// ACTIVATE_AT triggers almost immediately, so on typical scrolling
// the drop cap will usually finish growing before .page-content
// has even scrolled into view -- the growth itself won't be
// visible, only the already-grown result. Switching this back to
// an IntersectionObserver on #dropcapContainer would make it grow
// exactly as it scrolls into view instead, if that's wanted.
let dropcapDispatchTimer = null;
function notifyDropcap() {
    clearTimeout(dropcapDispatchTimer);
    if (activated) {
        dropcapDispatchTimer = setTimeout(() => {
            window.dispatchEvent(new CustomEvent('hero-activated', { detail: { activated: true } }));
        }, CONTENT_REVEAL_DELAY_MS);
    } else {
        window.dispatchEvent(new CustomEvent('hero-activated', { detail: { activated: false } }));
    }
}

let isGated = false;
let gateTimer = null;
let exitTimer = null;

function triggerEntrance() {
    if (activated) return;
    activated = true;
    applyState();
    notifyDropcap();

    isGated = true;
    window.scrollTo(0, LANDING_SCROLL_Y);

    clearTimeout(exitTimer);
    clearTimeout(gateTimer);
    gateTimer = setTimeout(() => {
        isGated = false;
    }, GATE_DURATION_MS);
}

function triggerExit() {
    if (!activated) return;
    activated = false;
    window.setMobileMenuOpen(false);
    slimTopbar.classList.remove('is-visible');
    titleWindow.style.transitionDuration = '';
    titleWindowPhoto.style.transitionDuration = '';
    washTitleGroup.style.transitionDuration = '';
    titleWhite.style.transitionDuration = '';
    applyState();
    notifyDropcap();

    isGated = true;
    clearTimeout(gateTimer);
    clearTimeout(exitTimer);

    // Hold scroll position steady at landing while page-content fades out cleanly (500ms).
    // Once page-content is fully transparent (opacity: 0), reset scroll to 0 and ungate.
    // This guarantees the body text NEVER shifts down on reverse.
    exitTimer = setTimeout(() => {
        window.scrollTo(0, 0);
        requestAnimationFrame(() => {
            isGated = false;
        });
    }, 500);
}

function onScroll() {
    // While gated during initial entrance or exit, ignore intermediate scroll events
    if (isGated) return;

    const y = window.scrollY;
    const next = activated ? y > DEACTIVATE_AT : y > ACTIVATE_AT;
    if (next !== activated) {
        if (!next) {
            triggerExit();
        } else {
            activated = next;
            applyState();
            notifyDropcap();
        }
    } else {
        updateScrollTracking();
    }
}

function onResize() {
    metrics = layout();
    applyState(true);
    if (window.innerWidth > 768) {
        window.setMobileMenuOpen(false);
    }
}

// Intercept wheel events: gate first entrance and symmetrical exit to prevent text shift
window.addEventListener('wheel', (e) => {
    if (!activated && window.scrollY <= ACTIVATE_AT && e.deltaY > 0) {
        // First scroll down from rest triggers entrance
        e.preventDefault();
        triggerEntrance();
    } else if (activated && window.scrollY <= LANDING_SCROLL_Y + 20 && e.deltaY < 0) {
        // Scrolling back up from landing triggers exit without shifting body text
        e.preventDefault();
        triggerExit();
    } else if (isGated) {
        // Absorb wheel impulses while entrance or exit animation is in progress
        e.preventDefault();
        if (!activated && e.deltaY > 0) {
            // Reversing direction back down during exit
            triggerEntrance();
        } else if (activated && e.deltaY < 0) {
            // Reversing direction back up during entrance
            triggerExit();
        }
    }
}, { passive: false });

// Touch support for mobile devices: gate entrance on first swipe-up and exit on swipe-down
let touchStartY = 0;

window.addEventListener('touchstart', (e) => {
    if (e.touches.length === 1) {
        touchStartY = e.touches[0].clientY;
    }
}, { passive: true });

window.addEventListener('touchmove', (e) => {
    if (e.touches.length !== 1) return;
    const currentY = e.touches[0].clientY;
    const diffY = touchStartY - currentY; // positive = swipe up = scroll down

    if (!activated && window.scrollY <= ACTIVATE_AT && diffY > 12) {
        if (e.cancelable) e.preventDefault();
        triggerEntrance();
    } else if (activated && window.scrollY <= LANDING_SCROLL_Y + 20 && diffY < -12) {
        if (e.cancelable) e.preventDefault();
        triggerExit();
    } else if (isGated) {
        if (e.cancelable) e.preventDefault();
        if (!activated && diffY > 12) {
            triggerEntrance();
        } else if (activated && diffY < -12) {
            triggerExit();
        }
    }
}, { passive: false });

// Smoothly grow drop cap letter when hero activates
window.addEventListener('hero-activated', (e) => {
    const container = document.getElementById('dropcapContainer');
    if (container) {
        if (e.detail.activated) {
            container.classList.add('dropcap-grown');
        } else {
            container.classList.remove('dropcap-grown');
        }
    }
});

window.addEventListener('scroll', onScroll, { passive: true });
window.addEventListener('resize', onResize);
applyState(true);
if (activated) {
    const container = document.getElementById('dropcapContainer');
    if (container) container.classList.add('dropcap-grown');
}
