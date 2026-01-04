# Regeneración Lab - Project Context for Gemini

## Design Principles
- Always follow Google Material Design 3 for typography: https://m3.material.io/styles/typography/applying-type
- Use Apple's layout best practices for web views: https://developer.apple.com/design/human-interface-guidelines/layout
- Ensure contrast ratios follow WCAG standards (e.g., 4.5:1 for small text).

## Project Overview
**Regeneración Lab** is a digital humanities and Indigenous studies initiative involving three interconnected web projects.
1.  **Main Research Lab Website (`main-site/`)**: The primary public face of the lab.
2.  **Indigenous Border Studies Syllabus (`syllabus/`)**: An educational platform with thematic modules.
3.  **Tribal Community Historical Site**: (Planned/In-progress).

**Core Theme:** Water justice, Indigenous studies, and "Regeneración" (referencing the Mexican anarchist newspaper).
**Aesthetic:** Turn-of-the-century anarchist newspaper, nature-evoking colors (river imagery), clean/minimal but with experimental navigation.

## Architecture & Tech Stack (Critical)

**Stack:** Vanilla HTML, CSS, and JavaScript. **NO frameworks** (React, Vue, etc.) are used or desired.

### Main Site Architecture (`main-site/`)
The main site uses a custom "HTML-first" SPA architecture designed for future WordPress migration.

*   **Shell:** `index.html` contains the header, nav, and an empty `#mainContent` container.
*   **Routing:** Hash-based routing (e.g., `#about`, `#residents`) handled by `app.js`.
*   **Content Strategy (CRITICAL RULE):**
    *   **NEVER** put content in JavaScript strings.
    *   All page content lives in individual HTML fragments in `pages/` (e.g., `pages/home.html`).
    *   `app.js` fetches these fragments and injects them into `#mainContent`.
    *   **Reasoning:** This ensures SEO, easy editing for non-coders, and a direct migration path to WordPress templates.
*   **Partials:** Reusable sections (like `collaborations.html`) live in `partials/` and are injected via `data-partial` attributes.

### Styling
*   **`styles.css`**: Global variables, typography, and base styles.
*   **`spa.css`**: Layouts, component styles, and SPA-specific rules.
*   **Design System:** CSS variables are used for colors and fonts.

## Directory Structure

*   **`main-site/`**: **active development**. The main website.
    *   `pages/`: Content fragments (Edit these for text changes).
    *   `partials/`: Reusable content blocks.
    *   `images/`: Local assets.
*   **`syllabus/`**: The syllabus platform (formerly v2).
*   **`syllabus-old/`**: Archived syllabus platform (formerly v1).
*   **`ingest/`**: Raw image assets for use across projects.
*   **`temporary-landing/`**: A placeholder landing page.
*   **`archive/`**: Old versions (v1-v5). Do not edit.

## Development Workflow

1.  **Local Server:** Due to `fetch()` usage, you **MUST** run a local server to test:
    ```bash
    cd main-site
    python3 -m http.server 8000
    ```
2.  **Editing Content:** Modify files in `pages/`. Refresh browser.
3.  **Editing Logic:** Modify `app.js`.
4.  **Adding Pages:**
    *   Create `pages/new-page.html`.
    *   Add link to navigation in `index.html`.
    *   (Optional) Update `app.js` if custom routing logic is needed (usually not required for simple pages).

## Future Goals
*   **WordPress Migration:** The strict separation of HTML content from JS logic is to facilitate moving to WordPress later. Keep markup semantic and clean.
*   **Accessibility:** A high priority for all platforms.

## Important Notes
*   **Images:** Use images from `ingest/` or `main-site/images/`.
*   **Forms:** Use Formspree (placeholder `YOUR_FORM_ID` in code).
*   **"Melt" Effect:** An experimental WebGL effect exists on the homepage (`melt-effect.js`).
