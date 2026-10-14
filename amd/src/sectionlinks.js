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
 * Point course-index and module-title links at the readable module address.
 *
 * Moodle prints course/section.php. The course editor rebuilds those links
 * from its own state, so this runs again after those updates. Links that carry
 * edit or sesskey are left alone.
 *
 * @module     theme_nextgen/sectionlinks
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Replace plain section links and mark the open module in the course index.
 *
 * @param {Object} map Section id to public URL
 * @param {Number} sectionid Section open on this page, or 0
 */
export const init = (map, sectionid) => {
    const urls = (map && !Array.isArray(map)) ? map : {};
    const current = Number(sectionid) || 0;

    const rewrite = () => {
        document.querySelectorAll('a[href*="section.php"]').forEach((link) => {
            let parsed;
            try {
                parsed = new URL(link.href, window.location.href);
            } catch (error) {
                return;
            }
            if (!parsed.pathname.endsWith('/course/section.php')) {
                return;
            }
            const keys = Array.from(parsed.searchParams.keys());
            if (keys.length !== 1 || keys[0] !== 'id') {
                return;
            }
            const pretty = urls[parsed.searchParams.get('id')];
            if (!pretty || link.href === pretty) {
                return;
            }
            link.href = pretty;
        });

        if (!current) {
            return;
        }
        const item = document.querySelector('#course-index-section-' + current + ' [data-for="section_item"]');
        if (item && !item.classList.contains('pageitem')) {
            item.classList.add('pageitem');
        }
    };

    let frame = 0;
    const schedule = () => {
        if (frame) {
            return;
        }
        frame = window.requestAnimationFrame(() => {
            frame = 0;
            rewrite();
        });
    };

    rewrite();
    const root = document.getElementById('page');
    if (root && window.MutationObserver) {
        new MutationObserver(schedule).observe(root, {
            childList: true,
            subtree: true,
        });
    }
};
