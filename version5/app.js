// ===== CATALOG DATA =====
const catalogData = [
    {
        id: '00-01',
        title: 'Indigenous Sovereignty and Border Politics',
        author: 'Klee Benally',
        type: 'research',
        year: '2024',
        description: 'An examination of how Indigenous communities navigate and resist colonial borders imposed across traditional territories. This research explores sovereignty claims, movement rights, and the ongoing impacts of border militarization on Indigenous peoples.',
        tags: ['borders', 'sovereignty', 'indigenous studies', 'colonialism']
    },
    {
        id: '00-02',
        title: 'Decolonial Methodologies in Practice',
        author: 'Collective Work',
        type: 'essay',
        year: '2024',
        description: 'A collection of essays exploring practical applications of decolonial research methods. Contributors examine how to center Indigenous epistemologies while conducting ethical, community-accountable research.',
        tags: ['methodology', 'decolonial', 'research', 'epistemology']
    },
    {
        id: '00-03',
        title: 'Nuclear Colonialism and Uranium Mining Resistance',
        author: 'Leona Morgan',
        type: 'article',
        year: '2024',
        description: 'Documenting the ongoing struggle against uranium mining and nuclear colonialism on Diné lands. This article examines environmental racism, health impacts, and community-led resistance through Diné No Nukes and Haul No! organizing.',
        tags: ['nuclear justice', 'environmental', 'resistance', 'uranium mining']
    },
    {
        id: '00-04',
        title: 'Regeneración: Historical Archive 1900-1918',
        author: 'Flores Magón Brothers',
        type: 'archive',
        year: '1900-1918',
        description: 'Digital archive of the anarchist newspaper Regeneración, published by Ricardo and Enrique Flores Magón. These materials document radical Indigenous and mestizo resistance to the Díaz dictatorship and provide crucial context for understanding anarchist organizing in Mexico.',
        tags: ['anarchism', 'history', 'mexico', 'resistance', 'archive']
    },
    {
        id: '00-05',
        title: 'Land Back: Territory and Decolonization',
        author: 'Multiple Contributors',
        type: 'book',
        year: '2023',
        description: 'A comprehensive examination of Land Back movements across Turtle Island. Contributors explore the historical context, contemporary organizing strategies, and the relationship between land return and decolonization.',
        tags: ['land back', 'decolonization', 'territory', 'organizing']
    },
    {
        id: '00-06',
        title: 'Land Defense and Sacred Site Protection',
        author: 'Louise Benally',
        type: 'research',
        year: '2024',
        description: 'Research exploring community-based organizing for land defense and protection of sacred sites. Documents decades of resistance through Protect the Peaks and Indigenous Action, centering traditional governance and Indigenous autonomy.',
        tags: ['land defense', 'sacred sites', 'organizing', 'resistance']
    },
    {
        id: '00-07',
        title: 'Beyond Multiculturalism: Indigenous Critique',
        author: 'Dr. Rosa Mendez',
        type: 'essay',
        year: '2023',
        description: 'A critical analysis of multiculturalism as a settler colonial framework that obscures Indigenous sovereignty claims and land theft. The essay proposes alternative frameworks centered on treaty relationships and repatriation.',
        tags: ['critique', 'multiculturalism', 'settler colonialism', 'sovereignty']
    },
    {
        id: '00-08',
        title: 'Language Revitalization as Anti-Colonial Practice',
        author: 'Marina Lopez',
        type: 'article',
        year: '2023',
        description: 'Examining language revitalization efforts as acts of resistance against linguistic imperialism. The article centers Indigenous-led language programs and their connection to broader decolonization movements.',
        tags: ['language', 'revitalization', 'decolonization', 'education']
    },
    {
        id: '00-09',
        title: 'Indigenous Anarchism: A Critical Reader',
        author: 'Various Authors',
        type: 'book',
        year: '2024',
        description: 'A collection of writings that explore the intersections and tensions between Indigenous political traditions and anarchist theory. Contributors examine how Indigenous critiques transform and challenge European anarchist thought.',
        tags: ['anarchism', 'indigenous', 'political theory', 'critique']
    },
    {
        id: '00-10',
        title: 'Oral Histories: Border Resistance',
        author: 'Kira Yellowhorse',
        type: 'archive',
        year: '2024',
        description: 'A collection of oral histories documenting three generations of Indigenous resistance to border infrastructure. These testimonies center community knowledge and lived experience of border militarization.',
        tags: ['oral history', 'borders', 'resistance', 'testimony']
    },
    {
        id: '00-11',
        title: 'Seeds of Sovereignty: Indigenous Food Systems',
        author: 'Dr. Thomas Begay',
        type: 'research',
        year: '2023',
        description: 'Research examining the relationship between seed keeping, food sovereignty, and Indigenous self-determination. The study documents traditional agricultural practices and their role in resisting corporate food systems.',
        tags: ['food sovereignty', 'agriculture', 'seeds', 'self-determination']
    },
    {
        id: '00-12',
        title: 'Refusal as Political Practice',
        author: 'Dr. Maya Redfeather',
        type: 'essay',
        year: '2024',
        description: 'An exploration of refusal as a form of Indigenous political agency. The essay examines how saying "no" to colonial impositions—whether research, resource extraction, or governance—constitutes a powerful assertion of sovereignty.',
        tags: ['refusal', 'political theory', 'sovereignty', 'agency']
    }
];

// ===== APP CLASS =====
class RegeneracionApp {
    constructor() {
        this.currentFilter = 'all';
        this.activeDetailId = null;
        this.init();
    }

    init() {
        this.setupNavigation();
        this.setupScrollEffects();
        this.setupArchive();
        this.setupIntersectionObserver();
        this.renderCatalog();
    }

    // ===== NAVIGATION =====
    setupNavigation() {
        const menuBtn = document.getElementById('menuBtn');
        const closeNav = document.getElementById('closeNav');
        const navOverlay = document.getElementById('navOverlay');
        const navItems = document.querySelectorAll('.nav-item');

        menuBtn.addEventListener('click', () => this.openMenu());
        closeNav.addEventListener('click', () => this.closeMenu());

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const target = item.getAttribute('href');
                this.closeMenu();
                setTimeout(() => {
                    document.querySelector(target)?.scrollIntoView({
                        behavior: 'smooth'
                    });
                }, 400);
            });
        });

        // Smooth scroll for all internal links
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });

        // Close menu on escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (navOverlay.classList.contains('open')) {
                    this.closeMenu();
                }
                if (this.activeDetailId) {
                    this.closeDetailPanel();
                }
            }
        });
    }

    openMenu() {
        const navOverlay = document.getElementById('navOverlay');
        navOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    closeMenu() {
        const navOverlay = document.getElementById('navOverlay');
        navOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    // ===== SCROLL EFFECTS =====
    setupScrollEffects() {
        const siteHeader = document.getElementById('siteHeader');
        const headerLogo = document.getElementById('headerLogo');
        const heroSection = document.querySelector('.hero-section');

        function handleScroll() {
            const scrollPosition = window.pageYOffset;
            const heroHeight = heroSection.offsetHeight;

            // Update header background
            if (scrollPosition > 100) {
                siteHeader.classList.add('scrolled');
            } else {
                siteHeader.classList.remove('scrolled');
            }

            // Show/hide logo
            if (scrollPosition > heroHeight - 200) {
                headerLogo.classList.add('visible');
            } else {
                headerLogo.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Initial call
    }

    // ===== INTERSECTION OBSERVER =====
    setupIntersectionObserver() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe sections
        const sections = document.querySelectorAll(
            '.about-section, .residents-section, .projects-section, ' +
            '.archive-section, .stories-section, .support-section'
        );

        sections.forEach(section => {
            observer.observe(section);
        });
    }

    // ===== ARCHIVE FUNCTIONALITY =====
    setupArchive() {
        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                this.handleFilter(btn.dataset.filter);
            });
        });

        // Close detail panel
        const closeDetail = document.getElementById('closeDetail');
        if (closeDetail) {
            closeDetail.addEventListener('click', () => {
                this.closeDetailPanel();
            });
        }
    }

    handleFilter(filter) {
        this.currentFilter = filter;

        // Update active button
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });

        // Re-render catalog
        this.renderCatalog();
    }

    getFilteredData() {
        if (this.currentFilter === 'all') {
            return catalogData;
        }
        return catalogData.filter(item => item.type === this.currentFilter);
    }

    renderCatalog() {
        const catalogGrid = document.getElementById('catalogGrid');
        if (!catalogGrid) return;

        const filteredData = this.getFilteredData();

        if (filteredData.length === 0) {
            catalogGrid.innerHTML = `
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 20px;">
                    <h3 style="font-family: var(--font-serif); font-size: 1.5em; margin-bottom: 10px;">
                        No items found
                    </h3>
                    <p style="color: var(--color-text-light);">Try selecting a different filter</p>
                </div>
            `;
            return;
        }

        catalogGrid.innerHTML = filteredData.map(item => `
            <div class="catalog-item" data-id="${item.id}">
                <div class="item-index">${item.id}</div>
                <h3 class="item-title">${item.title}</h3>
                <div class="item-author">${item.author}</div>
                <div class="item-type">${item.type}</div>
            </div>
        `).join('');

        // Add click listeners
        catalogGrid.querySelectorAll('.catalog-item').forEach(item => {
            item.addEventListener('click', () => {
                this.showDetail(item.dataset.id);
            });
        });
    }

    showDetail(id) {
        const item = catalogData.find(i => i.id === id);
        if (!item) return;

        this.activeDetailId = id;

        const detailContent = document.getElementById('detailContent');
        const detailPanel = document.getElementById('detailPanel');

        detailContent.innerHTML = `
            <div class="detail-index">${item.id}</div>
            <h2 class="detail-title">${item.title}</h2>
            <div class="detail-meta">
                <div class="meta-item">
                    <span class="meta-label">Author:</span>
                    <span class="meta-value">${item.author}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Type:</span>
                    <span class="meta-value">${item.type}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Year:</span>
                    <span class="meta-value">${item.year}</span>
                </div>
            </div>
            <div class="detail-description">
                ${item.description}
            </div>
            ${item.tags ? `
                <div class="detail-tags">
                    ${item.tags.map(tag => `<span class="tag">${tag}</span>`).join('')}
                </div>
            ` : ''}
        `;

        detailPanel.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    closeDetailPanel() {
        const detailPanel = document.getElementById('detailPanel');
        detailPanel.classList.remove('open');
        document.body.style.overflow = '';
        this.activeDetailId = null;
    }
}

// ===== INITIALIZE APP =====
const app = new RegeneracionApp();

// Make app globally available
window.app = app;

// ===== ADDITIONAL UTILITIES =====

// Throttle function for performance
function throttle(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Back to top smooth scroll
document.addEventListener('DOMContentLoaded', () => {
    const backToTopLinks = document.querySelectorAll('a[href="#home"]');
    backToTopLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    });
});

// Log initialization
console.log('Regeneración Lab v5.0 initialized');
console.log(`Catalog items: ${catalogData.length}`);
