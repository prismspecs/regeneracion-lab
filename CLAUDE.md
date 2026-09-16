# Regeneración Lab - Project Context

## Overview
**Regeneración Lab** is a digital humanities and Indigenous studies initiative. The live site (https://regeneracionlab.org) runs on the WordPress theme in `regen_wp/`. It grew out of an earlier set of interconnected static prototypes, now archived in `html-js-site/`:
1.  **Main Research Lab Website (`html-js-site/main-site/`)**: The original static prototype for the lab's public face — superseded by `regen_wp/`.
2.  **Indigenous Border Studies Syllabus (`html-js-site/syllabus/`)**: An educational platform prototype with thematic modules — not yet ported to WordPress.
3.  **Tribal Community Historical Site**: Planned; not started.

**Core Theme:** Water justice, Indigenous studies, and "Regeneración" (referencing the Mexican anarchist newspaper).

## Design Principles & Aesthetic
*   **Typography:** Google Material Design 3 guidelines.
*   **Layout:** Apple's layout best practices for web views.
*   **Accessibility:** WCAG standards (e.g., 4.5:1 contrast).
*   **Aesthetic:** Turn-of-the-century anarchist newspaper, nature-evoking colors (river imagery), clean/minimal but with experimental navigation.
*   **Visual Inspiration:** "Motates" (cooking holes) photo, Mexican anarchist newspaper *Regeneración* (1900-1918).
*   **UI Style:** Sharp, brutalist/modernist aesthetic (no rounded corners), soft shadows, modern typography (Inter/Roboto).

## Architecture & Tech Stack (Critical)
**Live site (`regen_wp/`):** WordPress theme, PHP templates, no build step. Standard WP hooks/CPTs (see "WordPress Migration" section below for the content model).

**Archived prototype (`html-js-site/`):** Vanilla HTML, CSS, and JavaScript. **NO frameworks** (React, Vue, etc.). Described below for historical reference only — this is not what's running in production.

### Main Site Architecture (`html-js-site/main-site/`)
*   **HTML-First SPA:** `index.html` is the shell. `app.js` handles hash-based routing and injects content into `#mainContent`.
*   **Content Strategy:**
    *   **All content lives in `pages/*.html`.**
    *   **NEVER** put content in JavaScript strings.
    *   This ensures SEO, easy editing, and future WordPress migration.
*   **Partials:** Reusable sections (e.g., `partials/collaborations.html`) are injected via `data-partial` attributes.
*   **Styling:** `styles.css` (global) and `spa.css` (layout/components) using CSS variables.

### Syllabus Platform (`html-js-site/syllabus/`) — archived prototype, not yet in WordPress
*   **Layout:** Thematic columnar layout with filtering.
*   **Detail Panel:** Side panel for theme details, visual placeholders, and related items.
*   **Features:** Filtering (Articles, Books, etc.), definition flashcards, timelines.

## Directory Structure
*   `regen_wp/`: **The live WordPress theme.** This is what's actually running in production (https://regeneracionlab.org). All active development happens here.
*   `html-js-site/`: **Archived.** The pre-WordPress static HTML/JS prototypes this theme evolved from. Not deployed anywhere; kept for design-history reference.
    *   `main-site/`: The SPA prototype described in the workflow notes below.
    *   `syllabus/`: Syllabus platform prototype (v2). `archive/` holds older versions (v1-v5) including an earlier syllabus iteration.
    *   `design-revamp/`, `merged-site/`, `new-main-site/`, `examples/`: Other design exploration snapshots.
*   `reference-sites/`: Screenshots of design inspiration sites.
*   `PRODUCT.md`, `.impeccable.md`: Product/design briefs.
*   `.claude/skills/`: Agent skills for Claude Code (see "Agent Skills" below). `skills-lock.json` + `.cursor/skills/`, `.gemini/skills/`, `.agents/skills/` are a separate, gitignored install of the `impeccable` design skill (managed by `npx impeccable`) — unrelated to `.claude/skills/`.

## Development Workflow (current — WordPress)
*   Local environment: **Local** (by WP Engine), site `regeneracion-lab`, https://regeneracion-lab.local, table prefix `wpjp_`. This repo is the `wp-content/themes/` folder inside that site's `app/public/`.
*   Edit theme templates/PHP/CSS/JS directly in `regen_wp/`.
*   Production pulls/pushes use the WP Migrate DB Pro plugin (already installed in both environments).
*   Commit and push theme changes to `origin/main` as normal.
*   **WP-CLI:** available as `wp` on PATH (`~/.local/bin/wp`, wrapping `~/.local/bin/wp-cli.phar`). Local (by WP Engine) doesn't expose its bundled PHP/MySQL on the system PATH, so this is a wrapper script, not a system install — it looks up the current Local site in `~/.config/Local/sites.json` from `$PWD`, then runs `wp-cli.phar` with that site's bundled PHP binary/`php.ini` (which already points at the right MySQL socket). Run `wp` from anywhere under this site's `app/public/` (e.g. from this repo). Machine-local setup, not part of the repo — re-run on a fresh machine by re-downloading `wp-cli.phar` and recreating the wrapper (see chat history for the script, or ask an agent to redo it).

## Agent Skills
`.claude/skills/` (tracked in git — these are plain files, not something a separate installer regenerates, unlike the `impeccable` skill):
*   `wordpress-router`, `wp-project-triage` — classify/triage a WordPress repo and route to the right workflow.
*   `wp-wpcli-and-ops` — WP-CLI usage (search-replace, db export/import, plugin/theme/content management).
*   `wp-performance` — backend profiling/caching/DB/query optimization; useful given cPanel/LiteSpeed shared hosting in production.
*   `wp-phpstan` — PHPStan static analysis setup for WordPress PHP (not yet wired into this repo — no `phpstan.neon` or Composer install here yet).
*   `wp-patterns` — registering WordPress block patterns (this repo's Timeline / Resource Header / Resource List patterns).
*   `wq-accessibility`, `wq-seo`, `wq-performance` (addyosmani/web-quality-skills) — WCAG/screen-reader, SEO/structured-data, and Core Web Vitals guidance; match the WCAG AA requirement and editorial/discoverability goals in `PRODUCT.md`.
*   `wq-best-practices` (addyosmani/web-quality-skills) — security headers/CSP/HTTPS, browser-compat, and code-quality checks (Lighthouse best-practices pillar).
*   `theme-factory` (anthropics) — color/font theme presets for artifacts (slides, docs, HTML mockups); useful for quick design exploration before porting into `regen_wp/`.
*   `frontend-skill` (openai) — composition/hierarchy/imagery guidance for visually strong front-end work. Note: OpenAI deleted this skill from their upstream repo in April 2026; this copy is a last-available snapshot and won't get updates.
*   `playwright` (openai) — CLI-first real-browser automation via `playwright-cli` (wrapper script self-fetches with `npx`; no global install). Use for visual verification of the theme at https://regeneracion-lab.local — screenshots, responsive layout checks, contrast spot-checks.
*   Not installed: `anthropics/frontend-design` — identical to the `frontend-design` skill Claude Code already ships built-in, so a project copy would just collide with it.
*   Not installed from `WordPress/agent-skills`: `wp-block-development`, `wp-block-themes`, `wp-rest-api`, `wp-interactivity-api`, `wp-abilities-api`, `wpds`, `wp-playground`, `wp-plugin-development` — this repo is a classic (non-FSE) theme with no custom blocks, REST endpoints, or plugin code, so these don't apply yet. Revisit if that changes.

## Development Workflow (archived — static prototype in `html-js-site/main-site/`)
Kept for reference only; not part of the live build.
1.  **Local Server:** Required due to `fetch()` usage.
    ```bash
    cd html-js-site/main-site
    python3 -m http.server 8000
    ```
2.  **Editing Content:** Modify files in `pages/`.
3.  **Editing Logic:** Modify `app.js`.
4.  **Adding Pages:** Create `pages/new-page.html` -> Add link in `index.html` -> Update `app.js` if custom routing needed.

## Notes
*   **Experimental Features (archived prototype):** "Melt" WebGL effect on homepage (`melt-effect.js`), never ported to WordPress.

## WordPress Migration (status: live)
*   **Goal:** Replace SPA routing with native WP templates and content types while preserving the existing aesthetic.
*   **Theme setup:** Register nav menu(s), enqueue only needed assets, use `front-page.php` for the landing page, and avoid hash-based navigation (`data-page` links go away). Title tag, thumbnails, primary menu, and customizer options (hero/support CTA) are enabled; SPA script is off by default.
*   **Landing page content sources:**
    1. **Site identity:** `bloginfo()` (already used) + optional custom logo.
    2. **Navigation:** `wp_nav_menu()` pulling a "Primary" menu configured in the Dashboard.
    3. **Hero:** Featured image (or customizer image) on the Home page.
    4. **Intro copy:** Home page content (the_content()).
    5. **Projects grid:** Custom Post Type `project` with meta: `project_badge` (shows only when "Ongoing"), `project_meta` (e.g., Ongoing/2025-2026), `project_link_label` (CTA label), `project_link_url` (optional external; opens in new tab), `project_style` (turquoise/brown/amber), and optional title overrides (`project_title_line1`/`project_title_line2` for manual breaks). Excerpt populates the card body.
    6. **Collaborations block:** CPT `collaboration` rendered via template part; supports `collaboration_link_label`/`collaboration_link_url` (external opens in new tab).
    7. **Recent updates:** Standard Posts in an "Updates" category (loop limited on the front page).
    8. **Support CTA:** Button URL/text from theme options (Customizer); hero quote/attribution/image also via Customizer.
*   **SPA note:** Hash-based SPA routing (app.js) is disabled; template-driven rendering is now the default. Keep app.js only if future hash navigation is required.
*   **Templates in theme:** `front-page.php`, `archive-project.php` (Projects grid), `single-project.php` (neutral header color), generic `single.php`, fallback `index.php`. Project badges are Ongoing-only; project card styles are chosen via `project_style` meta.
*   **Editor UX:**
    - Project metabox “Project Display” sits in main column: badge, year/status, button label, optional link URL (external allowed), card style, title line 1/2 overrides.
    - Collaboration metabox “Collaboration Link” in main column: button label + link URL (external allowed).
*   **Block patterns:**
    - **Timeline** pattern (`timeline` classes) for project timelines.
    - **Resource Header** (H4 with `resource-header` class) and **Resource List** (list with `resource-list` class) to recreate bibliography/resource sections without manual classes.
*   **Next templates:** Optionally add `page.php` to mirror general typography/layout.
*   **Data migration:** Move HTML snippets from `regen_wp/pages/*.html` into WP content (Pages, CPTs, Posts) and re-map image URLs to the media library where possible.
*   **Email Delivery:** Configure an SMTP plugin (e.g., WP Mail SMTP) to ensure reliable email delivery for Contact Form 7 (prevents spam flags).
