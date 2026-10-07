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
 * Event observers for the NextGen LMS theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {
    /**
     * After profile save, return to the enrol checkout when that was the pending destination.
     *
     * Moodle's user edit form otherwise sends people to Preferences and drops wantsurl.
     *
     * @param \core\event\user_updated $event
     */
    public static function user_updated(\core\event\user_updated $event): void {
        global $SESSION, $USER;

        if ((int) $event->objectid !== (int) $USER->id) {
            return;
        }
        if (empty($SESSION->wantsurl) || !is_string($SESSION->wantsurl)) {
            return;
        }

        try {
            $wants = new \moodle_url($SESSION->wantsurl);
        } catch (\Throwable $e) {
            return;
        }

        $path = $wants->get_path();
        if (!str_contains($path, '/enrol/index.php')) {
            return;
        }

        unset($SESSION->wantsurl);
        redirect($wants);
    }

    /**
     * Remember a fresh enrolment so the course page can show a one-time success banner.
     *
     * @param \core\event\user_enrolment_created $event
     */
    public static function user_enrolment_created(\core\event\user_enrolment_created $event): void {
        global $SESSION, $USER;

        if ((int) $event->relateduserid !== (int) $USER->id) {
            return;
        }
        $courseid = (int) $event->courseid;
        if ($courseid < 1) {
            return;
        }
        $SESSION->theme_nextgen_enrolsuccess = $courseid;
    }
}
