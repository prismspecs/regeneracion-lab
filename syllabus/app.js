// Sample catalog data
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

class CatalogApp {
    constructor() {
        this.data = catalogData;
        this.currentFilter = 'all';
        this.activeItem = null;
        this.init();
    }

    init() {
        this.renderCatalog();
        this.setupEventListeners();
    }

    setupEventListeners() {
        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.handleFilter(e.target.dataset.filter);
            });
        });

        // Close detail panel
        const closeBtn = document.getElementById('closeBtn');
        closeBtn.addEventListener('click', () => {
            this.closeDetailPanel();
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeItem) {
                this.closeDetailPanel();
            }
        });
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
            return this.data;
        }
        return this.data.filter(item => item.type === this.currentFilter);
    }

    renderCatalog() {
        const catalogList = document.getElementById('catalogList');
        const filteredData = this.getFilteredData();

        if (filteredData.length === 0) {
            catalogList.innerHTML = `
                <div class="empty-state">
                    <h3>No items found</h3>
                    <p>Try selecting a different filter</p>
                </div>
            `;
            return;
        }

        catalogList.innerHTML = filteredData.map(item => `
            <div class="catalog-item fade-in" data-id="${item.id}">
                <div class="item-index">${item.id}</div>
                <div class="item-title">${item.title}</div>
                <div class="item-author">${item.author}</div>
                <div class="item-type">${item.type}</div>
            </div>
        `).join('');

        // Add click listeners to items
        const items = catalogList.querySelectorAll('.catalog-item');
        items.forEach(item => {
            item.addEventListener('click', () => {
                this.showDetail(item.dataset.id);
            });
        });
    }

    showDetail(id) {
        const item = this.data.find(i => i.id === id);
        if (!item) return;

        this.activeItem = id;

        // Update active state
        const items = document.querySelectorAll('.catalog-item');
        items.forEach(el => {
            el.classList.toggle('active', el.dataset.id === id);
        });

        // Render detail content
        const detailContent = document.getElementById('detailContent');
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

        // Open panel
        const detailPanel = document.getElementById('detailPanel');
        detailPanel.classList.add('open');
    }

    closeDetailPanel() {
        const detailPanel = document.getElementById('detailPanel');
        detailPanel.classList.remove('open');

        // Remove active state from items
        const items = document.querySelectorAll('.catalog-item');
        items.forEach(el => {
            el.classList.remove('active');
        });

        this.activeItem = null;
    }
}

// Initialize the app
const app = new CatalogApp();

