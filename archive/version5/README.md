# Regeneración Lab — Version 5

**The Ultimate Combination**

Version 5 is a comprehensive prototype that combines the best features from all previous versions into a single, cohesive experience.

## What's Included

### From Version 1 (SPA Navigation)
- Multi-page content structure with smooth transitions
- Tab-based navigation for resident profiles
- Content caching and routing capabilities

### From Version 2 (Archive/Catalog)
- Filterable research archive with 12 catalog items
- Sliding detail panel for in-depth content viewing
- Category-based filtering system (All, Articles, Books, Essays, Research, Archive)
- Professional catalog grid layout

### From Version 3 (Modern Scroll Design)
- Full-screen hero section with background imagery
- Animated marquee section
- Smooth scroll navigation
- Fixed header with scroll effects
- Modern, minimal aesthetic

### From Version 4 (Editorial/Magazine)
- Editorial story cards with featured content
- Magazine-style typography and layout
- Pull quotes and drop caps
- Hero overlays and immersive storytelling

## Features

### Navigation
- **Full-screen overlay menu** - Clean, minimal navigation experience
- **Smooth scroll** - Seamless scrolling between sections
- **Fixed header** - Logo appears/disappears based on scroll position
- **Keyboard support** - ESC key closes all overlays

### Sections
1. **Hero** - Full-screen introduction with background image and call-to-action
2. **About** - Editorial-style content with drop caps and pull quotes
3. **Residents** - Grid display of current scholars and artists with bios
4. **Projects** - List of active research initiatives
5. **Archive** - Filterable catalog of 12 research items with detail panel
6. **Stories** - Magazine-style featured content cards
7. **Support** - Call-to-action for community funding
8. **Footer** - Comprehensive site navigation and links

### Interactive Elements
- Archive filtering by content type
- Detail panel for catalog items
- Hover effects on all interactive elements
- Smooth transitions and animations
- Responsive touch targets

### Design System
- **Colors**: Earth tones (greens, browns, tans) with black ink aesthetic
- **Typography**: 
  - Libre Baskerville (serif headlines)
  - Cormorant Garamond (alt serif)
  - Inter (body text)
  - IBM Plex Mono (labels, codes)
- **Layout**: Responsive grid system, mobile-first approach
- **Spacing**: Consistent 40px/25px spacing units

### Performance
- Throttled scroll handlers
- Intersection Observer for lazy animations
- Efficient DOM manipulation
- Minimal JavaScript footprint
- CSS-based animations

### Accessibility
- Semantic HTML structure
- Keyboard navigation support
- ARIA labels on interactive elements
- Sufficient color contrast
- Focus states on all interactive elements

## Technical Stack

- **HTML5** - Semantic markup
- **CSS3** - Custom properties, Grid, Flexbox, animations
- **Vanilla JavaScript** - No frameworks, ES6+ syntax
- **Responsive** - Mobile-first, works on all screen sizes

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## File Structure

```
version5/
├── index.html          # Main HTML structure
├── styles.css          # Comprehensive styling
├── app.js             # JavaScript functionality
├── vikam-doag.jpg     # Hero background image
└── README.md          # This file
```

## Key Interactions

1. **Menu Navigation**: Click "+ MENU" → Select section → Smooth scroll to section
2. **Archive Filtering**: Click filter buttons → See filtered catalog items
3. **Detail View**: Click any catalog item → See full details in slide-out panel
4. **Smooth Scrolling**: Click any link → Smooth scroll to target section

## Responsive Breakpoints

- **Desktop**: > 1024px (full layout)
- **Tablet**: 768px - 1024px (adjusted grid)
- **Mobile**: < 768px (single column, hamburger menu)

## Color Palette

```css
--color-primary-bg: #f5f5f0     /* Newsprint cream */
--color-primary-text: #1a1a1a   /* Ink black */
--color-accent-green: #2d5016   /* Forest green */
--color-earth-brown: #8b4513    /* Earth brown */
--color-clay-red: #c05746       /* Clay red */
--color-bg-tan: #b5a999         /* Archive tan */
```

## Typography Scale

- **Hero Title**: 7em (clamp: 3em - 7em)
- **Section Titles**: 3.5em (clamp: 2em - 3.5em)
- **Card Titles**: 1.5em - 2em
- **Body Text**: 1em - 1.1em
- **Labels**: 0.75em - 0.85em

## Animation Examples

- **Marquee**: 30s infinite scroll
- **Fade In**: 0.6s ease-out on scroll
- **Hover Effects**: 0.2s - 0.3s transitions
- **Panel Slides**: 0.4s ease transitions
- **Scroll Indicator**: 2s bounce animation

## Development Notes

- No build process required - works directly in browser
- No dependencies - pure vanilla JavaScript
- Modular CSS with custom properties
- ES6+ features (classes, arrow functions, template literals)
- Modern event handling (addEventListener, IntersectionObserver)

## Future Enhancements

Potential additions for production:
- Content management system integration
- Search functionality for archive
- Pagination for catalog items
- Social media sharing
- Newsletter signup integration
- Actual backend API connections
- Analytics integration
- Image lazy loading
- Service worker for offline support

## Credits

**Design**: Inspired by anarchist newspaper aesthetic (Regeneración 1900-1918)  
**Content**: Real profiles from Indigenous Action members  
**Reference Sites**: Indigenous Action, Emergence Magazine, Culture Hack, Archive Magon

---

Built with care for the Regeneración Lab project.

