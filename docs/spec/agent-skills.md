# Agent Skills Inventory & Tooling Specification

This specification catalogs the AI agent skills tracked in `.claude/skills/` within this repository and outlines their purpose, execution models, and boundaries.

---

## 1. Tracked Skills Inventory (`.claude/skills/`)

All skills listed below are checked into version control and provide domain guidance:

### A. WordPress Lifecycle & Operations
* **`wordpress-router` & `wp-project-triage`:**
  * Classify repository architecture, detect classic vs block themes, and route implementation tasks to the correct WordPress pattern.
* **`wp-wpcli-and-ops`:**
  * Guides command-line WordPress operations including database import/export, URL search-replace, plugin activation, and content staging via `wp`.
* **`wp-performance`:**
  * Guidance on backend database queries, object caching, transient usage, asset minification, and LiteSpeed/cPanel shared-hosting optimization.
* **`wp-phpstan`:**
  * Static analysis guidelines and rule levels for WordPress PHP code (ready for future `phpstan.neon` integration).
* **`wp-patterns`:**
  * Guidelines for declaring and structuring WordPress core block patterns (e.g., Timeline, Resource Header, Resource List).

### B. Web Quality & Compliance (Addy Osmani Web-Quality Suite)
* **`wq-accessibility`:**
  * WCAG AA conformance rules, contrast ratio verification (minimum 4.5:1), ARIA roles, focus traps, and screen-reader navigable markup.
* **`wq-seo`:**
  * Semantic HTML hierarchy, Open Graph/Twitter card tags, structured schema.org data, and canonical URL hygiene.
* **`wq-performance`:**
  * Core Web Vitals targets (LCP, CLS, INP), image responsive sizing, font-display strategies, and DOM complexity reduction.
* **`wq-best-practices`:**
  * Security headers, CSP compliance, HTTPS enforcement, and Lighthouse best-practices standards.

### C. Visual Design & Browser Automation
* **`theme-factory` (Anthropic):**
  * Presets for palette exploration, font pairings, and document styling for artifacts and mockups.
* **`frontend-skill` (OpenAI):**
  * Aesthetic principles for layout, typography hierarchy, negative space, and visual rhythm. (Note: Archived snapshot preserved locally).
* **`playwright` (OpenAI):**
  * Real-browser automation executed via `./.claude/skills/playwright/scripts/playwright_cli.sh`.
  * Allows headless testing, mobile viewport emulation, visual screenshot capture, and layout verification without requiring an active MCP daemon.

---

## 2. Uninstalled Skills (Rationale)

* **`anthropics/frontend-design`:**
  * Redundant with Claude Code's built-in `frontend-design` system capability; avoided to prevent naming collisions.
* **`WordPress/agent-skills` (Block, REST API, Interactivity API):**
  * `wp-block-development`, `wp-block-themes`, `wp-rest-api`, `wp-interactivity-api`, `wp-playground`, `wp-plugin-development`.
  * Omitted because `regen_wp/` is a classic PHP template theme with no custom Gutenberg blocks, decoupled REST endpoints, or plugin boilerplates.

---

## 3. Skill Architecture & Footprint Note

The skills in `.claude/skills/` are invoked on-demand rather than preloaded into every prompt. To prevent context bloat:
* Consult specific skill manuals only when performing tasks within their domain.
* Prefer CLI-based tools (like `playwright_cli.sh` and `wp`) over persistent MCP tool schemas when performing targeted checks.
