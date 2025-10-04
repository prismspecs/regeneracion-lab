// Menu toggle functionality
const menuBtn = document.getElementById('menuBtn');
const closeNav = document.getElementById('closeNav');
const navOverlay = document.getElementById('navOverlay');
const navItems = document.querySelectorAll('.nav-item');

function openMenu() {
    navOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeMenu() {
    navOverlay.classList.remove('open');
    document.body.style.overflow = '';
}

menuBtn.addEventListener('click', openMenu);
closeNav.addEventListener('click', closeMenu);

// Close menu when clicking on a nav item
navItems.forEach(item => {
    item.addEventListener('click', () => {
        closeMenu();
    });
});

// Close menu on escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navOverlay.classList.contains('open')) {
        closeMenu();
    }
});

// Smooth scroll to top
const backToTopLinks = document.querySelectorAll('a[href="#"]');
backToTopLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});

// Add scroll effects (optional - fade in on scroll)
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe sections for fade-in effect
const sections = document.querySelectorAll('section');
sections.forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(section);
});

// Prevent default behavior on placeholder links
const placeholderLinks = document.querySelectorAll('a[href="#research"], a[href="#residents"], a[href="#archive"], a[href="#projects"], a[href="#resources"], a[href="#editorial"], a[href="#about"], a[href="#support"]');
placeholderLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        const target = link.getAttribute('href');
        const targetSection = document.querySelector(target);

        if (targetSection) {
            e.preventDefault();
            targetSection.scrollIntoView({ behavior: 'smooth' });
        }
    });
});

