// Support call-to-action modal: "Contribute" opens a reminder to name the lab
// at checkout, then sends the visitor on to the giving page (the Continue
// link's own href, which comes from the Customizer in WordPress).
(function () {
    var openBtn = document.getElementById('supportOpenBtn');
    var modal = document.getElementById('supportModal');
    if (!openBtn || !modal) return;

    var continueBtn = modal.querySelector('[data-support-continue]');
    var supportUrl = continueBtn ? continueBtn.getAttribute('href') : '';

    function openModal(e) {
        if (e) e.preventDefault();
        modal.removeAttribute('hidden');
        requestAnimationFrame(function () { modal.classList.add('is-open'); });
    }

    function closeModal(e) {
        if (e) e.preventDefault();
        modal.classList.remove('is-open');
        setTimeout(function () { modal.setAttribute('hidden', 'hidden'); }, 250);
    }

    openBtn.addEventListener('click', openModal);
    modal.querySelectorAll('[data-support-close]').forEach(function (el) {
        el.addEventListener('click', closeModal);
    });

    if (continueBtn) {
        continueBtn.addEventListener('click', function (e) {
            e.preventDefault();
            closeModal();
            window.open(supportUrl, '_blank', 'noopener');
        });
    }

    window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });
})();
