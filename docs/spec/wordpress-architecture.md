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

Enqueue logic is in `regen_wp_enqueue_scripts()` (`functions.php`): `site.*` everywhere; `page.css` on everything except the homepage; the per-template files by slug; `cards.css` on the homepage and `/projects/`; `support.*` on the homepage and `/support/`; `home.*` on the homepage.

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

---

## 4. Contact Form 7

Forms are rendered with `regen_wp_cf7( $title, $class )`, which passes `html_class` so the `<form>` gets the layout class (`join-form`, `apply-form`, `contact-form`).

| Form (title) | ID* | Used on | Mail |
| :--- | :--- | :--- | :--- |
| Main Contact Form | 66 | About (via shortcode in content), Support | subject, reply-to, message |
| Residency Application Form | 77 | Residents | all fields + CV attachment (`[documents]`) |
| Students | 83 | Students | all fields |

*IDs are from the current database. Recipient is `[_site_admin_email]`; sender `wordpress@regeneracionlab.org` (must match the site domain for SPF).
Mail templates must reference fields that exist in the form template — the originals for forms 77/83 referenced `[your-name]` etc. and delivered blank messages.

**Testing mail locally:** Local routes PHP mail to Mailpit (site's own ports are in `~/.config/Local/sites.json`). Submit through the REST endpoint or the page, then read `http://localhost:<mailpit web port>/api/v1/messages`.

---

## 5. Custom post types & meta

* **project** — `project_badge`, `project_meta`, `project_link_label`, `project_link_url`, `project_style` (turquoise/brown/amber), `project_title_line1/2`. Excerpt = card body.
* **resident** — `resident_title`, `resident_dates`, `resident_bio`, `resident_links` (array of label/url), `resident_is_past`, `resident_order`. Post content = "Residency Focus".
* **collaboration** — `collaboration_link_label`, `collaboration_link_url`.
* **person** — `person_role`, `person_years`, `person_link_label`, `person_link_url`, `person_order`. Rendered by the Person Card block / `[person_card id=…]` via `regen_wp_person_card_html()`.
* **program** — `program_badge`, `program_badge_style` (active|winter), `program_partner`, `program_schedule`, `program_facilitator`, `program_level`, `program_topics_title`, `program_topics` (one per line), `program_cta`, `program_facilitator_note`. Not public; listed only on the Students page.
* **Pages** — `regen_eyebrow`, `regen_intro_current`, `regen_intro_past`, `regen_intro_programs`; Support: `support_section{1,2,3}_{heading,body}`, `support_contact_heading`.
* **Posts** — `update_links` (array of label/url).

---

## 6. Block patterns (category "Regeneracion")

Timeline, Resource Header, Resource List, Focus Grid (3 columns), Three-Column Notes, Numbered Commitments, Pull Quote. All are plain core blocks with a CSS class, so every word stays editable. Their CSS is in `assets/page.css`.

---

## 7. Development & deployment

* **Local site:** `Regeneración Lab newermaybe` (`~/Local Sites/regeneracin-lab-newermaybe`, `https://regeneracin-lab-newermaybe.local`) holds a pull of production. Its `wp-content/themes/regen_wp` is a **symlink to this repo's `regen_wp/`**, so edits apply immediately. Use the site's Local "Trust" button for the SSL certificate.
* **Branch flow:** work on a branch, verify with the Playwright CLI (`./.claude/skills/playwright/scripts/playwright_cli.sh`, config with `ignoreHTTPSErrors` for the local cert), merge to `main`.
* **DB backups:** `mysqldump` is not on PATH under Local. Use the site's socket: `~/.config/Local/lightning-services/mysql-*/bin/linux/bin/mysqldump --socket=<site run dir>/mysql/mysqld.sock -uroot -proot local`.
* **Going live (Namecheap):** push the theme, and/or upload a Migrate DB Pro package. **Pull production into Local immediately before creating the package** so recent production edits are not overwritten. The package must include the Media Library (hero photos are attachments).
* **Content that must exist in the database** (created by hand or by the setup scripts in the redesign branch history): Pages `projects` and `residents`; two Programs; restyled CF7 forms + mail templates; hero-photo option `regen_hero_photos`.
