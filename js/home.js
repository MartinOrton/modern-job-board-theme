/**
 * Home page pointer effects (front page only, loaded with css/home.css).
 * - Hero product card tilts towards the pointer.
 * - Feature and pricing cards get a spotlight that follows the pointer.
 * Skipped entirely for touch devices and prefers-reduced-motion.
 */
(function () {
    'use strict';

    if (!document.body || !document.body.classList.contains('home') || !window.matchMedia) {
        return;
    }

    if (
        window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
        !window.matchMedia('(hover: hover) and (pointer: fine)').matches
    ) {
        return;
    }

    var hero = document.querySelector('.hero');
    var visual = document.querySelector('.hero-visual');

    if (hero && visual) {
        var frame = 0;
        var clamp = function (n) {
            return Math.max(-1, Math.min(1, n));
        };

        hero.addEventListener('pointermove', function (e) {
            if (frame) {
                return;
            }
            frame = window.requestAnimationFrame(function () {
                frame = 0;
                var r = visual.getBoundingClientRect();
                if (!r.width || !r.height) {
                    return;
                }
                var x = clamp((e.clientX - (r.left + r.width / 2)) / r.width);
                var y = clamp((e.clientY - (r.top + r.height / 2)) / r.height);
                visual.style.setProperty('--mjb-ry', (x * 7).toFixed(2) + 'deg');
                visual.style.setProperty('--mjb-rx', (y * -5).toFixed(2) + 'deg');
            });
        });

        hero.addEventListener('pointerleave', function () {
            visual.style.removeProperty('--mjb-ry');
            visual.style.removeProperty('--mjb-rx');
        });
    }

    var cards = document.querySelectorAll('.feature-card, .pricing-card');
    Array.prototype.forEach.call(cards, function (card) {
        card.addEventListener('pointermove', function (e) {
            var r = card.getBoundingClientRect();
            card.style.setProperty('--mjb-mx', (e.clientX - r.left).toFixed(0) + 'px');
            card.style.setProperty('--mjb-my', (e.clientY - r.top).toFixed(0) + 'px');
        });
    });
})();
