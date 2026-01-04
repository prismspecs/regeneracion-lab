// syllabus2/app.js

const syllabusData = {
    themes: [
        {
            id: 'theme-1',
            title: 'Indigenous Sovereignty & Border Politics',
            description: 'Examining the intersection of Indigenous self-determination, colonial borders, and resistance movements.',
            items: [
                {
                    title: 'Indigenous Sovereignty and Border Politics',
                    author: 'Klee Benally',
                    type: 'research',
                    description: 'An examination of how Indigenous communities navigate and resist colonial borders imposed across traditional territories.'
                },
                {
                    title: 'Oral Histories: Border Resistance',
                    author: 'Kira Yellowhorse',
                    type: 'archive',
                    description: 'A collection of oral histories documenting three generations of Indigenous resistance to border infrastructure.'
                }
            ]
        },
        {
            id: 'theme-2',
            title: 'Decolonial Methodologies',
            description: 'Exploring alternative research methods centered on Indigenous epistemologies and community accountability.',
            items: [
                {
                    title: 'Decolonial Methodologies in Practice',
                    author: 'Collective Work',
                    type: 'essay',
                    description: 'A collection of essays exploring practical applications of decolonial research methods.'
                },
                {
                    title: 'Refusal as Political Practice',
                    author: 'Dr. Maya Redfeather',
                    type: 'essay',
                    description: 'An exploration of refusal as a form of Indigenous political agency.'
                }
            ]
        },
        {
            id: 'theme-3',
            title: 'Environmental Justice & Land Defense',
            description: 'Documenting struggles against environmental racism, resource extraction, and the fight for sacred site protection.',
            items: [
                {
                    title: 'Nuclear Colonialism and Uranium Mining Resistance',
                    author: 'Leona Morgan',
                    type: 'article',
                    description: 'Documenting the ongoing struggle against uranium mining and nuclear colonialism on Diné lands.'
                },
                {
                    title: 'Land Defense and Sacred Site Protection',
                    author: 'Louise Benally',
                    type: 'research',
                    description: 'Research exploring community-based organizing for land defense and protection of sacred sites.'
                },
                {
                    title: 'Seeds of Sovereignty: Indigenous Food Systems',
                    author: 'Dr. Thomas Begay',
                    type: 'book',
                    description: 'Research examining the relationship between seed keeping, food sovereignty, and Indigenous self-determination.'
                }
            ]
        },
        {
            id: 'theme-4',
            title: 'Anarchism & Anti-Colonial Critique',
            description: 'Exploring the connections and tensions between anarchist thought and Indigenous political traditions.',
            items: [
                {
                    title: 'Regeneración: Historical Archive 1900-1918',
                    author: 'Flores Magón Brothers',
                    type: 'archive',
                    description: 'Digital archive of the anarchist newspaper Regeneración, a key text in Indigenous and mestizo resistance.'
                },
                {
                    title: 'Indigenous Anarchism: A Critical Reader',
                    author: 'Various Authors',
                    type: 'book',
                    description: 'A collection of writings that explore the intersections between Indigenous political traditions and anarchist theory.'
                },
                {
                    title: 'Beyond Multiculturalism: Indigenous Critique',
                    author: 'Dr. Rosa Mendez',
                    type: 'essay',
                    description: 'A critical analysis of multiculturalism as a settler colonial framework.'
                }
            ]
        },
    ]
};

class SyllabusApp {
    constructor() {
        this.themes = syllabusData.themes;
        this.activeTheme = null;
        this.currentFilter = 'all';
        this.images = [
            'AmericanCanal.jpg', 'AnimalTracks2.jpg', 'AsequiasSanLucy.jpg', 'BarrelCactus.jpg',
            'desertsunset.jpg', 'GatheringHanam1.jpg', 'HanamHarvest1.jpg', 'ImperialDam.jpg',
            'JumpinCholla.jpg', 'JumpingCholla1.jpg', 'JumpingCholla2.jpg', 'JumpingCholla3.jpg',
            'OcotilloLeaves.jpg', 'PaintedRock.jpg', 'SanLucyWash.jpg', 'SLakeYuma.jpg',
            'VikamDoag3.jpg', 'YuccaBlossoms.jpg'
        ];
        this.init();
    }

    init() {
        this.renderThemes();
        this.setupEventListeners();
    }

    getRandomImage() {
        const randomIndex = Math.floor(Math.random() * this.images.length);
        return `images/${this.images[randomIndex]}`;
    }

    setupEventListeners() {
        // Close detail panel
        const closeBtn = document.getElementById('closeBtn');
        closeBtn.addEventListener('click', () => this.closeDetailPanel());

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeTheme) {
                this.closeDetailPanel();
            }
        });
        
        // Filter buttons
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.handleFilter(e.target.dataset.filter);
            });
        });
    }

    handleFilter(filter) {
        this.currentFilter = filter;

        // Update active button
        const filterBtns = document.querySelectorAll('.filter-btn');
        filterBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.filter === filter);
        });

        // If a theme is active, re-render its details with the new filter
        if (this.activeTheme) {
            this.showDetail(this.activeTheme);
        }
    }

    renderThemes() {
        const themesList = document.getElementById('themesList');
        themesList.innerHTML = this.themes.map(theme => `
            <div class="theme-item fade-in" data-id="${theme.id}">
                <div class="theme-title">${theme.title}</div>
                <div class="theme-description">${theme.description}</div>
                <div class="theme-items-count">${theme.items.length} items</div>
            </div>
        `).join('');

        const themeItems = themesList.querySelectorAll('.theme-item');
        themeItems.forEach(item => {
            item.addEventListener('click', () => {
                this.showDetail(item.dataset.id);
            });
        });
    }

    showDetail(id) {
        const theme = this.themes.find(t => t.id === id);
        if (!theme) return;

        this.activeTheme = id;

        // Update active state for the theme list
        const themeItems = document.querySelectorAll('.theme-item');
        themeItems.forEach(el => {
            el.classList.toggle('active', el.dataset.id === id);
        });
        
        const getFilteredItems = () => {
            if (this.currentFilter === 'all') {
                return theme.items;
            }
            return theme.items.filter(item => item.type.toLowerCase() === this.currentFilter);
        }

        const filteredItems = getFilteredItems();
        const randomImage = this.getRandomImage();

        const detailContent = document.getElementById('detailContent');
        detailContent.innerHTML = `
            <div class="detail-header">
                <h2 class="detail-title">${theme.title}</h2>
                <p class="detail-description">${theme.description}</p>
            </div>
            
            <div class="detail-visual">
                <img src="${randomImage}" alt="A random image related to the theme">
            </div>

            <div class="detail-section">
                <h3 class="section-title">Readings & Projects (${filteredItems.length})</h3>
                <ul class="section-list">
                    ${filteredItems.length > 0 ? filteredItems.map(item => `
                        <li class="section-item">
                            <h4 class="item-title">${item.title}</h4>
                            <p class="item-author">${item.author}</p>
                            <span class="item-type">${item.type}</span>
                        </li>
                    `).join('') : '<p>No items match the current filter.</p>'}
                </ul>
            </div>
        `;

        const detailPanel = document.getElementById('detailPanel');
        detailPanel.classList.add('open');
    }

    closeDetailPanel() {
        const detailPanel = document.getElementById('detailPanel');
        detailPanel.classList.remove('open');
        this.activeTheme = null;

        // Remove active state from theme items
        const themeItems = document.querySelectorAll('.theme-item');
        themeItems.forEach(el => {
            el.classList.remove('active');
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new SyllabusApp();
});