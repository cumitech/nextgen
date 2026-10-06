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
 * Save a student's review and return to the course reviews tab.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/bootstrap.php');

defined('MOODLE_INTERNAL') || die();

global $DB, $USER;

$courseid = required_param('courseid', PARAM_INT);
$rating = required_param('rating', PARAM_INT);
$review = required_param('review', PARAM_TEXT);

require_login();
require_sesskey();

$return = new moodle_url('/course/view.php', ['id' => $courseid], 'ng-panel-reviews');

$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);
if ((int) $course->id === SITEID) {
    throw new moodle_exception('invalidcourseid');
}

$context = context_course::instance($course->id);
if (!(int) $course->visible && !has_capability('moodle/course:viewhiddencourses', $context)) {
    throw new moodle_exception('coursehidden');
}

if (!is_enrolled($context, null, '', true)) {
    \core\notification::error(get_string('reviewenrol', 'theme_nextgen'));
    redirect($return);
}

$review = trim($review);
if ($rating < 1 || $rating > 5 || $review === '') {
    \core\notification::error(get_string('reviewinvalid', 'theme_nextgen'));
    redirect($return);
}

$review = \core_text::substr($review, 0, 1000);
$updated = theme_nextgen_save_review((int) $course->id, (int) $USER->id, $rating, $review);
$message = $updated ? 'reviewupdated' : 'reviewsubmitted';
\core\notification::success(get_string($message, 'theme_nextgen'));
redirect($return);
