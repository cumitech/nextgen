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
 * Copy a blog post address from the share row.
 *
 * @module     theme_nextgen/blogshare
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Wire the copy-link control on a blog post.
 */
export const init = () => {
    document.querySelectorAll('.ng-blog-share-copy').forEach((button) => {
        button.addEventListener('click', () => {
            const url = button.dataset.url || '';
            const copied = button.dataset.copied || '';
            const finish = () => {
                const label = button.getAttribute('aria-label') || '';
                button.classList.add('is-copied');
                if (copied) {
                    button.setAttribute('aria-label', copied);
                }
                window.setTimeout(() => {
                    button.classList.remove('is-copied');
                    if (label) {
                        button.setAttribute('aria-label', label);
                    }
                }, 1600);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(finish).catch(finish);
                return;
            }
            finish();
        });
    });
};
