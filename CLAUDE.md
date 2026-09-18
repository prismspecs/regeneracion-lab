# Regeneración Lab - Project Context

## Overview
**Regeneración Lab** is a digital humanities and Indigenous studies initiative exploring water justice and decolonial praxis (referencing the 1900–1918 Mexican anarchist newspaper *Regeneración*).

* **Production:** [regeneracionlab.org](https://regeneracionlab.org) — powered by `regen_wp/`.
* **Repository Scope:** This repo is the `wp-content/themes/` directory in the local install.
* **Archived Prototypes:** `html-js-site/` contains pre-WordPress prototypes (reference only).

---

## Design Principles & Aesthetic
* **Typography:** Modern serif/sans contrast (Instrument Serif, Georgia, Inter/Roboto) via Material Design 3 type scales.
* **UI Style:** Sharp brutalist/modernist aesthetic (**no rounded corners / zero border-radius** on core containers), subtle neutral shadows.
* **Palette:** River water, desert earth, terracotta, and foliage green over warm parchment.
* **Accessibility & Layout:** Strict WCAG AA (min 4.5:1 text contrast), mobile-first responsive grid following Apple web layout standards.

---

## Architecture & Repository Structure
* `regen_wp/`: **Live WordPress theme.** PHP templates, semantic styles/scripts, no build step.
* `html-js-site/`: **Archived static prototypes.** Pre-WordPress SPA and syllabus experiments.
* `docs/spec/`: **Technical specifications** (see Progressive Disclosure below).
* `reference-sites/`: Visual inspiration screenshots.
* `PRODUCT.md`, `.impeccable.md`: Product briefs and design system specifications.
* `.claude/skills/`: Domain-specific agent skills (WordPress operations, accessibility, Playwright).

---

## Active Development Workflow (WordPress)
* **Local Environment:** **Local** (by WP Engine), site `regeneracion-lab`, URL `https://regeneracion-lab.local`, table prefix `wpjp_`.
* **Theme Development:** Edit PHP/CSS/JS in `regen_wp/`. Test locally, commit, and push to `origin/main`.
* **WP-CLI:** Wrapper at `~/.local/bin/wp` on PATH auto-resolves site environment from `$PWD`. Run commands (`wp post list`, `wp cache flush`) directly from repo root.
* **Database Staging:** Production sync uses the WP Migrate DB Pro plugin.
* **Visual Verification:** Real-browser CLI `./.claude/skills/playwright/scripts/playwright_cli.sh` for headless rendering, mobile viewport checks, and regression screenshots.

---

## Progressive Disclosure Reference Specifications

Detailed specifications are maintained in `docs/spec/` to conserve resident agent context:

1. **WordPress Architecture & Content Model:** [`docs/spec/wordpress-architecture.md`](docs/spec/wordpress-architecture.md)
   * Custom Post Types (`project`, `collaboration`), meta schemas, editor metaboxes, block patterns (`timeline`, `resource-header`, `resource-list`), and template hierarchy.
2. **Archived Static Prototypes:** [`docs/spec/archived-prototypes.md`](docs/spec/archived-prototypes.md)
   * SPA hash routing architecture, syllabus platform layout, local server notes, and WebGL melt/mask experiments.
3. **Agent Skills & Tooling:** [`docs/spec/agent-skills.md`](docs/spec/agent-skills.md)
   * Catalogue of tracked skills in `.claude/skills/` (WordPress routing/ops, WCAG suite, Playwright CLI) and uninstalled skill rationale.
