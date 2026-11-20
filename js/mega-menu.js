/**
 * Mega menus: hover and focus are handled in CSS; this adds aria-expanded,
 * Escape to close, tap-to-open on touch screens and click-outside to close.
 */
(function () {
    'use strict';

    var items = document.querySelectorAll('.nav-item--mega');

    Array.prototype.forEach.call(items, function (item) {
        var trigger = item.querySelector('a');

        if (!trigger) {
            return;
        }

        var setOpen = function (open) {
            item.classList.toggle('is-open', open);
            trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        item.addEventListener('mouseenter', function () {
            setOpen(true);
        });

        item.addEventListener('mouseleave', function () {
            item.classList.remove('is-dismissed');
            setOpen(false);
        });

        item.addEventListener('focusin', function () {
            if (!item.classList.contains('is-dismissed')) {
                setOpen(true);
            }
        });

        item.addEventListener('focusout', function (e) {
            if (!item.contains(e.relatedTarget)) {
                item.classList.remove('is-dismissed');
                setOpen(false);
            }
        });

        document.addEventListener('keydown', function (e) {
            if ('Escape' === e.key && item.classList.contains('is-open')) {
                item.classList.add('is-dismissed');
                setOpen(false);
                trigger.focus();
            }
        });

        // Touch screens have no hover: the first tap opens the menu, the second follows the link.
        trigger.addEventListener('click', function (e) {
            if (window.matchMedia && window.matchMedia('(hover: none)').matches && !item.classList.contains('is-open')) {
                e.preventDefault();
                setOpen(true);
            }
        });

        document.addEventListener('click', function (e) {
            if (!item.contains(e.target)) {
                setOpen(false);
            }
        });
    });
})();
