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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Public design preview for a visible course-certificate template.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$courseid = required_param('courseid', PARAM_INT);
$templateid = required_param('templateid', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

if (empty($course->visible) || $courseid === SITEID
        || !$DB->get_manager()->table_exists('coursecertificate')
        || !class_exists('\tool_certificate\template')) {
    throw new moodle_exception('notfound');
}

$modinfo = get_fast_modinfo($course);
$hasvisiblecertificate = false;
foreach ($modinfo->get_cms() as $cm) {
    if ($cm->modname !== 'coursecertificate' || empty($cm->visible) || !empty($cm->deletioninprogress)) {
        continue;
    }
    $section = $modinfo->get_section_info((int) $cm->sectionnum);
    if (!$section || empty($section->visible)) {
        continue;
    }
    $activity = $DB->get_record('coursecertificate', [
        'id' => (int) $cm->instance,
        'course' => $courseid,
        'template' => $templateid,
    ], 'id', IGNORE_MISSING);
    if ($activity) {
        $hasvisiblecertificate = true;
        break;
    }
}

if (!$hasvisiblecertificate) {
    throw new moodle_exception('notfound');
}

$PAGE->set_url(new moodle_url('/theme/nextgen/certificate-preview.php', [
    'courseid' => $courseid,
    'templateid' => $templateid,
]));
$PAGE->set_context(context_course::instance($courseid));
header('Cache-Control: private, no-store, max-age=0');

$USER = (object) [
    'id' => 0,
    'firstname' => get_string('certificatesamplefirstname', 'theme_nextgen'),
    'lastname' => get_string('certificatesamplelastname', 'theme_nextgen'),
    'firstnamephonetic' => '',
    'lastnamephonetic' => '',
    'middlename' => '',
    'alternatename' => '',
    'email' => '',
    'idnumber' => '',
    'institution' => '',
    'department' => '',
    'city' => '',
    'country' => '',
    'address' => '',
    'phone1' => '',
    'phone2' => '',
    'url' => '',
    'lang' => $CFG->lang,
];

\tool_certificate\template::instance($templateid)->generate_pdf(true);
