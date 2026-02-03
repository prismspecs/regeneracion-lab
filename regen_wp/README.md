# Regeneración Lab WordPress Theme

Custom WordPress theme for Regeneración Lab, designed to maintain the aesthetic and content structure of the original static site while leveraging WordPress for content management.

## Overview

- **Theme Name:** Regeneración Lab Theme
- **Text Domain:** `regeneracion-lab`
- **Architecture:** PHP template-driven (replaces previous JS SPA routing).

## Key Features

- **Custom Post Types:**
  - `project`: For the "Projects" section (supports `project_badge` and `project_style` meta).
  - `collaboration`: For the "Collaborations" section.
- **Customizer Support:**
  - Site Identity (Logo/Title).
  - Hero Section (Home page featured image).
  - Support CTA (Button label/URL).
- **Templates:**
  - `front-page.php`: Custom landing page.
  - `archive-project.php`: Grid layout for projects.
  - `single-project.php`: Specialized view for project details.

## Setup

1. **Activate:** Select "Regeneración Lab Theme" in Appearance > Themes.
2. **Menu:** Create a menu and assign it to the "Primary Menu" location.
3. **Front Page:** Go to Settings > Reading and set "Your homepage displays" to a static page (select your "Home" page).
4. **Permalinks:** Set to "Post name" for clean URLs.

## Development

- **Styles:** `style.css` handles global theming.
- **Templates:** Modify `*.php` files in the root for layout changes.
- **Assets:** Images in `images/`, script logic (if any) in `app.js`.
