# Regeneración Lab - Version 6

Updated version of the Regeneración Lab website with proper content/code separation for easy WordPress migration.

## Architecture

### Content Separation (CRITICAL PRINCIPLE)
**Content lives in HTML files, NOT in JavaScript.**

This architecture ensures:
- ✅ Content editors can edit HTML without touching code
- ✅ SEO-friendly (real HTML content, not JS-generated)
- ✅ Easy WordPress migration path
- ✅ Clean separation of concerns
- ✅ Cacheable content files

### File Structure

```
version6/
├── index.html          # Shell page with header/navigation
├── app.js             # SPA navigation logic ONLY (no content)
├── styles.css         # Global styles
├── spa.css            # SPA-specific styles
├── metates.jpg        # Hero image
└── pages/             # ALL PAGE CONTENT GOES HERE
    ├── home.html      # Home page content
    ├── about.html     # About page content
    ├── projects.html  # Projects page content
    ├── residents.html # Residents page content
    ├── students.html  # Students page content
    └── support.html   # Support page content
```

## How It Works

1. **index.html** provides the shell (header, navigation, container)
2. **app.js** handles:
   - Navigation clicks
   - Loading HTML files from `/pages/` directory
   - Hash-based routing (#home, #about, etc.)
   - Tab switching on residents page
   - Loading states
3. **pages/*.html** contain ONLY page content (no shell)
4. Content is loaded via fetch() and injected into the main container

## Editing Content

### To Edit Page Content:
1. Open the appropriate file in `/pages/` directory
2. Edit the HTML directly
3. Save the file
4. Refresh the page

**Example:** To edit the About page, edit `pages/about.html`

### No JavaScript Knowledge Required
Content editors never need to touch `app.js` - it's just the navigation engine.

## WordPress Migration Path

When moving to WordPress:

1. Each HTML file in `/pages/` becomes a WordPress page or template
2. Forms get replaced with WordPress plugins (Contact Form 7, Gravity Forms, etc.)
3. Navigation gets replaced with WordPress menus
4. CSS stays largely the same
5. No need to untangle content from JavaScript

## Setup Instructions

### 1. Local Development

**Option A: Simple HTTP Server (Python)**
```bash
cd version6
python3 -m http.server 8000
```
Then open: http://localhost:8000

**Option B: PHP Server**
```bash
cd version6
php -S localhost:8000
```

**Option C: Node.js http-server**
```bash
npm install -g http-server
cd version6
http-server -p 8000
```

**Important:** You need a local server because the site loads HTML files via fetch(). Opening index.html directly in a browser won't work due to CORS restrictions.

### 2. Form Configuration

All forms use placeholder: `https://formspree.io/f/YOUR_FORM_ID`

**Forms to configure:**
1. About page - Contact Us form
2. Residents page - Application form (with file uploads)
3. Students page - Reading group registration
4. Support page - Contact form

**Setup with Formspree:**
1. Sign up at [formspree.io](https://formspree.io)
2. Create form endpoint for asalomon@ucsb.edu
3. Get your form ID (e.g., `abc123def`)
4. Search and replace in all HTML files:
   - Find: `YOUR_FORM_ID`
   - Replace with: your actual form ID

**Bulk replace command:**
```bash
cd pages
sed -i 's/YOUR_FORM_ID/your_actual_id/g' *.html
```

## Page Content Overview

### Home Page (pages/home.html)
- Hero image with revolutionary quote
- Introduction to Regeneración
- 6 project cards (including Safiya Henderson Holmes archive)
- 3 collaboration cards
- 3 recent updates
- Support box

### About Page (pages/about.html)
- About Regeneración Lab
- "Why Regeneración?" with newspaper history
- Our Approach
- Director bio (Amrah Salomón)
- Contact form

### Projects Page (pages/projects.html)
- 5 main projects with external links
- Clean, simple layout

### Residents Page (pages/residents.html)
- **3 tabs:** Current, Past, Apply
- Current: B.T. Werner (full bio), Lyra Monteiro (placeholder)
- Past: Omar Zahzah (full bio)
- Apply: Complete application form

### Students Page (pages/students.html)
- Reading group information and join form
- Study & research opportunities
- Resources section

### Support Page (pages/support.html)
- Financial contributions
- Share resources
- Collaborate
- Contact form

## External Links

All external links are configured in the HTML files:

**Collaborations:**
- Water Justice & Technology: https://waterjustice-tech.org/
- CIEJ: http://www.the-ciej.org/
- Finding Ceremony: https://findingceremony.com/

**Projects:**
- Reclaiming Homelands: https://storymaps.arcgis.com/stories/de868339c9a84afb920ead88bf65ccc3
- Indigenous Borderlands: https://uchri.org/awards/indigenous-borderlands-refusal-and-fugitivity/
- Symposium: https://crjucsc.com/calendar/the-indigenous-borderlands-an-exploration-of-the-borderlands-from-indigenous-perspectives-across-the-americas
- Museum of Us: https://museumofus.org/exhibits/hostile-terrain-94
- American Quarterly: https://www.americanquarterly.org/

**Support:**
- UCSB Donation: https://give.ucsb.edu/campaigns/58594/donations/new

## Fonts

- **Libre Baskerville** - Headings and titles (serif)
- **Inter** - Body text (sans-serif)
- **IBM Plex Mono** - Labels, badges, navigation (monospace)

Loaded from Google Fonts in index.html

## Color Palette

Earth and ink newspaper aesthetic:
- Primary Background: `#f5f5f0` (newsprint/cream)
- Primary Text: `#1a1a1a` (ink black)
- Accent Green: `#2d5016` (forest)
- Secondary Green: `#4a7c59` (sage)
- Earth Brown: `#8b4513`
- Clay Red: `#c05746`

## Browser Support

Requires modern browser with fetch() API support:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari 10.1+

## Content Updates Needed

1. **Lyra Monteiro** - Add bio and residency projects
2. **Indigenous Border Studies CFP** - Create dedicated page
3. **Indigenous Border Studies Syllabus** - Add link
4. **Omar Zahzah photo** - Optional: Add image from ozahzah.com

## Testing Checklist

Before deployment:
- [ ] Test all forms submit to asalomon@ucsb.edu
- [ ] Verify all external links work
- [ ] Test on mobile devices
- [ ] Verify hero image displays
- [ ] Test tab switching on residents page
- [ ] Test all internal navigation (#home, #about, etc.)
- [ ] Test back/forward browser buttons
- [ ] Verify file upload on residency application

## Deployment

1. Configure all form endpoints
2. Upload entire `version6/` directory to web server
3. Ensure server can serve HTML files properly
4. Test all forms and links
5. Monitor form submissions

## Future: WordPress Migration

When ready to migrate to WordPress:

### Content Migration
- Convert each `pages/*.html` file to WordPress page
- Or use as template parts in theme

### Forms
- Install Contact Form 7 or Gravity Forms
- Recreate forms in WordPress
- Update HTML to use WordPress form shortcodes

### Navigation
- Create WordPress menu matching current structure
- Replace hardcoded nav in index.html

### Styling
- Move CSS to WordPress theme
- May need to adjust some selectors

### Images
- Upload to WordPress media library
- Update image paths

The clean HTML content structure makes this migration straightforward.

## Contact

For questions: asalomon@ucsb.edu

## Notes

- Hero image is `metates.jpg` (cooking holes/metates)
- Site uses hash-based routing for client-side navigation
- All content is accessible without JavaScript (HTML files are standalone)
- Forms need JavaScript enabled for AJAX submission, but work with standard POST otherwise
