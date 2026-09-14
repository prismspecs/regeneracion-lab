(function () {
    const container = document.getElementById('mainContent');
    const spinner = document.getElementById('loadingSpinner');
    if (!container) return;

    const showSpinner = () => { if (spinner) spinner.style.display = 'block'; };
    const hideSpinner = () => { if (spinner) spinner.style.display = 'none'; };

    const executeScripts = (scripts) => {
        scripts.forEach((oldScript) => {
            const s = document.createElement('script');
            if (oldScript.src) {
                s.src = oldScript.src;
            } else {
                s.textContent = oldScript.textContent;
            }
            if (oldScript.type) {
                s.type = oldScript.type;
            }
            document.body.appendChild(s);
            document.body.removeChild(s);
        });
    };

    const sameOrigin = (url) => {
        try {
            const u = new URL(url, window.location.href);
            return u.origin === window.location.origin;
        } catch (e) {
            return false;
        }
    };

    const shouldHandle = (event, link) => {
        if (!link) return false;
        if (link.target && link.target !== '_self') return false;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return false;
        if (link.hasAttribute('download')) return false;
        
        const href = link.getAttribute('href');
        if (!href || href.startsWith('#')) return false;
        if (!sameOrigin(href)) return false;

        // Exclude static files and WordPress admin/login pages
        const path = link.pathname || '';
        if (/\.(pdf|zip|docx?|xlsx?|pptx?|txt|csv|png|jpe?g|gif|svg|mp[34]|wav)$/i.test(path)) return false;
        if (path.includes('/wp-admin') || path.includes('/wp-login.php')) return false;

        return true;
    };

    const replaceContent = (nextDoc) => {
        const nextMain = nextDoc.querySelector('#mainContent');
        if (!nextMain) return false;

        // Clean up any custom modals that were moved to the body to prevent duplicates
        document.querySelectorAll('body > .custom-modal').forEach(modal => modal.remove());

        // Update nav active states by swapping the nav container children if found
        const nextNav = nextDoc.querySelector('.site-nav');
        const currentNav = document.querySelector('.site-nav');
        if (nextNav && currentNav) {
            currentNav.replaceChildren(...nextNav.childNodes);
        }

        const scripts = Array.from(nextMain.querySelectorAll('script'));
        const clone = nextMain.cloneNode(true);
        clone.querySelectorAll('script').forEach((s) => s.remove());
        container.replaceChildren(...clone.childNodes);
        executeScripts(scripts);
        return true;
    };

    const loadPage = async (url, push) => {
        showSpinner();
        try {
            const resp = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!resp.ok) throw new Error('Fetch failed');
            const text = await resp.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(text, 'text/html');
            const title = doc.querySelector('title');
            const swapped = replaceContent(doc);
            if (swapped) {
                if (title) document.title = title.textContent;
                if (push) {
                    window.history.pushState({ url }, '', url);
                }
                setupModals();
                window.scrollTo({ top: 0, behavior: 'auto' });
                hideSpinner();
                return;
            }
        } catch (err) {
            console.error('PJAX swap failed, falling back', err);
        }
        window.location.href = url;
    };

    window.addEventListener('popstate', (e) => {
        const url = (e.state && e.state.url) ? e.state.url : window.location.href;
        loadPage(url, false);
    });

    // Seed initial history state
    if (!window.history.state) {
        window.history.replaceState({ url: window.location.href }, '', window.location.href);
    }

    // Modal Initializer and Builder (Gutenberg-friendly)
    const setupModals = () => {
        const modals = document.querySelectorAll('.custom-modal');
        modals.forEach(modal => {
            // 1. Ensure backdrop exists
            if (!modal.querySelector('.custom-modal__backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'custom-modal__backdrop';
                modal.insertBefore(backdrop, modal.firstChild);
            }
            
            // 2. Ensure close button exists inside dialog
            const dialog = modal.querySelector('.custom-modal__dialog');
            if (dialog && !dialog.querySelector('.custom-modal__close-btn')) {
                const closeBtn = document.createElement('button');
                closeBtn.className = 'custom-modal__close-btn';
                closeBtn.innerHTML = '&times;';
                closeBtn.setAttribute('aria-label', 'Close modal');
                closeBtn.setAttribute('type', 'button');
                closeBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    modal.classList.remove('is-open');
                    document.body.style.overflow = '';
                });
                dialog.insertBefore(closeBtn, dialog.firstChild);
            }
        });
    };

    setupModals();

    // Global Event Delegation for Custom Modals
    document.addEventListener('click', (e) => {
        // Handle Open Modal Button (handles data-open-modal attribute)
        const openBtn = e.target.closest('[data-open-modal]');
        if (openBtn) {
            e.preventDefault();
            const modalId = openBtn.getAttribute('data-open-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                // Move modal to body to break out of stacking context / transform bounds
                if (modal.parentNode !== document.body) {
                    document.body.appendChild(modal);
                }
                modal.classList.add('is-open');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
                const dialog = modal.querySelector('.custom-modal__dialog');
                if (dialog) {
                    dialog.setAttribute('tabindex', '-1');
                    dialog.focus();
                }
            }
            return;
        }

        // Support links (modal anchor links or PJAX page transitions)
        const link = e.target.closest('a');
        if (link) {
            const href = link.getAttribute('href');
            
            // Handle modal anchor links (e.g., href="#symposium-program")
            if (href && href.startsWith('#')) {
                const targetId = href.substring(1);
                const modal = document.getElementById(targetId);
                if (modal && modal.classList.contains('custom-modal')) {
                    e.preventDefault();
                    // Move modal to body to break out of stacking context / transform bounds
                    if (modal.parentNode !== document.body) {
                        document.body.appendChild(modal);
                    }
                    modal.classList.add('is-open');
                    document.body.style.overflow = 'hidden';
                    const dialog = modal.querySelector('.custom-modal__dialog');
                    if (dialog) {
                        dialog.setAttribute('tabindex', '-1');
                        dialog.focus();
                    }
                }
                return;
            }

            // Handle PJAX page transitions
            if (shouldHandle(e, link)) {
                e.preventDefault();
                
                // Close mobile menu if open
                const nav = document.getElementById('siteNav');
                const btn = document.querySelector('.nav-toggle');
                if (nav) nav.classList.remove('is-open');
                if (btn) {
                    btn.classList.remove('is-open');
                    btn.setAttribute('aria-expanded', 'false');
                }

                loadPage(link.href, true);
            }
        }

        // Handle Close Trigger (Backdrop clicks or elements with close-modal class)
        const isBackdrop = e.target.classList.contains('custom-modal__backdrop');
        const closeBtn = e.target.closest('.close-modal') || e.target.closest('[data-close-modal]');
        if (isBackdrop || closeBtn) {
            e.preventDefault();
            const modal = e.target.closest('.custom-modal');
            if (modal) {
                modal.classList.remove('is-open');
                document.body.style.overflow = ''; // Restore scrolling
            }
        }
    });

    // Support Escape key to close modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.custom-modal.is-open');
            if (openModal) {
                openModal.classList.remove('is-open');
                document.body.style.overflow = '';
            }
        }
    });
})();
