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
 * Upgrade steps for the NextGen LMS theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Add the commercial course fields used on course cards.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_theme_nextgen_upgrade($oldversion) {
    if ($oldversion < 2026100106) {
        theme_nextgen_install_commercial_fields();
        upgrade_plugin_savepoint(true, 2026100106, 'theme', 'nextgen');
    }

    if ($oldversion < 2026100111) {
        theme_nextgen_upgrade_public_pages();
        upgrade_plugin_savepoint(true, 2026100111, 'theme', 'nextgen');
    }

    if ($oldversion < 2026100112) {
        upgrade_plugin_savepoint(true, 2026100112, 'theme', 'nextgen');
    }

    if ($oldversion < 2026100113) {
        theme_nextgen_install_fact_fields();
        upgrade_plugin_savepoint(true, 2026100113, 'theme', 'nextgen');
    }

    if ($oldversion < 2026100114) {
        theme_nextgen_publish_public_paths();
        upgrade_plugin_savepoint(true, 2026100114, 'theme', 'nextgen');
    }

    if ($oldversion < 2026100115) {
        theme_nextgen_install_fact_fields();
        upgrade_plugin_savepoint(true, 2026100115, 'theme', 'nextgen');
    }

    return true;
}
