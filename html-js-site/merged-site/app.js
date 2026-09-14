class RegeneracionApp {
    constructor() {
        this.currentPage = null;
        this.contentCache = new Map();

        // Pretext state
        this.pretext = {
            prepared: null,
            canvas: null,
            ctx: null,
            progress: 0,
            animationFrameId: null,
            targetProgress: 0,
            currentProgress: 0,
            startTime: 0,
            duration: 1000,
            firstLetter: '',
            restText: '',
            fontSize: 18,
            lineHeight: 28,
            fontString: '18px "Inter", -apple-system, sans-serif'
        };

        this.init();
    }

    init() {
        this.setupNavigation();
        this.loadInitialContent();
        this.setupHistory();
        window.addEventListener('resize', () => {
            if (this.pretext.canvas) this.resizePretextCanvas();
        });
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

        // Event delegation for detail links
        document.getElementById('mainContent').addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                if (href && href.startsWith('#detail/')) {
                    e.preventDefault();
                    const detailPage = href.substring(8);
                    this.loadDetailPage(detailPage);
                }
            }

            // Handle person-card clicks
            const card = e.target.closest('.person-card');
            if (card) {
                const href = card.dataset.href;
                if (href && href.startsWith('#detail/')) {
                    e.preventDefault();
                    const detailPage = href.substring(8);
                    this.loadDetailPage(detailPage);
                }
            }
        });
    }

    setupHistory() {
        window.addEventListener('popstate', (e) => {
            if (e.state?.page) {
                if (e.state.page.startsWith('detail-')) {
                    this.loadDetailPage(e.state.page.substring(7), false);
                } else {
                    this.navigateTo(e.state.page, false);
                }
            } else {
                const hash = window.location.hash.slice(1);
                if (hash && document.getElementById(hash)) return;

                if (hash.startsWith('detail/')) {
                    this.loadDetailPage(hash.substring(7), false);
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
            this.setupTabNavigation();
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
        if (this.contentCache.has(page)) {
            return this.contentCache.get(page);
        }

        try {
            const response = await fetch(`pages/${page}.html`);
            if (!response.ok) throw new Error(`Failed to load page: ${page}`);
            const content = await response.text();
            this.contentCache.set(page, content);
            return content;
        } catch (error) {
            throw new Error(`Error loading ${page}: ${error.message}`);
        }
    }

    async loadDetailPage(detailPage, updateHistory = true) {
        this.showLoading();
        this.updateActiveNav(null);
        try {
            const content = await this.loadPageContent(`detail-${detailPage}`);
            this.renderContent(content);
            setTimeout(() => {
                this.scrollToContent();
            }, 0);
            this.setupTabNavigation();
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
        const tabButtons = document.querySelectorAll('.nav-tabs button');
        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const tabName = e.target.dataset.tab;
                if (tabName) this.showTab(tabName);
            });
        });
    }

    showTab(tabName) {
        const tabs = document.querySelectorAll('.tab-content');
        const buttons = document.querySelectorAll('.nav-tabs button');
        tabs.forEach(tab => tab.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));

        const targetTab = document.getElementById(tabName);
        if (targetTab) targetTab.classList.add('active');
        buttons.forEach(btn => {
            if (btn.dataset.tab === tabName) btn.classList.add('active');
        });
    }

    loadInitialContent() {
        const hash = window.location.hash.slice(1);
        if (hash) {
            if (hash.startsWith('detail/')) {
                this.loadDetailPage(hash.substring(7), false);
            } else {
                this.navigateTo(hash, false);
            }
        } else {
            this.navigateTo('home', false);
        }
    }

    renderContent(content) {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = content;
        this.loadPartials().then(() => {
            this.initPretext();
            this.initCardBackgrounds();
        });
    }

    async loadPartials() {
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

    /* =========================================
       Pretext Drop Cap Animation
       ========================================= */
    async initPretext() {
        const canvas = document.getElementById('textCanvas');
        const container = document.getElementById('pretextContainer');
        if (!canvas || !container) {
            this.pretext.canvas = null;
            return;
        }

        const { prepareWithSegments, layoutNextLine } = await import('https://esm.sh/@chenglou/pretext@0.0.4');
        this.pretext.layoutNextLine = layoutNextLine;

        const text = "A gathering place for research, stories, art, and calls to action that critically confront the ways that technology intersects with water. Water justice and indigenous studies require a regenerative approach. The Spanish term regeneración has been a rebellious political concept, philosophy, and battle cry for over a hundred years. In this context, regeneración developed as a rejection of eugenics that connected movements engaged in anti-racism, abolition of slavery and mass incarceration, and decolonial resistances rooted in Indigenous and fugitive land relations. As the Drop Cap swells, Pretext seamlessly calculates the boundaries for every frame and wraps this text perfectly around the new obstacle without a single DOM layout thrash.";

        this.pretext.firstLetter = text[0];
        this.pretext.restText = text.slice(1).trimStart();
        this.pretext.canvas = canvas;
        this.pretext.container = container;
        this.pretext.ctx = canvas.getContext('2d', { alpha: false });
        this.pretext.prepared = prepareWithSegments(this.pretext.restText, this.pretext.fontString);

        this.resizePretextCanvas();
        this.setupPretextObserver();
    }

    resizePretextCanvas() {
        if (!this.pretext.canvas) return;
        const dpr = window.devicePixelRatio || 1;
        const rect = this.pretext.container.getBoundingClientRect();
        this.pretext.cWidth = rect.width;
        this.pretext.cHeight = rect.height;
        this.pretext.canvas.width = this.pretext.cWidth * dpr;
        this.pretext.canvas.height = this.pretext.cHeight * dpr;
        this.pretext.ctx.scale(dpr, dpr);
        this.renderPretext(this.pretext.progress);
    }

    renderPretext(animationVal) {
        const { ctx, cWidth, cHeight, firstLetter, fontString, lineHeight, fontSize, prepared, layoutNextLine } = this.pretext;
        if (!ctx || !layoutNextLine) return;

        // Use paper color as background
        ctx.fillStyle = '#faf9f6';
        ctx.fillRect(0, 0, cWidth, cHeight);

        const dropCapSize = fontSize + (140 - fontSize) * animationVal;
        ctx.font = `bold ${dropCapSize}px "Instrument Serif", serif`;
        ctx.textBaseline = 'top';

        const dropCapWidth = ctx.measureText(firstLetter).width;
        const dropCapHeight = dropCapSize * 0.75;

        // Drop cap color transitions from ink to river blue
        ctx.fillStyle = `color-mix(in srgb, oklch(55% 0.12 240) ${animationVal * 100}%, oklch(20% 0.02 260))`;
        ctx.fillText(firstLetter, 0, -(dropCapSize * 0.05));

        ctx.font = fontString;
        ctx.fillStyle = 'oklch(20% 0.02 260)';
        ctx.textBaseline = 'top';

        let cursor = { segmentIndex: 0, graphemeIndex: 0 };
        let y = 0;
        const padding = 24 * animationVal;

        while (true) {
            const isBeside = y + (lineHeight * 0.4) < dropCapHeight;
            let xOffset = 0;
            let availableWidth = cWidth;

            if (isBeside && dropCapWidth > 0 && animationVal > 0.01) {
                xOffset = dropCapWidth + padding;
                availableWidth = cWidth - xOffset;
            }

            const line = layoutNextLine(prepared, cursor, Math.max(availableWidth, 30));
            if (line === null) break;

            ctx.fillText(line.text, xOffset, y);
            cursor = line.end;
            y += lineHeight;
            if (y > cHeight) break;
        }
    }

    setupPretextObserver() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                this.animatePretext(entry.isIntersecting ? 1 : 0);
            });
        }, { threshold: 0.2 });
        observer.observe(this.pretext.container);
    }

    animatePretext(target) {
        if (this.pretext.targetProgress === target) return;
        this.pretext.startProgress = this.pretext.currentProgress;
        this.pretext.targetProgress = target;
        this.pretext.startTime = 0;

        const animate = (time) => {
            if (!this.pretext.startTime) this.pretext.startTime = time;
            const elapsed = time - this.pretext.startTime;
            let t = Math.min(elapsed / this.pretext.duration, 1);
            t = 1 - (--t) * t * t * t;

            this.pretext.currentProgress = this.pretext.startProgress + (this.pretext.targetProgress - this.pretext.startProgress) * t;
            this.pretext.progress = this.pretext.currentProgress;
            this.renderPretext(this.pretext.progress);

            if (elapsed < this.pretext.duration) {
                this.pretext.animationFrameId = requestAnimationFrame(animate);
            } else {
                this.pretext.animationFrameId = null;
            }
        };

        if (this.pretext.animationFrameId) cancelAnimationFrame(this.pretext.animationFrameId);
        this.pretext.animationFrameId = requestAnimationFrame(animate);
    }

    /* =========================================
       Project Card Hover Background Images
       ========================================= */
    initCardBackgrounds() {
        const cards = document.querySelectorAll('.editorial-card[data-bg]');
        cards.forEach(card => {
            const bgUrl = card.dataset.bg;
            if (bgUrl) {
                card.style.setProperty('--card-bg', `url('${bgUrl}')`);
            }
        });
    }

    /* =========================================
       Navigation & UI
       ========================================= */
    updateActiveNav(page) {
        const navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.dataset.page === page) link.classList.add('active');
        });
    }

    scrollToContent() {
        const mainContent = document.getElementById('mainContent');
        if (!mainContent) return;
        const buffer = 20;
        const elementTop = mainContent.getBoundingClientRect().top;
        const startPosition = window.pageYOffset;
        const targetPosition = startPosition + elementTop - buffer;
        const distance = targetPosition - startPosition;
        const duration = 200;
        let start = null;

        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = timestamp - start;
            const percentage = progress / duration;
            const easedProgress = Math.min(percentage * (2 - percentage), 1);
            window.scrollTo(0, startPosition + distance * easedProgress);
            if (progress < duration) window.requestAnimationFrame(step);
        };
        window.requestAnimationFrame(step);
    }

    showLoading() {
        const spinner = document.getElementById('loadingSpinner');
        if (spinner) spinner.style.display = 'block';
    }

    hideLoading() {
        const spinner = document.getElementById('loadingSpinner');
        if (spinner) spinner.style.display = 'none';
    }

    showError() {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <h2 style="font-family: var(--font-display); font-size: 2.5rem;">Error Loading Content</h2>
                <p>There was an error loading the requested page. Please try again.</p>
                <a href="#home" class="editorial-btn" onclick="app.navigateTo('home')">Return Home</a>
            </div>
        `;
    }
}

const app = new RegeneracionApp();
window.app = app;
