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
        return true;
    };

    const bindNav = () => {
        document.querySelectorAll('.site-nav a').forEach((link) => {
            link.addEventListener('click', (e) => {
                if (!shouldHandle(e, link)) return;
                e.preventDefault();
                const url = link.href;
                loadPage(url, true);
            });
        });
    };

    const replaceContent = (nextDoc) => {
        const nextMain = nextDoc.querySelector('#mainContent');
        if (!nextMain) return false;
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
                bindNav();
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

    bindNav();
})();
