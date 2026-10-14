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
 * Collapse long content behind a "Show more" / "Show less" toggle.
 *
 * Content is measured when it becomes visible, so blocks inside hidden tabs
 * are checked when the tab opens.
 *
 * @module     theme_nextgen/showmore
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

const SELECTORS = {
    root: '[data-ng-showmore]',
    body: ':scope > [data-ng-showmore-body]',
    toggle: ':scope > [data-ng-showmore-toggle]',
};

// Content that overflows by less than about three lines stays open.
const SLACK = 64;

let counter = 0;

/**
 * Show the collapsed or expanded state.
 *
 * @param {HTMLElement} root
 * @param {HTMLButtonElement} toggle
 * @param {Boolean} expanded
 */
const setExpanded = (root, toggle, expanded) => {
    root.classList.toggle('is-expanded', expanded);
    toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    toggle.querySelector('.ng-showmore-sign').textContent = expanded ? '\u2212' : '+';
    toggle.querySelector('.ng-showmore-label').textContent = expanded ? toggle.dataset.less : toggle.dataset.more;
};

/**
 * Collapse the content only when it is long enough to need it.
 *
 * @param {HTMLElement} root
 * @param {HTMLElement} body
 * @param {HTMLButtonElement} toggle
 */
const measure = (root, body, toggle) => {
    if (root.classList.contains('is-expanded') || body.offsetParent === null) {
        return;
    }
    root.classList.add('is-collapsible');
    const long = body.scrollHeight - body.clientHeight > SLACK;
    root.classList.toggle('is-collapsible', long);
    toggle.hidden = !long;
};

/**
 * Bind one block.
 *
 * @param {HTMLElement} root
 */
const bind = (root) => {
    const body = root.querySelector(SELECTORS.body);
    const toggle = root.querySelector(SELECTORS.toggle);
    if (!body || !toggle) {
        return;
    }
    root.dataset.ngShowmoreReady = '1';
    if (!body.id) {
        counter++;
        body.id = 'ng-showmore-' + counter;
    }
    toggle.setAttribute('aria-controls', body.id);
    setExpanded(root, toggle, false);

    toggle.addEventListener('click', () => {
        const expanded = !root.classList.contains('is-expanded');
        setExpanded(root, toggle, expanded);
        if (!expanded && root.getBoundingClientRect().top < 0) {
            root.scrollIntoView({block: 'start'});
        }
    });

    const check = () => window.requestAnimationFrame(() => measure(root, body, toggle));
    if (window.ResizeObserver) {
        new ResizeObserver(check).observe(root);
    } else {
        window.addEventListener('resize', check);
    }
    check();
};

/**
 * Bind every block on the page.
 */
export const init = () => {
    document.querySelectorAll(SELECTORS.root).forEach((root) => {
        if (!root.dataset.ngShowmoreReady) {
            bind(root);
        }
    });
};
