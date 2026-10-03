<?php
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
 * Remove the site-root pages published by this theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Delete only the public scripts this theme published.
 */
function xmldb_theme_nextgen_uninstall() {
    global $CFG;

    foreach (['about', 'contact', 'privacy', 'terms', 'faq'] as $key) {
        $path = $CFG->dirroot . DIRECTORY_SEPARATOR . $key . '.php';
        if (!is_file($path)) {
            continue;
        }
        $head = (string) file_get_contents($path, false, null, 0, 900);
        if (str_contains($head, 'theme_nextgen public page:')) {
            unlink($path);
        }
    }

    return true;
}
