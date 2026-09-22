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
* `regen_wp/`: **Live WordPress theme.** PHP templates, no build step. The design CSS/JS live once in `regen_wp/assets/` (`site.*` on every page, `page.css`, `home.*`, `cards.css`, `support.*`, `pages/*.css`). **Never copy these files; link to them.**
* `html-js-site/design-revamp/mask-experiment/`: the static prototype of the current design. Its pages link to `regen_wp/assets/…`; content changes belong in WordPress, layout changes in `assets/`.
* `html-js-site/` (rest): **Archived static prototypes.** Pre-WordPress SPA and syllabus experiments.
* `docs/spec/`: **Technical specifications** (see Progressive Disclosure below).
* `reference-sites/`: Visual inspiration screenshots.
* `PRODUCT.md`, `.impeccable.md`: Product briefs and design system specifications.
* `.claude/skills/`: Domain-specific agent skills (WordPress operations, accessibility, Playwright).

---

## Active Development Workflow (WordPress)
* **Local Environment:** **Local** (by WP Engine). Develop against site `Regeneración Lab newermaybe` (`~/Local Sites/regeneracin-lab-newermaybe`, `https://regeneracin-lab-newermaybe.local`), a pull of production whose `themes/regen_wp` is a **symlink to this repo's `regen_wp/`**. The older `regeneracion-lab` Local site is stale and superseded (safe to delete via Local's own UI whenever). Table prefix `wpjp_`. Nobody edits production directly, so this pull doesn't go stale on its own.
* **Theme Development:** Edit PHP/CSS/JS in `regen_wp/`. Work on a branch (e.g. `redesign`), verify in the browser and Mailpit, then merge to `main`. Menu items for site sections must be Page links, not typed-URL custom links (a missing trailing slash forces a redirect on every click — see spec §3).
* **WP-CLI:** Wrapper at `~/.local/bin/wp` on PATH auto-resolves the site from `$PWD`; run it from inside a Local site directory (e.g. `~/Local Sites/regeneracin-lab-newermaybe/app/public`). `wp db export` needs Local's `mysqldump` (see the spec's DB backup note).
* **Accessibility:** the theme is verified at zero axe-core violations (WCAG 2.1/2.2 AA + best-practice) across every template; re-run the check (spec §7) after structural template changes, not just style ones.
* **Before deploying:** push the theme and/or upload a Migrate DB Pro package (include Media Library — hero photos are attachments). Re-pull production first only if someone has started editing it directly.
* **Full architecture, what-is-edited-where table, forms/mail notes, a11y notes:** [`docs/spec/wordpress-architecture.md`](docs/spec/wordpress-architecture.md).
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
