/* AYFC public website interactions (no jQuery needed) */
(function () {
    'use strict';

    document.documentElement.classList.remove('no-js');

    var header = document.querySelector('.site-header');
    var toTop = document.querySelector('.to-top');

    function onScroll() {
        var y = window.scrollY || window.pageYOffset;
        if (header) header.classList.toggle('is-scrolled', y > 10);
        if (toTop) toTop.classList.toggle('show', y > 600);
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    if (toTop) {
        toTop.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    }

    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Reveal sections as they scroll into view.
    var revealItems = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && !reduceMotion) {
        var revealObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        revealItems.forEach(function (el) { revealObserver.observe(el); });
    } else {
        revealItems.forEach(function (el) { el.classList.add('is-visible'); });
    }

    // Count-up animation for impact numbers.
    function formatNumber(n) { return n.toLocaleString('en-US'); }
    function animateCount(el) {
        var target = parseInt(el.getAttribute('data-count'), 10) || 0;
        if (reduceMotion || target === 0) { el.textContent = formatNumber(target); return; }
        var start = null, duration = 1600;
        function step(ts) {
            if (!start) start = ts;
            var progress = Math.min((ts - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = formatNumber(Math.round(target * eased));
            if (progress < 1) window.requestAnimationFrame(step);
        }
        window.requestAnimationFrame(step);
    }
    var counters = document.querySelectorAll('[data-count]');
    if ('IntersectionObserver' in window) {
        var countObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCount(entry.target);
                    countObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });
        counters.forEach(function (el) { countObserver.observe(el); });
    } else {
        counters.forEach(animateCount);
    }

    // Photo lightbox.
    if (window.GLightbox && document.querySelector('.glightbox')) {
        window.GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
    }

    // Close the mobile menu after choosing a link.
    document.querySelectorAll('#mainNav .nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            var menu = document.getElementById('mainNav');
            if (menu && menu.classList.contains('show') && window.bootstrap) {
                window.bootstrap.Collapse.getOrCreateInstance(menu).hide();
            }
        });
    });
})();
