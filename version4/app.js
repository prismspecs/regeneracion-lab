// Menu toggle
const hamburgerMenu = document.getElementById('hamburgerMenu');
const closeMenu = document.getElementById('closeMenu');
const navOverlay = document.getElementById('navOverlay');
const overlayLinks = document.querySelectorAll('.overlay-link');

function openMenu() {
    navOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeMenuHandler() {
    navOverlay.classList.remove('open');
    document.body.style.overflow = '';
}

hamburgerMenu.addEventListener('click', openMenu);
closeMenu.addEventListener('click', closeMenuHandler);

// Close menu when clicking on a link
overlayLinks.forEach(link => {
    link.addEventListener('click', closeMenuHandler);
});

// Close menu on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navOverlay.classList.contains('open')) {
        closeMenuHandler();
    }
});

// Show site title and change hamburger color based on scroll
const header = document.querySelector('.site-header');
const siteTitle = document.getElementById('siteTitle');
const featureTitle = document.querySelector('.feature-title');

function handleScroll() {
    const currentScroll = window.pageYOffset;

    // Show site title when scrolled past feature title
    if (featureTitle) {
        const featureTitleRect = featureTitle.getBoundingClientRect();
        const featureTitleBottom = featureTitleRect.bottom;

        if (featureTitleBottom < 100) {
            siteTitle.classList.add('visible');
            header.classList.add('scrolled');
        } else {
            siteTitle.classList.remove('visible');
            header.classList.remove('scrolled');
        }
    }
}

window.addEventListener('scroll', handleScroll);
window.addEventListener('load', handleScroll);

// Smooth scroll for internal links
document.addEventListener('DOMContentLoaded', () => {
    const links = document.querySelectorAll('a[href^="#"]');

    links.forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href !== '#') {
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
});

// Parallax effect for hero section
const heroFeature = document.querySelector('.hero-feature');

function handleParallax() {
    if (!heroFeature) return;

    const scrolled = window.pageYOffset;
    const parallaxSpeed = 0.5;

    if (scrolled < window.innerHeight) {
        heroFeature.style.backgroundPositionY = `${scrolled * parallaxSpeed}px`;
    }
}

window.addEventListener('scroll', handleParallax);

// Fade-in animation for article content
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const fadeObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe article paragraphs
const articleParagraphs = document.querySelectorAll('.article-content p, .article-content h2, .article-content .pull-quote');
articleParagraphs.forEach((paragraph, index) => {
    paragraph.style.opacity = '0';
    paragraph.style.transform = 'translateY(30px)';
    paragraph.style.transition = `opacity 0.8s ease ${index * 0.1}s, transform 0.8s ease ${index * 0.1}s`;
    fadeObserver.observe(paragraph);
});

// Observe related items
const relatedItems = document.querySelectorAll('.related-item');
relatedItems.forEach((item, index) => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(30px)';
    item.style.transition = `opacity 0.6s ease ${index * 0.15}s, transform 0.6s ease ${index * 0.15}s`;
    fadeObserver.observe(item);
});

// Add click handlers for related items
relatedItems.forEach(item => {
    item.addEventListener('click', () => {
        // Placeholder for navigation
        console.log('Navigate to related story');
    });
});

// Prevent default on placeholder links
document.querySelectorAll('a[href="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});

