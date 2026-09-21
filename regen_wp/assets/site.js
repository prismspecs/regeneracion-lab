// Shared site behavior: footer year, smooth anchors, mobile nav drawer, dropcap animation.
(function () {
    var year = document.getElementById('currentYear');
    if (year) year.textContent = new Date().getFullYear();

    // Smooth in-page anchor navigation (also drives every "Back to top" link).
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var id = this.getAttribute('href');
            if (id === '#' || id === '#top') {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }
            var target = document.querySelector(id);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    var burger = document.getElementById('slimTopbarBurger');
    var drawer = document.getElementById('mobileNavDrawer');
    // Exposed so page scripts (e.g. the homepage hero) can close the drawer.
    window.setMobileMenuOpen = function () {};
    if (burger && drawer) initMobileNav(burger, drawer);

    function initMobileNav(burger, drawer) {

        function setOpen(open) {
            burger.classList.toggle('is-open', open);
            drawer.classList.toggle('is-open', open);
            burger.setAttribute('aria-expanded', String(open));
            burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
            drawer.setAttribute('aria-hidden', String(!open));
            document.body.classList.toggle('menu-open', open);
        }

        window.setMobileMenuOpen = setOpen;

        burger.addEventListener('click', function (e) {
            e.stopPropagation();
            setOpen(!burger.classList.contains('is-open'));
        });
        drawer.querySelectorAll('.mobile-nav-link').forEach(function (link) {
            link.addEventListener('click', function () { setOpen(false); });
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && burger.classList.contains('is-open')) setOpen(false);
        });
        drawer.addEventListener('click', function (e) {
            if (e.target === drawer) setOpen(false);
        });
    }

    // Dropcap animate-in feature (built as a configurable option)
    // Can be configured globally, via URL query parameter (?dropcap=animate or ?dropcap=static),
    // or toggled dynamically via window.toggleDropcapAnimation(true|false).
    const ENABLE_DROPCAP_ANIMATION = true;

    function initDropcapAnimation() {
        const params = new URLSearchParams(window.location.search);
        const dropcapParam = params.get('dropcap');
        const shouldAnimate = dropcapParam === 'static' ? false : (dropcapParam === 'animate' ? true : ENABLE_DROPCAP_ANIMATION);

        if (!shouldAnimate) {
            document.body.classList.add('no-dropcap-animation');
            return;
        }

        const dropcaps = document.querySelectorAll('.dropcap-lead, .dropcap-paragraph');
        if (!dropcaps.length) return;

        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('dropcap-grown');
                        }, 200);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.15,
                rootMargin: '0px 0px -30px 0px'
            });

            dropcaps.forEach(p => observer.observe(p));
        } else {
            setTimeout(() => {
                dropcaps.forEach(p => p.classList.add('dropcap-grown'));
            }, 250);
        }

        // Clicking the letter replays the animation
        dropcaps.forEach(p => {
            const letter = p.querySelector('.dropcap-letter');
            if (letter) {
                letter.addEventListener('click', () => {
                    p.classList.remove('dropcap-grown');
                    setTimeout(() => p.classList.add('dropcap-grown'), 80);
                });
            }
        });
    }

    window.toggleDropcapAnimation = function(enable) {
        document.body.classList.toggle('no-dropcap-animation', !enable);
        document.querySelectorAll('.dropcap-lead, .dropcap-paragraph').forEach(p => p.classList.toggle('dropcap-grown', enable));
    };

    initDropcapAnimation();
})();
