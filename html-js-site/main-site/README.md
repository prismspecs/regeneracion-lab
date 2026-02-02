# Regeneración Lab - Main Site

Clean architecture with content in HTML files for easy editing and WordPress migration.

## Architecture

**CRITICAL PRINCIPLE: Content lives in HTML files, NOT in JavaScript.**

### File Structure

```
main-site/
├── index.html          # Shell: header, nav, container
├── app.js              # Navigation logic + partials loader
├── styles.css          # Global styles
├── spa.css             # SPA-specific styles
├── metates.jpg         # Hero image
├── partials/           # Reusable content sections (no duplication!)
│   └── collaborations.html    # Used on home + projects pages
└── pages/              # ALL CONTENT HERE ← Edit these!
    ├── home.html
    ├── about.html
    ├── projects.html
    ├── residents.html
    ├── students.html
    ├── support.html
    └── detail-*.html   # Intermediary pages
```

## Editing Content

### Regular Pages
1. Open the HTML file in `/pages/` directory
2. Edit the HTML
3. Save
4. Refresh browser

### Reusable Sections (Partials)
Some content appears on multiple pages (e.g., Collaborations on both Home and Projects):
1. Edit the file in `/partials/` directory
2. Save
3. Changes automatically appear on all pages using that partial

**To add a partial to a page:** `<div data-partial="filename"></div>`

**No JavaScript knowledge required!**

## Local Testing

You MUST use a local server (fetch() doesn't work with file:// URLs):

```bash
cd main-site
python3 -m http.server 8000
# Open http://localhost:8000
```

## Key Features

### Partials System
- Avoids duplicating content across pages
- Edit once, updates everywhere
- Example: Collaborations section appears on Home + Projects but defined once in `partials/collaborations.html`

### Detail Pages
- Main pages show brief content
- Click through to detail pages for full info
- Residents: Brief bio + photo → Click for full bio
- Projects: Overview → Click for details

### Clickable Cards
- Entire resident cards are clickable (not just the link)
- Better UX

## Forms

All forms use placeholder `YOUR_FORM_ID` - configure with Formspree:

1. Sign up at formspree.io
2. Create form for asalomon@ucsb.edu
3. Replace `YOUR_FORM_ID` in HTML files
4. Test submission

## WordPress Migration

When migrating to WordPress:
- Each HTML file → WordPress page
- Partials → Reusable blocks or template parts
- Forms → Form plugins (Contact Form 7, etc.)
- Navigation → WordPress menus
- CSS → Theme styles

Clean separation makes this straightforward.

## More Info

See `plan.md` for architecture decisions and `ARCHITECTURE.md` for detailed documentation.
