# Regeneración Lab

This repository is the custom WordPress theme and design history for **Regeneración Lab**, a digital humanities and Indigenous studies research platform. It's designed to reflect the aesthetic of the turn-of-the-century Mexican anarchist newspaper *Regeneración* (1900–1918), blending historical typography with modern, accessible web design.

- **Live site:** https://regeneracionlab.org
- **Hosting:** cPanel/LiteSpeed shared hosting
- **Platform:** WordPress, custom theme (`regen_wp`)

## What's in here

| Path | What it is |
|---|---|
| `regen_wp/` | The active, live WordPress theme. This is what's running in production. |
| `html-js-site/` | Archived pre-WordPress design history: the original static HTML/JS/SPA prototypes (`main-site`, `merged-site`, `new-main-site`, `design-revamp`, `archive`, `examples`, `syllabus`) that the current theme evolved from. Kept for reference, not deployed anywhere. |
| `reference-sites/` | Screenshots of design inspiration (Kinfolk, Native Bound Unbound, Culture Hack Labs, Water Justice and Technology Studio). |
| `PRODUCT.md` | Product brief: register, users, purpose, brand personality, anti-references. |
| `.impeccable.md` | Design system context: aesthetic direction, typography, design principles. |
| `GEMINI.md` | Working notes on architecture, the WP migration, and content model — the most detailed reference for how the theme is built. |

This repo only tracks the theme and design assets, not the full WordPress install (core, plugins, uploads, database). See below for how it plugs into a full site.

## Local development

The full WordPress site (this theme + WP core + plugins + database) runs locally via **Local** (by WP Engine):

- Site name: `regeneracion-lab`
- Local URL: https://regeneracion-lab.local
- Table prefix: `wpjp_` (matches production)
- This repo lives at `app/public/wp-content/themes/` inside the Local site folder — that's the git working tree.

To get a fresh machine set up: create a Local site, point/clone this repo into its `wp-content/themes/`, and import a current database export (production pulls are done via the WP Migrate DB Pro plugin, already installed). Production content lives under table prefix `wpjp_`; make sure `wp-config.php`'s `$table_prefix` matches before importing.

## Development status

The WordPress theme is live in production. `html-js-site/` is historical — new design work happens in `regen_wp/` and, for early-stage exploration, may still start as static HTML/CSS/JS before being ported into PHP templates.
