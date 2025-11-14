# Regeneración Lab Project Plan

## Project Overview

Three-project contract for Amrah focusing on digital humanities and Indigenous studies platforms.

**Rate:** $100/hour  
**Total scope:** 90–180 hours across all projects

---

## Project 1: Main Research Lab Website (Primary Focus)

**Budget:** $3,000–$5,000 (30–50 hours)  
**Timeline:** Landing page by mid-October (extended from mid-September)  
**Platform:** WordPress  
**Hosting:** Pantheon (UCSB-hosted)

### URLs

- Production: `regeneracionlab.org` (with redirect from `regen.english.ucsb.edu`)
- Development: https://dev-regen-eng-ucsb-edu-v01.pantheonsite.io/

### Site Structure

- Landing page (home)
- About page
- Scholar/artist-in-residence profiles
- Projects page
- Fund us page (grants received)
- Links to related projects

### Design Aesthetic

**Core theme:** Turn-of-the-century anarchist newspaper aesthetic inspired by the Mexican anarchist newspaper *Regeneración* (1900-1918).

**Visual approach:**
- Clean, minimal, accessible
- Easy to read with strong focus on content
- Nature-evoking color palette: whites, grays, blacks, with greens, blues, browns, muted reds/oranges
- River imagery is central (water justice theme)
- Consider experimental navigation: image warping, data moshing, scroll storytelling
- Composed elements from photos (shrubs, rocks, shells, water features)

**Reference sites:**

- **Primary style:** https://fuckhope.noblogs.org/ (anarchist aesthetic)
- **Fonts/historical design:** https://archivomagon.net/en/periodicos/regeneracion-1900-1918/
- **Navigation/scroll storytelling:** https://www.culturehack.io/
- **Design and video integration:** https://emergencemagazine.org/
- **Archive/clearinghouse structure:** https://www.indigenousaction.org/
- **Clean design:** https://waterjustice-tech.org/ (previous collaboration with Theodora Dryer)
- **Purple background angle:** https://thenewcentre.org/about/
- **Other references:** 
  - https://webresidencies.akademie-solitude.de/carrier-bag/
  - https://cyberfeminismindex.com/
  - https://slateandash.com/

### Technical Details

- **CMS:** WordPress (confirmed)
- **Hosting:** Pantheon (UCSB)
- **Access:** SFTP/SSH interface available
- **Backups:** Daily backups via Pantheon
- **Development:** Dev environment with push capability built into Pantheon
- **Note:** Previous platform auto-deletes content after 30 days of inactivity

### Content Development

**Priority order:**
1. Home page
2. About page
3. Residencies (scholar/artist profiles with bios and photos)
4. Projects page

**Assets:** [Shared photos folder](https://drive.google.com/file/d/1DzN2GWVg51Bj7NV5EgQElXvDm3zvfzT0/view?ts=68dc0d9b)

**Visual inspiration:** "Motates" (cooking holes) photo for main site vibe

### Communication

- Primary channels: Google Drive and email
- Timesheet: Biweekly or monthly
- Finance: Coordinate with Amrah
- No additional stakeholders for approvals (just need resident info for profiles)

---

## Project 2: Indigenous Border Studies Syllabus Platform

**Budget:** $2,000–$6,000 (20–60 hours)  
**Timeline:** End of fall semester  
**Note:** Budget range due to uncertainty around creative hub complexity

### Structure

- Online course format with thematic modules
- Topic pages
- About page
- Films and readings links
- Definition flashcards (flip cards)
- Questions and activities
- Timelines
- Short lecture videos
- Creative hub for project submissions and networking (may start as simple contact list)

### Reference

- https://library.saintheron.com/ (more relevant for syllabus design)

---

## Project 3: Tribal Community Historical Site

**Budget:** $4,000–$7,000 (40–70 hours)

### Structure

- General historical info page
- About page
- Maps
- Archival records
- Timeline
- Videos
- Other creative elements

---

## Current Action Items

- [ ] Connect with web admin for Pantheon credentials
- [ ] Confirm final domain/hosting configuration
- [ ] Establish check-in schedule
- [ ] Receive initial content for home and about pages
- [x] Collect resident bios and photos for profiles (Updated with Indigenous Action members)
- [ ] Set up WordPress on Pantheon dev environment
- [ ] Begin landing page design (target: mid-October)

---

## Design Decisions Made

- **CMS:** WordPress (confirmed)
- **Color palette:** Minimal base (white/gray/black) with nature colors (greens, blues, browns, muted reds/oranges)
- **Fonts:** Clean, accessible, inspired by turn-of-century anarchist newspapers
- **Vibe:** Clean, minimal, accessible, with experimental/trippy navigation elements
- **Primary theme:** Rivers and water justice
- **Historical inspiration:** Mexican anarchist newspaper *Regeneración*

## Architecture Decisions

### Content Separation (CRITICAL)
- **NEVER store content in JavaScript files** - this is bad for SEO, maintenance, and WordPress migration
- **Content structure:** All page content lives in separate HTML files in `/pages/` directory
- **JavaScript role:** Only handles navigation, loading, and interactivity - NO content
- **WordPress migration path:** HTML files can be directly converted to WordPress templates/pages
- **Benefits:**
  - Content editors can edit HTML files without touching code
  - SEO-friendly (content is in HTML, not generated by JS)
  - Easy to migrate to WordPress
  - Clean separation of concerns
  - Cacheable content files

### File Structure
```
main-site/              # ACTIVE DIRECTORY
├── index.html          # Shell page with header/nav/footer
├── app.js             # Navigation and SPA logic + partial loader
├── styles.css         # Global styles
├── spa.css            # SPA-specific styles
├── partials/          # Reusable content sections (no duplication!)
│   └── collaborations.html    # Used on both home + projects pages
└── pages/             # All page content (HTML fragments)
    ├── home.html
    ├── about.html
    ├── projects.html
    ├── residents.html
    ├── students.html
    ├── support.html
    ├── detail-reclaiming-homelands.html        # Intermediary page
    ├── detail-resident-bt-werner.html          # Resident detail page
    └── detail-resident-omar-zahzah.html        # Resident detail page
```

### Partials System
- **Purpose:** Avoid duplicating content that appears on multiple pages
- **Usage:** Add `<div data-partial="filename"></div>` in any page
- **How it works:** After page loads, app.js finds all `[data-partial]` elements and loads content from `partials/` directory
- **Example:** Collaborations section appears on both Home and Projects pages but defined once in `partials/collaborations.html`

### Content Strategy
- **Main pages:** Brief, scannable content (~amount shown in reference images)
- **Detail pages:** Click-through for full information
- **Residents:** Brief bio + photo placeholder → Click for full bio
- **Projects:** Major projects on main page → Click for details before external links

---

## Notes

- "Regeneración" means "Regeneration" in Spanish
- Previous successful collaboration on water justice website
- All three projects are interconnected through Indigenous studies and social justice themes
- Accessibility is a priority across all platforms

## Content Updates

### October 4, 2025
**Resident Profiles Updated** - All 4 prototype versions now feature real Indigenous Action members:
- **Klee Benally** - Diné artist, activist, and musician (Indigenous Action, Haul No!, Diné No Nukes)
- **Leona Morgan** - Diné activist working on uranium mining resistance and nuclear justice (Diné No Nukes)
- **Louise Benally** - Land defense organizer (Protect the Peaks, Indigenous Action)

These profiles replace placeholder content and align with the project's anti-colonial research focus and connection to Indigenous Action's clearinghouse model (reference site: https://www.indigenousaction.org/)

**Version 5 Created** - Comprehensive prototype combining all previous versions:
- **Version 1 features**: SPA-style content navigation, tabbed interfaces
- **Version 2 features**: Filterable archive/catalog system with detail panel
- **Version 3 features**: Modern scroll-based design, hero imagery, marquee elements
- **Version 4 features**: Editorial/magazine aesthetic, storytelling approach

Version 5 provides a complete, production-ready foundation with:
- Full-screen hero section with background image
- Smooth scroll navigation with fixed header
- Comprehensive sections: Home, About, Residents, Projects, Archive, Stories, Support
- Interactive archive with 12 catalog items and detail panel
- Responsive design optimized for all screen sizes
- Performance-optimized JavaScript with throttling/debouncing
- Accessible navigation with keyboard support (ESC key closes overlays)
- Combined color palette from all versions
- Multiple typography options (Libre Baskerville, Cormorant Garamond, Inter, IBM Plex Mono)

