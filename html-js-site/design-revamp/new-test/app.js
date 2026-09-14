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
        this.loadPartials().then(() => {
            this.initPretextDrops();
        });
    }

    async initPretextDrops() {
        const dropcaps = document.querySelectorAll('.pretext-dropcap');
        if (dropcaps.length === 0) return;

        try {
            const { prepareWithSegments, layoutNextLine } = await import('https://esm.sh/@chenglou/pretext@0.0.4');

            dropcaps.forEach(p => {
                if (p.hasAttribute('data-pretext-applied')) return;
                p.setAttribute('data-pretext-applied', 'true');

                const text = p.textContent.trim();
                const firstLetter = text[0];
                const restText = text.slice(1).trimStart(); 

                const fontSize = 18;
                const lineHeight = 28;
                const fontString = `${fontSize}px "Inter", -apple-system, sans-serif`;

                const prepared = prepareWithSegments(restText, fontString);

                // Create a container to replace the original text
                const container = document.createElement('div');
                container.style.width = '100%';
                container.style.height = '350px';
                container.style.position = 'relative';
                
                const canvas = document.createElement('canvas');
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.style.display = 'block';
                container.appendChild(canvas);

                p.innerHTML = '';
                p.appendChild(container);

                const ctx = canvas.getContext('2d', { alpha: false });
                let dpr = window.devicePixelRatio || 1;
                let cWidth, cHeight;

                const render = (progressVal) => {
                    ctx.fillStyle = '#2b2628';
                    ctx.fillRect(0, 0, cWidth, cHeight);

                    const baseSize = fontSize;
                    const targetSize = 120;
                    const dropCapSize = baseSize + (targetSize - baseSize) * progressVal;
                    
                    ctx.font = `bold ${dropCapSize}px "Georgia", serif`;
                    ctx.textBaseline = 'top';

                    const dropCapWidth = ctx.measureText(firstLetter).width;
                    const dropCapHeight = dropCapSize * 0.75;

                    ctx.fillStyle = `color-mix(in srgb, #d2691e ${progressVal * 100}%, #e0d9d1)`;
                    ctx.fillText(firstLetter, 0, - (dropCapSize * 0.05));

                    ctx.font = fontString;
                    ctx.fillStyle = '#e0d9d1';
                    ctx.textBaseline = 'top';

                    let cursor = { segmentIndex: 0, graphemeIndex: 0 };
                    let y = 0;

                    const currentPadding = 24 * progressVal;

                    while (true) {
                        const isBeside = y + (lineHeight * 0.4) < dropCapHeight;
                        let availableWidth = cWidth;
                        let xOffset = 0;

                        if (isBeside && dropCapWidth > 0 && progressVal > 0.01) {
                            xOffset = dropCapWidth + currentPadding;
                            availableWidth = cWidth - xOffset;
                        }

                        if (availableWidth < 30) availableWidth = 30;

                        const line = layoutNextLine(prepared, cursor, availableWidth);
                        if (line === null) break;

                        ctx.fillText(line.text, xOffset, y);
                        cursor = line.end;
                        y += lineHeight;

                        if (y > cHeight) break;
                    }
                };

                const resizeCanvas = () => {
                    const rect = container.getBoundingClientRect();
                    cWidth = rect.width;
                    cHeight = rect.height;
                    canvas.width = cWidth * dpr;
                    canvas.height = cHeight * dpr;
                    ctx.scale(dpr, dpr);
                    render(progress);
                };

                window.addEventListener('resize', resizeCanvas);

                let progress = 0;
                let animationFrameId = null;
                let startProgress = 0, targetProgress = 0, currentProgress = 0, startTime = 0;
                const DURATION = 1000;

                const animate = (time) => {
                    if (!startTime) startTime = time;
                    const elapsed = time - startTime;
                    let t = Math.min(elapsed / DURATION, 1);
                    t = 1 - (--t) * t * t * t; // easeOutQuart

                    currentProgress = startProgress + (targetProgress - startProgress) * t;
                    progress = currentProgress;
                    render(progress);

                    if (elapsed < DURATION) {
                        animationFrameId = requestAnimationFrame(animate);
                    } else {
                        currentProgress = targetProgress;
                        render(currentProgress);
                        animationFrameId = null;
                    }
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            if (targetProgress !== 1) {
                                startProgress = currentProgress;
                                targetProgress = 1;
                                startTime = 0;
                                if (!animationFrameId) animationFrameId = requestAnimationFrame(animate);
                            }
                        } else {
                            if (targetProgress !== 0) {
                                startProgress = currentProgress;
                                targetProgress = 0;
                                startTime = 0;
                                if (!animationFrameId) animationFrameId = requestAnimationFrame(animate);
                            }
                        }
                    });
                }, { threshold: 0.5 });

                document.fonts.ready.then(() => {
                    resizeCanvas();
                    observer.observe(container);
                });
            });
        } catch(e) {
            console.error("Failed to load or apply pretext", e);
        }
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
