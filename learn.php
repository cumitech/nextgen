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

require(__DIR__ . '/bootstrap.php');

defined('MOODLE_INTERNAL') || die();

$courseid = required_param('id', PARAM_INT);

if (!isloggedin() || isguestuser()) {
    $wantsurl = (new moodle_url('/learn.php', ['id' => $courseid]))->out_as_local_url(false);
    redirect(new moodle_url('/login/index.php', ['wantsurl' => $wantsurl]));
}

$course = get_course($courseid);
if (!$course || (int) $course->id === SITEID) {
    redirect(new moodle_url('/my/'));
}

$context = context_course::instance($course->id);
if (!is_enrolled($context, $USER, '', true) && !has_capability('moodle/course:view', $context, $USER)) {
    redirect(new moodle_url('/course/view.php', ['id' => $course->id]));
}

redirect(new moodle_url('/course/view.php', ['id' => $course->id]));
