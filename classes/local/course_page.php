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

namespace theme_nextgen\local;

/**
 * Public course sales page, served at /course/view.php.
 *
 * People who already belong to the course continue into the normal course page.
 * Everyone else sees the sales layout at the same address, without a theme path.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_page {
    /**
     * Render the sales page when this request is a public course view.
     *
     * Called during after_config, before course/view.php asks for a login.
     */
    public static function serve_public_course(): void {
        global $CFG, $DB, $USER;

        if (during_initial_install() || !empty($CFG->upgraderunning)) {
            return;
        }
        if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
            return;
        }
        if (defined('AJAX_SCRIPT') && AJAX_SCRIPT) {
            return;
        }
        if (defined('WS_SERVER') && WS_SERVER) {
            return;
        }
        if (($CFG->theme ?? '') !== 'nextgen') {
            return;
        }

        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if (!preg_match('#/course/view\.php$#', $script)) {
            return;
        }

        $courseid = optional_param('id', 0, PARAM_INT);
        if ($courseid < 1 || self::is_course_action_request()) {
            return;
        }

        $course = $DB->get_record('course', ['id' => $courseid]);
        if (!$course || (int) $course->id === SITEID) {
            return;
        }

        $context = \context_course::instance($course->id);
        if (!(int) $course->visible && !has_capability('moodle/course:viewhiddencourses', $context)) {
            return;
        }
        $categorycontext = \context_coursecat::instance($course->category);
        if (!has_capability('moodle/category:viewcourselist', $categorycontext)) {
            return;
        }
        if (isloggedin() && !isguestuser()) {
            if (is_enrolled($context, $USER, '', true)) {
                return;
            }
            if (has_any_capability([
                'moodle/course:view',
                'moodle/course:update',
            ], $context)) {
                return;
            }
        }

        self::render($course);
        exit(0);
    }

    /**
     * True when the query is a course-home action rather than a public view.
     *
     * @return bool
     */
    private static function is_course_action_request(): bool {
        $flags = [
            'sectionid' => 0,
            'duplicatesection' => 0,
            'move' => 0,
            'hide' => 0,
            'show' => 0,
        ];
        foreach ($flags as $name => $default) {
            if (optional_param($name, $default, PARAM_INT) !== $default) {
                return true;
            }
        }
        if (optional_param('section', null, PARAM_INT) !== null) {
            return true;
        }
        if (optional_param('edit', -1, PARAM_INT) !== -1) {
            return true;
        }
        if (optional_param('switchrole', -1, PARAM_INT) !== -1) {
            return true;
        }
        return false;
    }

    /**
     * Print the sales page for one visible course.
     *
     * @param \stdClass $course
     */
    public static function render(\stdClass $course): void {
        global $OUTPUT, $PAGE;

        $context = \context_course::instance($course->id);
        $title = format_string($course->fullname, true, ['context' => $context]);
        $PAGE->set_url(new \moodle_url('/course/view.php', ['id' => $course->id]));
        $PAGE->set_pagetype('theme-nextgen-course');
        $PAGE->set_context($context);
        $PAGE->set_course($course);
        $PAGE->set_pagelayout('standard');
        $PAGE->set_pagetype('theme-nextgen-course');
        $PAGE->set_secondary_navigation(false);
        $PAGE->set_title($title);
        $PAGE->set_heading('');
        $PAGE->navbar->add(get_string('courses'), new \moodle_url('/course/index.php'));
        $PAGE->navbar->add(format_string($course->shortname, true, ['context' => $context]));

        echo $OUTPUT->header();
        echo $OUTPUT->footer();
    }
}
