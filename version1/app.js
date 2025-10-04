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
            <div class="hero-statement fade-in">
                <h2>An anti-colonial project of Indigenous nihilism, for a non-European anarchy.</h2>
                <p>Regeneración Lab brings together scholars, artists, and activists working at the intersections of
                    Indigenous studies, border politics, and decolonial theory. We create space for research,
                    dialogue, and cultural production that challenges settler-colonial frameworks and imagines
                    otherwise.</p>
            </div>

            <section class="section">
                <h2 class="section-header">Current Projects</h2>
                <div class="projects-grid">
                    <div class="project-card">
                        <div class="project-meta">Active / 2025</div>
                        <h3>Indigenous Border Studies Syllabus</h3>
                        <p>A pop education platform offering thematic modules, readings, films, and community
                            dialogue on Indigenous perspectives on borders, sovereignty, and movement.</p>
                    </div>
                    <div class="project-card">
                        <div class="project-meta">Archive / Ongoing</div>
                        <h3>Community Historical Archive</h3>
                        <p>Digital preservation of tribal histories, maps, archival records, and oral testimonies,
                            created in collaboration with community members.</p>
                    </div>
                    <div class="project-card">
                        <div class="project-meta">Research / 2024-2025</div>
                        <h3>Water Justice & Technology</h3>
                        <p>Exploring the intersections of Indigenous water rights, environmental monitoring, and
                            technological sovereignty in the Southwest.</p>
                    </div>
                    <div class="project-card">
                        <div class="project-meta">Network / 2025</div>
                        <h3>Decolonial Methods Workshop</h3>
                        <p>A series of gatherings and online resources for researchers working to decolonize
                            academic methodologies and knowledge production.</p>
                    </div>
                </div>
            </section>

            <section class="section">
                <h2 class="section-header">Recent Updates</h2>
                <div style="background: white; border-left: 4px solid var(--color-primary-text); padding: 25px; margin-bottom: 20px;">
                    <div class="project-meta">May 15, 2025</div>
                    <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 10px;">
                        New Resident Scholar Announced</h3>
                    <p>We're excited to welcome Dr. Maria Santos as our Summer 2025 resident scholar. Maria will be
                        developing curriculum on Indigenous mapping practices and counter-cartography.</p>
                </div>
                <div style="background: white; border-left: 4px solid var(--color-primary-text); padding: 25px;">
                    <div class="project-meta">April 22, 2025</div>
                    <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 10px;">
                        Border Studies Syllabus Launch</h3>
                    <p>After six months of collaborative development, our Indigenous Border Studies syllabus is now
                        live. Explore modules on sovereignty, migration, and resistance.</p>
                </div>
            </section>

            <div class="support-box">
                <h3>Support Our Work</h3>
                <p style="margin-bottom: 20px;">Regeneración Lab operates through community support and grant
                    funding. Your contribution helps us maintain this platform, support resident scholars, and keep
                    our resources freely accessible.</p>
                <a href="#" class="btn">Contribute</a>
            </div>
        `;
    }

    async loadAboutContent() {
        return `
            <article class="article-content fade-in">
                <div class="article-header">
                    <h1 class="article-title">About Regeneración Lab</h1>
                </div>

                <p><strong>Regeneración—regeneration in Spanish—</strong>signals both a historical continuity and a
                    rupture. Named after the anarchist newspaper published by the Flores Magón brothers from
                    1900-1918, our lab carries forward their spirit of radical critique while centering Indigenous
                    epistemologies often erased in traditional anarchist narratives.</p>

                <p>We are a research collective working at the intersections of Indigenous studies, border politics,
                    anarchist theory, and decolonial practice. Based in the Southwest but connected to networks
                    across Turtle Island and beyond, we create space for scholarship and cultural production that
                    refuses settler-colonial frameworks.</p>

                <h3>Our Approach</h3>

                <p>Regeneración Lab operates outside traditional academic structures. We believe knowledge
                    production should be accountable to communities, not institutions. Our work is grounded in
                    several commitments:</p>

                <div class="quote-block">
                    "Refusal, clarity, sabotage. Photocopy spirit; networked circulation."
                </div>

                <p>We prioritize accessibility—all our resources are freely available. We practice citation politics
                    that center Indigenous and BIPOC scholars. We reject extractive research models. We understand
                    archives not as sites of preservation but as tools for present struggles.</p>

                <h3>History & Context</h3>

                <p>The lab emerged from years of organizing work, from Indigenous Action collectives to water
                    protector camps to university study groups that felt too constrained by academic conventions. We
                    wanted a space that could hold both rigorous theoretical work and urgent practical organizing—a
                    space that understood they're inseparable.</p>

                <p>Like the original Regeneración newspaper, we believe in the power of printed—now digital—matter
                    to circulate ideas, build networks, and coordinate resistance. But we also recognize that
                    anarchism's European origins must be challenged and transformed through Indigenous critique.</p>

                <h3>What We Do</h3>

                <p>Our work takes many forms: hosting resident scholars and artists, developing educational
                    resources like our Indigenous Border Studies syllabus, maintaining community archives, and
                    connecting people across movements and geographies. We're not an academic department or a
                    nonprofit organization—we're a lab, a space for experimentation in decolonial thought and
                    practice.</p>
            </article>
        `;
    }

    async loadResidentsContent() {
        return `
            <div class="fade-in">
                <div class="article-header">
                    <h1 class="article-title">Scholars & Artists in Residence</h1>
                    <p style="font-size: 1.1em; margin-top: 15px;">The residency program supports Indigenous scholars,
                        artists, and organizers developing projects that advance decolonial theory and practice.
                        Residents receive research support, platform space, and community connection.</p>
                </div>

                <div class="nav-tabs">
                    <button class="active" onclick="app.showResidentTab('current')">Current</button>
                    <button onclick="app.showResidentTab('past')">Past</button>
                    <button onclick="app.showResidentTab('apply')">Apply</button>
                </div>

                <div id="current" class="tab-content active">
                    <div class="resident-list">
                        <div class="resident-card">
                            <h4>Dr. Maria Santos</h4>
                            <div class="year">Summer 2025</div>
                            <p>Working on Indigenous mapping practices and counter-cartography as tools for land defense
                                and sovereignty claims.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Jordan Blackwater</h4>
                            <div class="year">Spring-Fall 2025</div>
                            <p>Multimedia artist exploring water memory, ceremony, and environmental violence through
                                video installation and sound.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Dr. Samuel Ortiz</h4>
                            <div class="year">2025</div>
                            <p>Researching border militarization's impact on Indigenous communities and developing
                                community-based documentation methods.</p>
                        </div>
                    </div>
                </div>

                <div id="past" class="tab-content">
                    <div class="resident-list">
                        <div class="resident-card">
                            <h4>Kira Yellowhorse</h4>
                            <div class="year">2024</div>
                            <p>Developed oral history archive project documenting Indigenous resistance to border
                                infrastructure across three generations.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Dr. Rosa Mendez</h4>
                            <div class="year">2024</div>
                            <p>Created bilingual educational materials on Indigenous food sovereignty and seed keeping
                                practices in the borderlands.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Alex Chen-Garcia</h4>
                            <div class="year">2023-2024</div>
                            <p>Mixed-media artist whose residency resulted in exhibition exploring settler colonialism
                                through family archives and speculative fiction.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Dr. Thomas Begay</h4>
                            <div class="year">2023</div>
                            <p>Worked on translation project bringing Diné perspectives into conversation with anarchist
                                theory and mutual aid practices.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Marina Lopez</h4>
                            <div class="year">2023</div>
                            <p>Poet and organizer who developed workshop series on language revitalization as
                                anti-colonial practice.</p>
                        </div>
                        <div class="resident-card">
                            <h4>Dr. David Redhorse</h4>
                            <div class="year">2022-2023</div>
                            <p>Completed research on Indigenous critiques of border abolition movements and developed
                                framework for land-based analysis.</p>
                        </div>
                    </div>
                </div>

                <div id="apply" class="tab-content">
                    <div style="max-width: 720px;">
                        <h3 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.6em; margin-bottom: 20px;">
                            Apply for Residency</h3>

                        <p style="margin-bottom: 20px;">We offer 3-12 month residencies for Indigenous scholars,
                            artists, and organizers. Residents receive research support, workspace, and platform for
                            sharing work. We prioritize projects that:</p>

                        <ul style="margin-left: 30px; margin-bottom: 25px; line-height: 1.8;">
                            <li>Center Indigenous epistemologies and methodologies</li>
                            <li>Connect theory to practice and organizing</li>
                            <li>Challenge settler-colonial frameworks</li>
                            <li>Build connections across movements and geographies</li>
                            <li>Make knowledge accessible beyond academic audiences</li>
                        </ul>

                        <p style="margin-bottom: 25px;"><strong>Application periods:</strong> We review applications on
                            a rolling basis. Next cohort begins Fall 2025.</p>

                        <a href="#" class="btn">Download Application</a>
                    </div>
                </div>
            </div>
        `;
    }

    async loadProjectsContent() {
        return `
            <div class="fade-in">
                <div class="article-header">
                    <h1 class="article-title">Research Projects</h1>
                    <p style="font-size: 1.1em; margin-top: 15px;">Our ongoing research initiatives span Indigenous studies, 
                        border politics, environmental justice, and decolonial methodologies.</p>
                </div>

                <section class="section">
                    <h2 class="section-header">Active Projects</h2>
                    <div class="projects-grid">
                        <div class="project-card">
                            <div class="project-meta">Active / 2025</div>
                            <h3>Indigenous Border Studies Syllabus</h3>
                            <p>A comprehensive educational platform offering thematic modules, readings, films, and community
                                dialogue on Indigenous perspectives on borders, sovereignty, and movement.</p>
                        </div>
                        <div class="project-card">
                            <div class="project-meta">Research / 2024-2025</div>
                            <h3>Water Justice & Technology</h3>
                            <p>Exploring the intersections of Indigenous water rights, environmental monitoring, and
                                technological sovereignty in the Southwest.</p>
                        </div>
                    </div>
                </section>

                <section class="section">
                    <h2 class="section-header">Archive Projects</h2>
                    <div class="projects-grid">
                        <div class="project-card">
                            <div class="project-meta">Archive / Ongoing</div>
                            <h3>Community Historical Archive</h3>
                            <p>Digital preservation of tribal histories, maps, archival records, and oral testimonies,
                                created in collaboration with community members.</p>
                        </div>
                        <div class="project-card">
                            <div class="project-meta">Network / 2025</div>
                            <h3>Decolonial Methods Workshop</h3>
                            <p>A series of gatherings and online resources for researchers working to decolonize
                                academic methodologies and knowledge production.</p>
                        </div>
                    </div>
                </section>
            </div>
        `;
    }

    async loadSupportContent() {
        return `
            <div class="fade-in">
                <div class="article-header">
                    <h1 class="article-title">Support Our Work</h1>
                    <p style="font-size: 1.1em; margin-top: 15px;">Regeneración Lab operates through community support 
                        and grant funding. Your contribution helps us maintain this platform, support resident scholars, 
                        and keep our resources freely accessible.</p>
                </div>

                <div class="support-box" style="margin-top: 40px;">
                    <h3>Ways to Support</h3>
                    <div style="margin-bottom: 30px;">
                        <h4 style="font-family: 'Libre Baskerville', Georgia, serif; font-size: 1.3em; margin-bottom: 15px;">
                            Financial Contributions</h4>
                        <p style="margin-bottom: 20px;">Direct financial support helps us maintain our digital platform, 
                            support resident scholars, and develop new educational resources.</p>
                        <a href="#" class="btn">Make a Contribution</a>
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
                        <a href="#" class="btn">Contact Us</a>
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
