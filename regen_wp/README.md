# Regeneración Lab WordPress Theme

Classic PHP theme (no build step) for regeneracionlab.org. Full specification: [`docs/spec/wordpress-architecture.md`](../docs/spec/wordpress-architecture.md).

## Layout

- `assets/` — the only copy of the design CSS/JS. `site.*` loads on every page, `page.css` on content pages, `home.*` on the homepage, plus `cards.css`, `support.*` and `pages/*.css`. The static prototype in `html-js-site/design-revamp/mask-experiment/` links to these same files.
- `inc/` — helpers, page fields, Programs post type, block patterns, Hero Photos admin page.
- `template-parts/` — masthead, cards, resident profile, footer, donation modal, homepage hero.
- Templates: `front-page.php`, `page.php`, `page-{students,residents,projects,support}.php`, `single-*.php`, `index.php`, `404.php`.

## Setup

1. Activate the theme; assign a menu to **Primary Menu**; set Settings > Reading to a static front page ("Home").
2. Permalinks: "Post name".
3. Create Pages with slugs `projects` and `residents` (they use `page-projects.php` / `page-residents.php`).
4. Plugins: Contact Form 7 (forms: Main Contact Form, Residency Application Form, Students).
5. Homepage photos: Appearance > Hero Photos. Quote/support copy: Appearance > Customize.

Nothing visible on the site is hard-coded: see the "what is edited where" table in the spec.
