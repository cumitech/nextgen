// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Thin progress line shown while a page is opening.
 *
 * Also copies the login URL hash into the anchor field. That used to be an
 * inline script on the login form.
 *
 * @module     theme_nextgen/loadline
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define([], function() {
    var bar = null;
    var inner = null;
    var value = 0;
    var trickle = null;
    var hideTimer = null;

    /**
     * Create the progress bar once.
     */
    var ensure = function() {
        if (bar) {
            return;
        }
        bar = document.createElement('div');
        bar.className = 'ng-loadline';
        bar.setAttribute('aria-hidden', 'true');
        inner = document.createElement('div');
        inner.className = 'ng-loadline-bar';
        bar.appendChild(inner);
        (document.body || document.documentElement).appendChild(bar);
    };

    /**
     * Set the bar width.
     *
     * @param {Number} next
     */
    var set = function(next) {
        value = Math.max(0, Math.min(100, next));
        if (inner) {
            inner.style.width = value + '%';
        }
    };

    /**
     * Start the trickle animation.
     */
    var start = function() {
        ensure();
        window.clearTimeout(hideTimer);
        window.clearInterval(trickle);
        bar.classList.add('is-active');
        bar.classList.remove('is-done');
        set(value > 0 && value < 100 ? value : 12);
        trickle = window.setInterval(function() {
            if (value < 88) {
                set(value + Math.max(0.6, (88 - value) * 0.07));
            }
        }, 180);
    };

    /**
     * Finish and hide the bar.
     */
    var done = function() {
        if (!bar) {
            return;
        }
        window.clearInterval(trickle);
        bar.classList.add('is-active');
        bar.classList.remove('is-done');
        set(100);
        hideTimer = window.setTimeout(function() {
            bar.classList.add('is-done');
            bar.classList.remove('is-active');
            window.setTimeout(function() {
                bar.classList.remove('is-done');
                set(0);
            }, 320);
        }, 160);
    };

    /**
     * Whether this click should navigate in the same tab.
     *
     * @param {MouseEvent} event
     * @return {Boolean}
     */
    var isPlainClick = function(event) {
        return event.button === 0 && !event.metaKey && !event.ctrlKey && !event.shiftKey && !event.altKey;
    };

    /**
     * Whether the link stays on this site and is not a toggle.
     *
     * @param {HTMLAnchorElement} anchor
     * @return {Boolean}
     */
    var opensHere = function(anchor) {
        var href = anchor.getAttribute('href');
        if (!href || href.charAt(0) === '#' || href.indexOf('javascript:') === 0) {
            return false;
        }
        if (anchor.target && anchor.target !== '_self') {
            return false;
        }
        if (anchor.hasAttribute('download') || anchor.hasAttribute('data-bs-toggle') || anchor.hasAttribute('data-toggler')) {
            return false;
        }
        var url;
        try {
            url = new URL(anchor.href, window.location.href);
        } catch (e) {
            return false;
        }
        if (url.origin !== window.location.origin) {
            return false;
        }
        if (url.pathname === window.location.pathname && url.search === window.location.search) {
            return false;
        }
        return true;
    };

    return {
        /**
         * Bind the progress line and the login anchor field.
         */
        init: function() {
            var anchor = document.getElementById('anchor');
            if (anchor) {
                anchor.value = window.location.hash;
            }

            document.addEventListener('click', function(event) {
                if (event.defaultPrevented || !isPlainClick(event)) {
                    return;
                }
                var link = event.target && event.target.closest ? event.target.closest('a[href]') : null;
                if (!link || !opensHere(link)) {
                    return;
                }
                start();
            }, true);

            document.addEventListener('submit', function(event) {
                var form = event.target;
                window.setTimeout(function() {
                    if (event.defaultPrevented || !form || (form.target && form.target !== '_self')) {
                        return;
                    }
                    start();
                }, 0);
            }, true);

            if (document.readyState !== 'complete') {
                if (document.body) {
                    start();
                } else {
                    document.addEventListener('DOMContentLoaded', start);
                }
                window.addEventListener('load', done);
            }

            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    done();
                }
            });
        },
    };
});
