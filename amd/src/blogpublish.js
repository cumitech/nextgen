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
 * Default a new blog post to the public audience.
 *
 * Moodle selects "Anyone on this site", which visitors cannot read. When the
 * world option is available, that is the audience a new post starts on.
 * Draft remains available.
 *
 * @module     theme_nextgen/blogpublish
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Point the publish control at the public option.
 */
export const init = () => {
    const select = document.getElementById('id_publishstate');
    if (!select) {
        return;
    }
    const world = select.querySelector('option[value="public"]');
    if (!world) {
        return;
    }
    const site = select.querySelector('option[value="site"]');
    if (select.value === 'site') {
        select.value = 'public';
    }
    if (site) {
        site.remove();
    }
};
