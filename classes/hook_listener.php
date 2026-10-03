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

namespace theme_nextgen;

/**
 * Hook listeners for the NextGen LMS theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_listener {
    /**
     * Send guests and signed-out visitors from the empty dashboard to the landing page.
     *
     * @param \core\hook\output\before_http_headers $hook
     */
    public static function before_http_headers(\core\hook\output\before_http_headers $hook): void {
        global $PAGE;
        if ($PAGE->theme->name !== 'nextgen') {
            return;
        }
        if (isloggedin() && !isguestuser()) {
            return;
        }
        if ((string) $PAGE->pagetype !== 'my-index') {
            return;
        }
        redirect(new \moodle_url('/', ['redirect' => 0]));
    }

    /**
     * Add administrator-defined links to the primary navigation.
     *
     * Core items such as Home, Dashboard, and My courses are left in place.
     *
     * @param \core\hook\navigation\primary_extend $hook
     */
    public static function extend_primary_navigation(\core\hook\navigation\primary_extend $hook): void {
        global $PAGE;
        if ($PAGE->theme->name !== 'nextgen') {
            return;
        }
        if (str_starts_with((string) $PAGE->pagetype, 'course-index')) {
            $browse = optional_param('browse', '', PARAM_ALPHA);
            $PAGE->set_primary_active_tab($browse === 'categories' ? 'nextgen-categories' : 'nextgen-courses');
        }
        $primary = $hook->get_primaryview();
        if (!isloggedin() || isguestuser()) {
            $home = $primary->find('home', \navigation_node::TYPE_SYSTEM);
            if ($home) {
                $home->action = new \moodle_url('/', ['redirect' => 0]);
            }
        }
        foreach (theme_nextgen_custom_nav_items() as $item) {
            $node = $primary->add(
                $item['label'],
                new \moodle_url($item['url']),
                \navigation_node::TYPE_CUSTOM,
                null,
                $item['key']
            );
            // Adding a node whose URL matches the current page marks it active immediately.
            // Clear that so only the chosen tab stays highlighted.
            if ($node) {
                $node->isactive = false;
            }
        }
        if (!during_initial_install()) {
            \theme_nextgen\local\public_page::extend_primary($primary);
        }
    }
}
