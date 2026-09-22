# WordPress Architecture & Content Model Specification

The live WordPress theme is `regen_wp/` for [Regeneración Lab](https://regeneracionlab.org). Classic PHP theme, no build step. The design is the one prototyped in `html-js-site/design-revamp/mask-experiment/`; the theme and the prototype **share the same CSS/JS files** in `regen_wp/assets/` (there is exactly one copy of each).

---

## 1. Structure

```
regen_wp/
  style.css              Theme header + WordPress-only rules (admin bar, block-editor output, CF7 messages)
  functions.php          Enqueues, walker, CPTs, meta boxes, Customizer, block/shortcode, patterns
  inc/                   helpers, page-fields, programs (CPT), patterns, hero-photos (admin page)
  header.php footer.php  Slim top bar + mobile drawer; footer. Homepage prints its own <main>
  front-page.php         Homepage (hero + content sections)
  page.php               Generic page: masthead + editable content (About, and any new page)
  page-{students,residents,projects,support}.php   Templates picked by page slug
  single-project.php single-resident.php single.php index.php 404.php
  template-parts/        masthead, project-card, resident-profile, site-footer,
                         support-modal, home-hero
  assets/
    site.css site.js     Chrome on EVERY page: tokens, top bar, drawer, footer, smooth anchors
    page.css             Content pages: masthead, sections, prose, forms, patterns, timeline
    cards.css            Project / collaboration / update cards (homepage + Projects page)
    support.css/.js      Contribute button + donation-reminder modal (homepage + Support page)
    home.css home.js     Homepage only: pinned title mask, scroll behaviour
    pages/               Per-template CSS (residents.css, students.css)
    admin/               Admin-only JS (hero-photos.js)
  images/                Bundled fallback hero photos
  blocks/person-card/    Editor script for the Person Card block
```

**Rule:** styles and scripts live in `assets/` once. Prototype pages link to `../../../regen_wp/assets/…`; do not copy them.

Enqueue logic is in `regen_wp_enqueue_scripts()` (`functions.php`): `site.*` everywhere; `page.css` on everything except the homepage; the per-template files by slug; `cards.css` on the homepage and `/projects/`; `support.*` on the homepage and `/support/`; `home.*` on the homepage. `regen_wp_font_preconnect()` upgrades the Google Fonts host hints from `dns-prefetch` to `preconnect` (the font stylesheet is the only cross-origin, render-blocking request on the page).

---

## 2. What is edited where (everything visible is editable in WP)

| Thing | Where to edit |
| :--- | :--- |
| Menu | Appearance > Menus ("Primary Menu"). Current section is highlighted automatically. |
| Homepage intro copy | Page "Home" body (first letter becomes the growing drop cap) |
| Homepage hero quote + attribution | Appearance > Customize > Regeneracion Theme Options |
| Homepage hero photos (random per load) | Appearance > **Hero Photos** (Media Library picker). Empty = bundled fallback photos |
| Support heading/text/note/button/URL/modal copy | Customize > Regeneracion Theme Options |
| Page headline, tagline, eyebrow | Page title, **Excerpt** (= tagline), "Page Header & Sections" box (eyebrow) |
| About page | Page "About" body. Director block = a *Person Card* block (People post type); "Contact Us" form = a Contact Form 7 shortcode |
| Projects | Projects post type. Card = title/excerpt + meta box "Project Display"; page body = optional intro. Order = "Order" attribute |
| Residents | Residents post type (title, dates, bio, links, "past resident" checkbox, featured photo). Page "Scholars & Artists in Residence" body = application text; section intros = "Page Header & Sections" box |
| Students reading groups & labs | **Programs** post type (title, description, meta box "Program Details", order). Page body = "Study & Research Opportunities" section |
| Collaborations | Collaborations post type (title, excerpt, "Collaboration Link" box) |
| Recent Updates | Posts (latest 3). "Update Links" box adds custom CTA links |
| Support page sections | Page "Support" + "Support Sections" box; empty = defaults |
| Forms | Contact > Contact Forms (see §4) |

---

## 3. Routing

`/projects/` and `/residents/` are **Pages** (slugs `projects`, `residents`) rendered by `page-projects.php` / `page-residents.php`. The `project` and `resident` post types keep `has_archive = false` and only provide single URLs (`/projects/{slug}/`, `/residents/{slug}/`). Changing routing needs a permalink flush (`regen_rewrite_version` option triggers one).

Templates are chosen by WordPress from the page slug (`page-students.php` etc.); no "Template Name" selection is needed. Creating a new Page with any other slug uses `page.php`.

**Primary menu items must link to the actual Page/Post objects** (add via "Pages" in the menu editor, not a "Custom Link" with a hand-typed URL). A custom link without a trailing slash (e.g. `/projects` instead of `/projects/`) makes WordPress 301-redirect on every click — a real, avoidable extra round trip on the site's most-used links. All four section links (Projects/Residents/Students/Support) were custom links missing the slash at one point; they're now Page links. If a menu item ever shows type `custom` for one of these, that's the bug to look for.

---

## 4. Contact Form 7

Forms are rendered with `regen_wp_cf7( $title, $class )`, which passes `html_class` so the `<form>` gets the layout class (`join-form`, `apply-form`, `contact-form`).

| Form (title) | ID* | Used on | Mail |
| :--- | :--- | :--- | :--- |
| Main Contact Form | 66 | About (via shortcode in content), Support | subject, reply-to, message |
| Residency Application Form | 77 | Residents | all fields + CV attachment (`[documents]`) |
| Students | 83 | Students | all fields |

*IDs are from the current database. Recipient is `[_site_admin_email]` (currently `graysonearle@gmail.com` — change the site's admin email, not the forms, to redirect all three); sender `wordpress@regeneracionlab.org` (must match the site domain for SPF).

**Mail templates must reference fields that exist in the form template.** The production forms for Residency Application (77) and Students (83) were found delivering **blank** messages — their Mail tab referenced `[your-name]`/`[your-email]`/`[your-message]`, tags belonging to a different, unrelated form, so every merge tag resolved to nothing. Applications submitted through those two forms on production before this was found may have arrived empty; worth checking for silently-lost submissions. All three forms' Mail tabs now reference the correct field names, include a proper subject and Reply-To, and (form 77) attach the uploaded CV via `[documents]`. This fix lives in the database (this local site's `wpjp_posts`/`wpjp_postmeta` for those three `wpcf7_contact_form` posts) — it ships with whatever gets deployed, no manual step needed. It has been verified with real submissions through Mailpit, including a browser-driven submission with a file attached.

**Testing mail locally:** Local routes PHP mail to Mailpit (site's own ports are in `~/.config/Local/sites.json`, key `mailpit.ports.WEB`). Submit through the REST endpoint (`POST /wp-json/contact-form-7/v1/contact-forms/{id}/feedback`) or the page, then read `http://localhost:<mailpit web port>/api/v1/messages`.

---

## 5. Custom post types & meta

* **project** — `project_badge`, `project_meta`, `project_link_label`, `project_link_url`, `project_style` (turquoise/brown/amber), `project_title_line1/2`. Excerpt = card body.
* **resident** — `resident_title`, `resident_dates`, `resident_bio`, `resident_links` (array of label/url), `resident_is_past`, `resident_order`. Post content = "Residency Focus".
* **collaboration** — `collaboration_link_label`, `collaboration_link_url`.
* **person** — `person_role`, `person_years`, `person_link_label`, `person_link_url`, `person_order`. Rendered by the Person Card block / `[person_card id=…]` via `regen_wp_person_card_html()` (the "director profile" layout — photo, name, role, bio, contact link).
* **program** — `program_badge`, `program_badge_style` (active|winter), `program_partner`, `program_schedule`, `program_facilitator`, `program_level`, `program_topics_title`, `program_topics` (one per line), `program_cta`, `program_facilitator_note`. Not public; listed only on the Students page.
* **Pages** — `regen_eyebrow`, `regen_intro_current`, `regen_intro_past`, `regen_intro_programs`; Support: `support_section{1,2,3}_{heading,body}`, `support_contact_heading`.
* **Posts** — `update_links` (array of label/url).

---

## 6. Block patterns (category "Regeneracion")

Timeline, Resource Header, Resource List, Focus Grid (3 columns), Three-Column Notes, Numbered Commitments, Pull Quote. All are plain core blocks with a CSS class, so every word stays editable. Their CSS is in `assets/page.css`.

---

## 7. Accessibility

The theme targets zero [axe-core](https://github.com/dequelabs/axe-core) violations at the `wcag2a`/`wcag2aa`/`wcag21a`/`wcag21aa`/`wcag22aa`/`best-practice` tag set, verified across every template (homepage, About, Projects listing + single, Residents listing + single, Students, Support, a Post, and a 404). Re-run this after any structural template change:

1. Fetch `axe.min.js` (e.g. from cdnjs) to a local file.
2. Drive it with the Playwright CLI's `run-code` (full Playwright API — `eval`'s sandboxed evaluation isn't enough here): `page.addScriptTag({ path: '<axe.min.js>' })`, then `axe.run(document, { runOnly: { type: 'tag', values: [...] } })` per URL, collecting `violations`.

Two structural rules worth knowing if you add new templates or content types:
* **Heading order must not skip a level.** The single-resident template is the tricky one: the masthead already renders the resident's name as `<h1>`, so `template-parts/resident-profile.php` checks `is_singular('resident')` and, in that context, omits the redundant name/eyebrow it normally renders (used on the Residents listing page) and promotes its "Residency Focus" heading from `<h4>` to `<h2>` so the sequence stays h1→h2 instead of skipping to h3/h4. `page-projects.php` has a visually-hidden `<h2>` before the project grid for the same reason (h1 "Projects" → h3 card titles would otherwise skip a level).
* **Landmarks:** every nav needs a distinct accessible name if more than one is present on a page (the homepage has three: the slim top bar's, the hero's, and the in-content one — see `aria-label`s in `header.php` / `template-parts/home-hero.php` / `front-page.php`). All text content should sit inside a landmark; the homepage's `<h1>` (screen-reader-only — the real "h1" is an SVG title mask, not text) lives inside `<header>`, and `.hero` itself carries `role="region" aria-label="Hero"` so the quote isn't orphaned. Its attribution is a plain `<p class="quote-attribution">`, not `<footer>` — a `<footer>` nested inside another landmark trips `landmark-contentinfo-is-top-level` even though it's a legitimate blockquote-citation use.

---

## 8. Development & deployment

* **Local site:** `Regeneración Lab newermaybe` (`~/Local Sites/regeneracin-lab-newermaybe`, `https://regeneracin-lab-newermaybe.local`) holds a pull of production (nobody edits production directly, so this doesn't go stale on its own). Its `wp-content/themes/regen_wp` is a **symlink to this repo's `regen_wp/`**, so edits apply immediately. Use the site's Local "Trust" button for the SSL certificate; for CLI tools (curl, Playwright) that don't have that button, use `-k` / an `ignoreHTTPSErrors` context config instead.
* **Branch flow:** work on a branch, verify with the Playwright CLI (`./.claude/skills/playwright/scripts/playwright_cli.sh`), merge to `main`.
* **DB backups:** `mysqldump` is not on PATH under Local. Use the site's socket: `~/.config/Local/lightning-services/mysql-*/bin/linux/bin/mysqldump --socket=<site run dir>/mysql/mysqld.sock -uroot -proot local`.
* **Going live (Namecheap):** push the theme, and/or upload a Migrate DB Pro package (make sure its Media Library option is on — the hero photos are attachments, not just DB rows). If production ever starts being edited directly again, pull it into Local again before building the next package.
* **Content that must exist in the database** (created by hand or by the setup scripts in the redesign branch history): Pages `projects` and `residents`, linked from the Primary menu as Page links (not custom URLs — see §3); two Programs; restyled CF7 forms + mail templates (see §4); hero-photo option `regen_hero_photos` (see §2); corrected `person_role` on the director's Person entry ("Principal", not "Principle").
