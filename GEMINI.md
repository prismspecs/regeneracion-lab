# Regeneración Lab - Project Context

## Overview
**Regeneración Lab** is a digital humanities and Indigenous studies initiative involving three interconnected web projects:
1.  **Main Research Lab Website (`main-site/`)**: The primary public face of the lab.
2.  **Indigenous Border Studies Syllabus (`syllabus/`)**: An educational platform with thematic modules.
3.  **Tribal Community Historical Site**: (Planned/In-progress).

**Core Theme:** Water justice, Indigenous studies, and "Regeneración" (referencing the Mexican anarchist newspaper).

## Design Principles & Aesthetic
*   **Typography:** Google Material Design 3 guidelines.
*   **Layout:** Apple's layout best practices for web views.
*   **Accessibility:** WCAG standards (e.g., 4.5:1 contrast).
*   **Aesthetic:** Turn-of-the-century anarchist newspaper, nature-evoking colors (river imagery), clean/minimal but with experimental navigation.
*   **Visual Inspiration:** "Motates" (cooking holes) photo, Mexican anarchist newspaper *Regeneración* (1900-1918).
*   **UI Style:** Sharp, brutalist/modernist aesthetic (no rounded corners), soft shadows, modern typography (Inter/Roboto).

## Architecture & Tech Stack (Critical)
**Stack:** Vanilla HTML, CSS, and JavaScript. **NO frameworks** (React, Vue, etc.).

### Main Site Architecture (`main-site/`)
*   **HTML-First SPA:** `index.html` is the shell. `app.js` handles hash-based routing and injects content into `#mainContent`.
*   **Content Strategy:**
    *   **All content lives in `pages/*.html`.**
    *   **NEVER** put content in JavaScript strings.
    *   This ensures SEO, easy editing, and future WordPress migration.
*   **Partials:** Reusable sections (e.g., `partials/collaborations.html`) are injected via `data-partial` attributes.
*   **Styling:** `styles.css` (global) and `spa.css` (layout/components) using CSS variables.

### Syllabus Platform (`syllabus/`)
*   **Layout:** Thematic columnar layout with filtering.
*   **Detail Panel:** Side panel for theme details, visual placeholders, and related items.
*   **Features:** Filtering (Articles, Books, etc.), definition flashcards, timelines.

## Directory Structure
*   `main-site/`: **Active Development**. Main website.
    *   `pages/`: Content fragments (HTML).
    *   `partials/`: Reusable content blocks.
    *   `images/`: Local assets.
*   `syllabus/`: Active Syllabus platform (v2).
*   `syllabus-old/`: Archived syllabus platform (v1).
*   `ingest/`: Raw image assets.
*   `temporary-landing/`: Placeholder landing page.
*   `archive/`: Old versions (v1-v5).

## Development Workflow
1.  **Local Server:** Required due to `fetch()` usage.
    ```bash
    cd main-site
    python3 -m http.server 8000
    ```
2.  **Editing Content:** Modify files in `pages/`.
3.  **Editing Logic:** Modify `app.js`.
4.  **Adding Pages:** Create `pages/new-page.html` -> Add link in `index.html` -> Update `app.js` if custom routing needed.

## Future Goals & Notes
*   **WordPress Migration:** Strict separation of HTML/JS facilitates this.
*   **Experimental Features:** "Melt" WebGL effect on homepage (`melt-effect.js`).
*   **Images:** Use assets from `ingest/` or `main-site/images/`.

## WordPress Migration (active)
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
