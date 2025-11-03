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
    }

    setupHistory() {
        window.addEventListener('popstate', (e) => {
            const page = e.state?.page || 'home';
            this.navigateTo(page, false);
        });
    }

    async navigateTo(page, updateHistory = true) {
        if (page === this.currentPage) return;

        this.showLoading();
        this.updateActiveNav(page);

        try {
            const content = await this.loadPageContent(page);
            this.renderContent(content);
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

        // Load content based on page
        let content;
        switch (page) {
            case 'home':
                content = await this.loadHomeContent();
                break;
            case 'about':
                content = await this.loadAboutContent();
                break;
            case 'residents':
                content = await this.loadResidentsContent();
                break;
            case 'projects':
                content = await this.loadProjectsContent();
                break;
            case 'students':
                content = await this.loadStudentsContent();
                break;
            case 'support':
                content = await this.loadSupportContent();
                break;
            default:
                throw new Error(`Unknown page: ${page}`);
        }

        // Cache the content
        this.contentCache.set(page, content);
        return content;
    }

    async loadHomeContent() {
        return `
            <div class="hero-image fade-in">
                <div class="quote-overlay">
                    <div class="quote-label">"THEY TRIED TO BURY US BUT THEY DIDN'T KNOW WE WERE SEEDS"<br>
                    —Mexican revolutionary dicho circa. 1910</div>
                </div>
            </div>

            <div class="intro-text fade-in">
                <p style="margin-bottom: 20px; font-size: 0.95em; line-height: 1.7;">Regeneration has become one of the most commercialized and co-opted terms of the 21st century, signally a plethora of ventures that greenwash extractive and colonial land relations, market capitalist products and settler ways of life, and envision alternatives that may delink from one system but reproduce others.</p>
                
                <p style="margin-bottom: 20px; font-size: 0.95em; line-height: 1.7;">However, the term regeneración has been a rebellious political concept, philosophy, and battle cry for over one hundred years grounded in anti-racism, abolition of slavery and mass incarceration, and decolonial resistances rooted in Indigenous and fugitive land relations.</p>
                
                <p style="font-size: 0.95em; line-height: 1.7;">The Regeneración Lab is grounded in this older, otherwise meaning of the term that emerged in the late nineteenth century and inspired the 1910 Mexican revolution. Our regenerative work centers community-based research justice, creative-critical practices, and decolonial praxis.</p>
            </div>

            <section class="section">
                <h2 class="section-header">Projects</h2>
                <div class="projects-grid">
                    <div class="project-card featured-card">
                        <div class="item-badge">ACTIVE</div>
                        <h3>Indigenous Border Studies</h3>
                        <p class="item-meta">Popular Education • 2024-2025</p>
                        <p>A popular education platform offering thematic modules, scholarly research, readings, films, and community dialogue centering Indigenous perspectives on borders, sovereignty, and movement.</p>
                        <a href="#" class="item-link">→ EXPLORE</a>
                    </div>
                    <div class="project-card">
                        <h3>Research Justice</h3>
                        <p class="item-meta">Framework • Ongoing</p>
                        <p>More information about our framework for community-based research praxis featuring a TEDx talk, scholarly publications, and links to resources.</p>
                        <a href="#" class="item-link">→ LEARN MORE</a>
                    </div>
                    <div class="project-card">
                        <div class="item-badge">NEW</div>
                        <h3>O'odham and Yoeme of the Colorado River & Gila River Confluence</h3>
                        <p class="item-meta">Community Research • 2025</p>
                        <p>A community-based research initiative with the descendants of O'odham, Yoeme, and other lower Colorado River region Indigenous communities.</p>
                        <a href="#" class="item-link">→ EXPLORE</a>
                    </div>
                    <div class="project-card">
                        <h3>Reclaiming Homelands: Indigenous North San Diego County</h3>
                        <p class="item-meta">Critical Mission Studies • 2024</p>
                        <p>collaborative project with Luiseño and Kumeyaay youth to recover the Indigenous place-names of the North San Diego County "Mission Trail", part of the Critical Mission Studies Initiative.</p>
                        <a href="#" class="item-link">→ VIEW PROJECT</a>
                    </div>
                    <div class="project-card">
                        <h3>Museum of Us Exhibit - O'odham Land Acknowledgement</h3>
                        <p class="item-meta">Exhibit • Ongoing</p>
                        <p>A response to the impacts of border militarization and deaths of migrants in the Sonoran desert with O'odham perspectives and Indigenous histories of colonial occupation and partition. Up now as part of the exhibit Hostile Terrain '94 at the Museum of Us in San Diego, CA.</p>
                        <a href="#" class="item-link">→ VISIT EXHIBIT</a>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2 class="section-header">Collaborations</h2>
                <div class="projects-grid">
                    <div class="project-card">
                        <h3>Water Justice & Technology</h3>
                        <p class="item-meta">Collaborative Research • Ongoing</p>
                        <p>a gathering place for research, stories, art, and calls to action that critically confront the ways that technology intersects with water.</p>
                        <a href="#" class="item-link">→ VISIT</a>
                    </div>
                    <div class="project-card">
                        <h3>CEIJ: Center for Interdisciplinary Environmental Justice</h3>
                        <p class="item-meta">Research Collective • Active</p>
                        <p>a collective of scholars, artists, scientists, outdoors practitioners, and parents engaged in feminist decolonial science for climate justice.</p>
                        <a href="#" class="item-link">→ LEARN MORE</a>
                    </div>
                    <div class="project-card">
                        <h3>Finding Ceremony</h3>
                        <p class="item-meta">Reparations Initiative • Ongoing</p>
                        <p>a descendant community-controlled reparationist process, restoring the lineages of care, reverence and spiritual memory to the work of caring for our dead and decolonizing museums.</p>
                        <a href="#" class="item-link">→ DISCOVER</a>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2 class="section-header">Recent Updates</h2>
                <div style="background: white; border-left: 4px solid var(--color-secondary-green); padding: 25px; margin-bottom: 20px;">
                    <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 10px;">
                        2025-2026 Reading Group: Critical Temporalities</h3>
                    <p>join the Regeneración Lab and the UCSB English Department Lit & Environment cluster for an interdisciplinary reading group on time, space, and environment.</p>
                    <a href="#" class="item-link" style="display: inline-block; margin-top: 15px;">→ JOIN</a>
                </div>
                <div style="background: white; border-left: 4px solid var(--color-secondary-green); padding: 25px; margin-bottom: 20px;">
                    <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 10px;">
                        2025-2026 Scholar in Residence Announced</h3>
                    <p>We're excited to welcome B.T. Werner as our 2025-2026 scholar in residence! B.T. (they / them) is a subversive physicist conducting transdisciplinary research into the ways that resistance movements affect relationships between societies and the More-Than-Human World.</p>
                    <a href="#" class="item-link" style="display: inline-block; margin-top: 15px;">→ READ MORE</a>
                </div>
                <div style="background: white; border-left: 4px solid var(--color-secondary-green); padding: 25px;">
                    <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 10px;">
                        Indigenous Border Studies - American Quarterly Special Issue Call for Papers</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <a href="#" class="item-link" style="display: inline-block; margin-top: 15px;">→ SUBMIT</a>
                </div>
            </section>

            <div class="support-box">
                <h3>Support Our Work</h3>
                <p style="margin-bottom: 20px;">Regeneración Lab operates through community support and grant funding. Your contribution helps us maintain this platform, support resident scholars, and keep these resources freely accessible.</p>
                <a href="#" class="btn">→ CONTRIBUTE</a>
            </div>
        `;
    }

    async loadAboutContent() {
        return `
            <div class="fade-in">
                <div class="article-header" style="border-bottom: none; padding-bottom: 0;">
                    <h1 class="article-title">About Regeneración Lab</h1>
                </div>

                <article class="article-content">
                    <p>The Regeneración Lab is grounded in the rebellious political concept of regeneración that emerged in the late nineteenth century and inspired the 1910 Mexican revolution. Our work centers community-based research justice, creative-critical practices, and decolonial praxis.</p>

                    <p>Named after the anarchist newspaper published by the Flores Magón brothers from 1900-1918, our lab carries forward their spirit of radical critique while centering Indigenous epistemologies often erased in traditional anarchist narratives.</p>

                    <h3>Our Approach</h3>

                    <p>We create space for research, dialogue, and cultural production that challenges settler-colonial frameworks and imagines otherwise. Our work brings together scholars, artists, and activists working at the intersections of Indigenous studies, border politics, and decolonial theory.</p>

                    <h3>Director</h3>
                </article>

                <section class="section">
                    <div class="project-card" style="max-width: 100%;">
                        <h3>Amrah Salomón</h3>
                        <p class="item-meta">Laboratory Director</p>
                        <p>Amrah Salomón is a scholar, educator, and practitioner of research justice working at the intersections of Indigenous studies, critical race theory, and decolonial methodologies. Their work centers community-based research praxis and the development of frameworks that challenge extractive knowledge production.</p>
                        <p style="margin-top: 15px;">At the Regeneración Lab, Dr. Salomón develops collaborative projects with Indigenous communities, supports resident scholars and artists, and builds educational resources for students and activists engaged in anti-colonial work.</p>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-header">Contact Us</h2>
                    <div style="max-width: 600px;">
                        <form class="contact-form">
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-family: 'IBM Plex Mono', monospace; font-size: 0.85em; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Name</label>
                                <input type="text" name="name" required style="width: 100%; padding: 12px; border: 2px solid var(--color-primary-text); font-family: inherit; font-size: 1em;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-family: 'IBM Plex Mono', monospace; font-size: 0.85em; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Email</label>
                                <input type="email" name="email" required style="width: 100%; padding: 12px; border: 2px solid var(--color-primary-text); font-family: inherit; font-size: 1em;">
                            </div>
                            <div style="margin-bottom: 20px;">
                                <label style="display: block; font-family: 'IBM Plex Mono', monospace; font-size: 0.85em; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Message</label>
                                <textarea name="message" required rows="6" style="width: 100%; padding: 12px; border: 2px solid var(--color-primary-text); font-family: inherit; font-size: 1em; resize: vertical;"></textarea>
                            </div>
                            <button type="submit" class="btn">→ SEND MESSAGE</button>
                        </form>
                    </div>
                </section>
            </div>
        `;
    }

    async loadResidentsContent() {
        return `
            <div class="fade-in">
                <div class="article-header" style="border-bottom: none; padding-bottom: 0;">
                    <h1 class="article-title">Scholars & Artists in Residence</h1>
                </div>

                <div class="nav-tabs">
                    <button class="active" onclick="app.showResidentTab('current')">Current</button>
                    <button onclick="app.showResidentTab('past')">Past</button>
                    <button onclick="app.showResidentTab('apply')">Apply</button>
                </div>

                <div id="current" class="tab-content active">
                    <div class="resident-list">
                        <div class="resident-card">
                            <div class="grid-header">
                                <span class="grid-index">01</span>
                                <span class="grid-type">SCHOLAR</span>
                            </div>
                            <h4>B.T. Werner</h4>
                            <div class="year">2025-2026</div>
                            <p>B.T. (they / them) is a subversive physicist conducting transdisciplinary research into the ways that resistance movements affect relationships between societies and the More-Than-Human World.</p>
                        </div>
                    </div>
                </div>

                <div id="past" class="tab-content">
                    <div class="resident-list">
                        <div class="resident-card">
                            <div class="grid-header">
                                <span class="grid-index">01</span>
                                <span class="grid-type">SCHOLAR</span>
                            </div>
                            <h4>Omar Zahzah</h4>
                            <div class="year">Past Resident</div>
                            <p>Scholar, poet, and activist working at the intersections of Palestinian liberation, Arab diaspora studies, and anti-colonial literature.</p>
                        </div>
                        <div class="resident-card">
                            <div class="grid-header">
                                <span class="grid-index">02</span>
                                <span class="grid-type">SCHOLAR</span>
                            </div>
                            <h4>Lyra Monteiro</h4>
                            <div class="year">Past Resident</div>
                            <p>Public historian and scholar engaged in critical race studies, Indigenous histories, and decolonial museum practices.</p>
                        </div>
                    </div>
                </div>

                <div id="apply" class="tab-content">
                    <div style="max-width: 720px;">
                        <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.6em; margin-bottom: 20px;">
                            Apply for Residency</h3>

                        <p style="margin-bottom: 20px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua:</p>

                        <ul style="margin-left: 30px; margin-bottom: 25px; line-height: 1.8;">
                            <li>Lorem ipsum dolor sit amet</li>
                            <li>Consectetur adipiscing elit</li>
                            <li>Sed do eiusmod tempor incididunt</li>
                            <li>Ut labore et dolore magna aliqua</li>
                            <li>Ut enim ad minim veniam</li>
                        </ul>

                        <p style="margin-bottom: 25px;"><strong>Application periods:</strong> Lorem ipsum dolor sit amet. Consectetur adipiscing elit 2025.</p>

                        <a href="#" class="btn">→ DOWNLOAD APPLICATION</a>
                    </div>
                </div>
            </div>
        `;
    }

    async loadProjectsContent() {
        return `
            <div class="fade-in">
                <section class="section">
                    <h2 class="section-header" style="border-bottom-color: var(--color-earth-brown);">Active Research Projects</h2>
                    <div class="projects-grid">
                        <div class="project-card">
                            <div class="item-badge">ACTIVE</div>
                            <h3>Reclaiming Homelands: Indigenous North San Diego County</h3>
                            <p class="item-meta">Critical Mission Studies • Ongoing</p>
                            <p>Collaborative project with Luiseño and Kumeyaay youth to recover the Indigenous place-names of the North San Diego County "Mission Trail", part of the Critical Mission Studies Initiative.</p>
                            <a href="https://storymaps.arcgis.com/stories/de868339c9a84afb920ead88bf65ccc3" target="_blank" class="item-link">→ VIEW STORYMAP</a>
                        </div>
                        <div class="project-card">
                            <h3>Indigenous Borderlands UCHRI Multi-Campus Faculty Working Group</h3>
                            <p class="item-meta">Research Collective • Active</p>
                            <p>Multi-campus faculty working group exploring Indigenous perspectives on borders, refusal, and fugitivity across the Americas.</p>
                            <a href="https://uchri.org/awards/indigenous-borderlands-refusal-and-fugitivity/" target="_blank" class="item-link">→ LEARN MORE</a>
                        </div>
                        <div class="project-card">
                            <div class="item-badge">NEW</div>
                            <h3>Indigenous Border Studies - American Quarterly Special Issue</h3>
                            <p class="item-meta">Publication • Call for Papers</p>
                            <p>Special issue of American Quarterly focusing on Indigenous Borderlands, bringing together interdisciplinary scholarship on Indigenous border perspectives.</p>
                            <a href="#" class="item-link">→ SUBMIT</a>
                        </div>
                        <div class="project-card">
                            <h3>Indigenous Border Studies Syllabus</h3>
                            <p class="item-meta">Educational Resource • Ongoing</p>
                            <p>A comprehensive syllabus centering Indigenous perspectives on borders, sovereignty, and movement across the Americas.</p>
                            <a href="#" class="item-link">→ ACCESS</a>
                        </div>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-header">Past Projects & Events</h2>
                    <div class="projects-grid">
                        <div class="project-card">
                            <h3>The Indigenous Border/ands Symposium</h3>
                            <p class="item-meta">Symposium • Past Event</p>
                            <p>An exploration of the borderlands from Indigenous perspectives across the Americas, bringing together scholars, artists, and activists.</p>
                            <a href="https://crjucsc.com/calendar/the-indigenous-borderlands-an-exploration-of-the-borderlands-from-indigenous-perspectives-across-the-americas" target="_blank" class="item-link">→ VIEW ARCHIVE</a>
                        </div>
                    </div>
                </section>
            </div>
        `;
    }

    async loadStudentsContent() {
        return `
            <div class="fade-in">
                <div class="article-header" style="border-bottom: none; padding-bottom: 0;">
                    <h1 class="article-title">For Students</h1>
                </div>

                <section class="section">
                    <h2 class="section-header">Critical Temporalities Reading Group</h2>
                    <div class="projects-grid">
                        <div class="project-card">
                            <div class="item-badge">ACTIVE</div>
                            <h3>2025-2026 Reading Group: Critical Temporalities</h3>
                            <p class="item-meta">Reading Group • 2025-2026</p>
                            <p>Join the Regeneración Lab and the UCSB English Department Lit & Environment cluster for an interdisciplinary reading group on time, space, and environment. This collaborative space brings together students, faculty, and community members to engage with critical scholarship on temporality and environmental justice.</p>
                            <a href="#" class="item-link">→ JOIN THE GROUP</a>
                        </div>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-header">Study & Research Opportunities</h2>
                    <article class="article-content">
                        <p>The Regeneración Lab offers opportunities for students to engage in community-based research, decolonial praxis, and collaborative scholarship. We work with undergraduate and graduate students across disciplines who are committed to research justice and anti-colonial frameworks.</p>

                        <h3>Ways to Engage</h3>

                        <p>Students can participate through directed research projects, reading groups, workshops, and collaborative initiatives with our resident scholars. We prioritize hands-on learning that centers community needs and Indigenous epistemologies.</p>

                        <h3>Resources</h3>

                        <p>Access our Indigenous Border Studies Syllabus, research frameworks, and educational materials developed through our community-based projects. These resources are designed to support critical engagement with decolonial scholarship and praxis.</p>
                        
                        <a href="#" class="item-link" style="display: inline-block; margin-top: 20px;">→ CONTACT US ABOUT OPPORTUNITIES</a>
                    </article>
                </section>
            </div>
        `;
    }

    async loadSupportContent() {
        return `
            <div class="fade-in">
                <div class="article-header" style="border-bottom: none; padding-bottom: 0;">
                    <h1 class="article-title">Support Our Work</h1>
                </div>
                
                <p style="font-size: 1.1em; margin-bottom: 40px;">Regeneración Lab operates through community support 
                    and grant funding. Your contribution helps us maintain this platform, support resident scholars, 
                    and keep these resources freely accessible.</p>

                <div class="support-box" style="margin-top: 40px;">
                    <h3>Ways to Support</h3>
                    <div style="margin-bottom: 30px;">
                        <h4 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 15px;">
                            Financial Contributions</h4>
                        <p style="margin-bottom: 20px;">Direct financial support helps us maintain our digital platform, 
                            support resident scholars, and develop new educational resources.</p>
                        <a href="#" class="btn">→ MAKE A CONTRIBUTION</a>
                    </div>

                    <div style="margin-bottom: 30px;">
                        <h4 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 15px;">
                            Share Our Resources</h4>
                        <p style="margin-bottom: 20px;">Help us reach broader audiences by sharing our educational 
                            materials, research, and resident work with your networks.</p>
                    </div>

                    <div>
                        <h4 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 15px;">
                            Collaborate</h4>
                        <p style="margin-bottom: 20px;">We welcome collaboration with scholars, artists, and organizers 
                            working on related projects. Reach out to discuss potential partnerships.</p>
                        <a href="#" class="btn">→ CONTACT US</a>
                    </div>
                </div>
            </div>
        `;
    }

    showResidentTab(tabName) {
        const tabs = document.querySelectorAll('.tab-content');
        const buttons = document.querySelectorAll('.nav-tabs button');

        tabs.forEach(tab => tab.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));

        document.getElementById(tabName).classList.add('active');
        event.target.classList.add('active');
    }

    loadInitialContent() {
        const hash = window.location.hash.slice(1);
        if (hash) {
            this.navigateTo(hash, false);
        }
        // If no hash, don't load any content initially - user must click to navigate
    }

    renderContent(content) {
        const mainContent = document.getElementById('mainContent');
        mainContent.innerHTML = content;
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
                <a href="#" class="btn" onclick="app.navigateTo('home')">Return Home</a>
            </div>
        `;
    }
}

// Initialize the app
const app = new RegeneracionApp();

// Make app globally available for tab functionality
window.app = app;
