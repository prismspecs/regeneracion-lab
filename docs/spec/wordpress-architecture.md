# WordPress Architecture & Content Model Specification

This specification documents the live WordPress theme implementation located in `regen_wp/` for [Regeneración Lab](https://regeneracionlab.org).

---

## 1. Overview & Architecture
* **Theme Directory:** `regen_wp/`
* **Stack:** Classic WordPress theme, PHP templates, semantic CSS/JS, no build step.
* **Environment:** Local (by WP Engine), site `regeneracion-lab`, table prefix `wpjp_`.
* **Theme Setup:**
  * Nav menus registered (`Primary`).
  * Enqueues only necessary assets; SPA script (`app.js`) is disabled by default.
  * Title tag, featured images (post thumbnails), primary menu, and Customizer options (hero/support CTA) enabled.
  * `front-page.php` handles the landing page with standard WP loops and template parts.

---

## 2. Landing Page Content Model & Sources

| Section | Content Source / Implementation | Details |
| :--- | :--- | :--- |
| **Site Identity** | `bloginfo()` + Custom Logo | Theme supports custom logo via WordPress Customizer. |
| **Navigation** | `wp_nav_menu()` | "Primary" menu location configured in WP Dashboard. |
| **Hero** | Page Featured Image / Customizer | Homepage featured image (or Customizer image fallback). Hero quote and attribution configured via Customizer. |
| **Intro Copy** | Page Content (`the_content()`) | Drawn from Home page (WP page ID 7). |
| **Projects Grid** | Custom Post Type `project` | Rendered via custom loop with meta fields (see schema below). |
| **Collaborations**| Custom Post Type `collaboration` | Rendered via template part; supports external link CTA. |
| **Recent Updates**| Standard Posts | Category: `Updates`. Loop count restricted on the front page. |
| **Support CTA** | Customizer Options | Button label, URL, and callout text configured in Customizer. |

---

## 3. Custom Post Types & Meta Schema

### A. Projects (`project`)
* **Badge (`project_badge`):** Displays when set (on live site, displays when value is "Ongoing"; template supports "New" and other custom badges).
* **Meta line (`project_meta`):** Year/status string (e.g. `Ongoing`, `2025–2026`).
* **Link Label (`project_link_label`):** Call-to-action text (e.g. `Explore →`).
* **Link URL (`project_link_url`):** Optional URL (external URLs open in a new tab).
* **Card Style (`project_style`):** Color accent variant (`turquoise`, `brown`, `amber`).
* **Title Overrides (`project_title_line1` / `project_title_line2`):** Manual two-line title break formatting.
* **Excerpt:** Standard post excerpt populates the project card body.
* **Editor Metabox:** "Project Display" in main column containing all above fields.

### B. Collaborations (`collaboration`)
* **Link Label (`collaboration_link_label`):** CTA button text.
* **Link URL (`collaboration_link_url`):** Destination URL (external opens in a new tab).
* **Editor Metabox:** "Collaboration Link" in main edit column.

---

## 4. Theme Templates

* `front-page.php`: Main landing page combining hero, intro copy, projects grid, collaborations, updates, and CTA.
* `archive-project.php`: Dedicated archive grid for all `project` posts.
* `single-project.php`: Single project detail view with neutral header palette.
* `single.php`: Generic single post template.
* `index.php`: Fallback default loop template.
* `page.php` *(Planned)*: Standard static page template matching global typography and spacing.

---

## 5. Block Patterns

* **Timeline (`timeline`):** Block pattern markup with `.timeline` CSS classes for chronological project histories and milestones.
* **Resource Header (`resource-header`):** `<h4>` element with `.resource-header` class for bibliography and syllabus sections.
* **Resource List (`resource-list`):** `<ul>` element with `.resource-list` class providing stylized bibliographic list spacing.

---

## 6. Migration & Operational Notes

* **Data Migration:** Migrate static HTML fragments from `regen_wp/pages/*.html` into native WordPress content types (Pages, CPTs, Posts). Map local image URLs to the WordPress Media Library.
* **Email Delivery:** Configure an SMTP plugin (e.g., WP Mail SMTP) to guarantee reliable delivery for Contact Form 7 submissions and prevent spam filtering.
* **SPA Transition:** SPA hash-based routing (`app.js`) is retired for the production theme in favor of native WordPress template routing. Keep `app.js` archived only if standalone static parity testing is required.
