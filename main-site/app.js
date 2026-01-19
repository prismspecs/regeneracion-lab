class RegeneracionApp {
    constructor() {
        this.currentPage = null;
        this.contentCache = new Map();
        this.init();
    }

    init() {
        this.setupNavigation();
        this.loadInitialContent();
        this.setupHistory();
    }

    setupNavigation() {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = e.target.dataset.page;
                this.navigateTo(page);
            });
        });

        // Event delegation for dynamic content links
        document.getElementById('mainContent').addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                if (href && href.startsWith('#detail/')) {
                    e.preventDefault();
                    const detailPage = href.substring('#detail/'.length);
                    this.loadDetailPage(detailPage);
                }
            }
        });
    }

    setupHistory() {
        window.addEventListener('popstate', (e) => {
            if (e.state?.page) {
                this.navigateTo(e.state.page, false);
            } else {
                // Handle hash change or back/forward to a state without state object
                const hash = window.location.hash.slice(1);

                // If the hash corresponds to an element ID on the current page, it's an anchor link.
                // Don't try to load it as a new page.
                if (hash && document.getElementById(hash)) {
                    return;
                }

                if (hash.startsWith('detail/')) {
                    const detailPage = hash.substring('detail/'.length);
                    this.loadDetailPage(detailPage, false);
                    // Ensure state object is present for future navigation
                    history.replaceState({ page: `detail-${detailPage}` }, '', `#detail/${detailPage}`);
                } else if (hash) {
                    this.navigateTo(hash, false);
                } else {
                    this.navigateTo('home', false);
                }
            }
        });
    }

    async navigateTo(page, updateHistory = true) {
        if (page === this.currentPage) return;

        this.showLoading();
        this.updateActiveNav(page);

        try {
            const content = await this.loadPageContent(page);
            this.renderContent(content);
            setTimeout(() => {
                this.scrollToContent();
            }, 0);
            this.setupTabNavigation(); // Setup tabs after content is loaded
            this.currentPage = page;

            if (updateHistory) {
                history.pushState({ page }, '', `#${page}`);
            }
        } catch (error) {
            console.error('Error loading page:', error);
            this.showError();
        } finally {
            this.hideLoading();
        }
    }

    async loadPageContent(page) {
        // Check cache first
        if (this.contentCache.has(page)) {
            return this.contentCache.get(page);
        }

        // Load content from HTML file
        try {
            const response = await fetch(`pages/${page}.html`);
            if (!response.ok) {
                throw new Error(`Failed to load page: ${page}`);
            }
            const content = await response.text();
            
            // Cache the content
            this.contentCache.set(page, content);
            return content;
        } catch (error) {
            throw new Error(`Error loading ${page}: ${error.message}`);
        }
    }

    // Load detail pages (for project/resident details)
    async loadDetailPage(detailPage, updateHistory = true) {
        this.showLoading();
        this.updateActiveNav(null); // Clear active nav state as we are in a detail view
        try {
            const content = await this.loadPageContent(`detail-${detailPage}`);
            this.renderContent(content);
            setTimeout(() => {
                this.scrollToContent();
            }, 0);
            this.setupTabNavigation(); // Setup tabs after content is loaded
            this.currentPage = `detail-${detailPage}`;
            if (updateHistory) {
                history.pushState({ page: `detail-${detailPage}` }, '', `#detail/${detailPage}`);
            }
        } catch (error) {
            console.error('Error loading detail page:', error);
            this.showError();
        } finally {
            this.hideLoading();
        }
    }

    setupTabNavigation() {
        // Setup tab navigation for residents page
        const tabButtons = document.querySelectorAll('.nav-tabs button');
        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const tabName = e.target.dataset.tab;
                if (tabName) {
                    this.showTab(tabName);
                }
            });
        });
    }

    showTab(tabName) {
        const tabs = document.querySelectorAll('.tab-content');
        const buttons = document.querySelectorAll('.nav-tabs button');

        tabs.forEach(tab => tab.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));

        const targetTab = document.getElementById(tabName);
        if (targetTab) {
            targetTab.classList.add('active');
        }

        buttons.forEach(btn => {
            if (btn.dataset.tab === tabName) {
                btn.classList.add('active');
            }
        });
    }

    loadInitialContent() {
        const hash = window.location.hash.slice(1);
        if (hash) {
            if (hash.startsWith('detail/')) {
                const detailPage = hash.substring('detail/'.length);
                this.loadDetailPage(detailPage, false);
            } else {
                this.navigateTo(hash, false);
            }
        }
        // If no hash, don't load any content initially - user must click to navigate
    }

    renderContent(content) {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = content;
        // Load any partials after content is rendered
        this.loadPartials();
    }

    async loadPartials() {
        // Find all partial placeholders and load their content
        const partials = document.querySelectorAll('[data-partial]');
        for (const placeholder of partials) {
            const partialName = placeholder.dataset.partial;
            try {
                const response = await fetch(`partials/${partialName}.html`);
                if (response.ok) {
                    const content = await response.text();
                    placeholder.innerHTML = content;
                }
            } catch (error) {
                console.error(`Error loading partial ${partialName}:`, error);
            }
        }
    }

    updateActiveNav(page) {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.dataset.page === page) {
                link.classList.add('active');
            }
        });
    }

    scrollToContent() {
        const mainContent = document.getElementById('mainContent');
        if (!mainContent) return;

        // Custom smooth scroll with 100ms duration and ~1em buffer
        const buffer = 20; // approx 1em
        const elementTop = mainContent.getBoundingClientRect().top;
        const startPosition = window.pageYOffset;
        const targetPosition = startPosition + elementTop - buffer;
        const distance = targetPosition - startPosition;
        const duration = 200; // ms
        let start = null;

        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            // Simple ease-out for natural feel even at high speed
            const percentage = progress / duration;
            const easedProgress = Math.min(percentage * (2 - percentage), 1);
            
            window.scrollTo(0, startPosition + distance * easedProgress);

            if (progress < duration) {
               window.requestAnimationFrame(step);
            }
        };
        
        window.requestAnimationFrame(step);
    }

    showLoading() {
        document.getElementById('loadingSpinner').style.display = 'block';
    }

    hideLoading() {
        document.getElementById('loadingSpinner').style.display = 'none';
    }

    showError() {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <h2>Error Loading Content</h2>
                <p>There was an error loading the requested page. Please try again.</p>
                <a href="#home" class="btn" onclick="app.navigateTo('home')">Return Home</a>
            </div>
        `;
    }
}

// Initialize the app
const app = new RegeneracionApp();

// Make app globally available for inline event handlers
window.app = app;
