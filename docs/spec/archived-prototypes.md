# Archived Static Prototypes Specification

This document preserves the architecture and development notes for the pre-WordPress static prototypes stored in `html-js-site/`. These files are archived for design-history and experimental reference and are not deployed to production.

---

## 1. Prototype Directory Inventory

* `html-js-site/main-site/`: Original static single-page application (SPA) prototype for the lab's public website (superseded by `regen_wp/`).
* `html-js-site/syllabus/`: Indigenous Border Studies Syllabus platform prototype (v2), featuring modular thematic columns and research material filters.
* `html-js-site/archive/`: Legacy syllabus and lab iterations (v1 through v5).
* `html-js-site/design-revamp/`: Interactive animation and layout explorations (including `mask-experiment/` with scroll-pinned SVG text masks and sticky hairline navigation).
* `html-js-site/merged-site/`, `new-main-site/`, `examples/`: Additional design snapshot experiments.

---

## 2. Main Site SPA Architecture (`html-js-site/main-site/`)

* **Tech Stack:** Vanilla HTML5, CSS3, JavaScript (ES6+). Zero third-party frameworks (no React, Vue, or build pipeline).
* **Routing Architecture:**
  * `index.html` acts as the primary application shell.
  * `app.js` listens to `hashchange` events and dynamically fetches and injects page content into `#mainContent`.
* **Content Separation Principle:**
  * All textual and editorial content resides strictly in `pages/*.html`.
  * Content is **never** embedded directly in JavaScript template strings, facilitating SEO, readability, and subsequent migration to CMS templates.
* **Component Partials:**
  * Reusable UI components (such as `partials/collaborations.html`) are injected dynamically via `data-partial` attributes on container elements.
* **Styling Architecture:**
  * `styles.css`: Global design tokens, CSS variables, typography reset, color palettes.
  * `spa.css`: Layout containers, transitions, component cards, and navigation drawer styles.

---

## 3. Syllabus Platform Architecture (`html-js-site/syllabus/`)

* **Information Architecture:**
  * Columnar layout organized by pedagogical and thematic modules.
  * Interactive categorization filters: Articles, Books, Multimedia, Primary Sources.
* **Detail Inspection Panel:**
  * Collapsible side panel displaying full citation details, abstract metadata, visual placeholders, and related reading lists.
* **Educational Modules:**
  * Definition flashcards, historical timeline components, and reading lists centered on border justice and Indigenous sovereignty.

---

## 4. Archived Local Development Workflow

When running or referencing the archived static prototypes:

1. **Local HTTP Server:** Required to satisfy browser CORS security policies for `fetch()` requests on partials and page templates:
   ```bash
   cd html-js-site/main-site
   python3 -m http.server 8000
   ```
2. **Editing Content:** Edit HTML files located under `pages/*.html`.
3. **Editing Routing/Logic:** Modify `app.js`.
4. **Adding New Pages:**
   * Create `pages/new-page.html`.
   * Add corresponding navigation link to `index.html`.
   * Update routing handler in `app.js` if non-standard behavior is required.

---

## 5. Experimental & Creative Features

* **Melt WebGL Effect (`melt-effect.js`):** Custom WebGL distortion shader tested on the prototype homepage, producing an organic fluid melt transition. Archived and never ported to WordPress.
* **SVG Mask Title (`design-revamp/mask-experiment/`):** Viewport-registered SVG clip path masking a fixed desert landscape image through the title glyphs during vertical scroll, with hairline topbar transition.
