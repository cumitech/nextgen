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
 * Create or edit a public event.
 *
 * Staff access follows Moodle's calendar-entry management permissions.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/bootstrap.php');

defined('MOODLE_INTERNAL') || die();

global $OUTPUT, $PAGE;

require_login();
if (!\theme_nextgen\local\events::can_manage()) {
    throw new moodle_exception('nopermissions', 'error', '', get_string('eventmanage', 'theme_nextgen'));
}

$id = optional_param('id', 0, PARAM_INT);
$create = optional_param('create', 0, PARAM_BOOL);
if (!$id && !$create) {
    $PAGE->set_context(context_system::instance());
    $PAGE->set_url(new moodle_url('/eventmanage.php'));
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title(get_string('eventmanage', 'theme_nextgen'));
    $PAGE->set_heading(get_string('eventmanage', 'theme_nextgen'));
    $PAGE->add_body_class('nextgen-public nextgen-events');

    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template(
        'theme_nextgen/event_management',
        \theme_nextgen\local\events::management_context()
    );
    echo $OUTPUT->footer();
    exit;
}

$record = $id
    ? \theme_nextgen\local\events::form_data(
        \theme_nextgen\local\events::editable_event($id)
    )
    : (object) [
        'id' => 0,
        'title' => '',
        'eventtype' => 'seminar',
        'delivery' => 'online',
        'summary' => '',
        'imageurl' => '',
        'description' => '',
        'timestart' => time() + DAYSECS,
        'timeend' => time() + DAYSECS + HOURSECS,
        'venue' => '',
        'cost' => '',
        'registrationurl' => '',
        'status' => 0,
    ];

$form = new \theme_nextgen\form\event(null, ['record' => $record]);
$form->set_data($record);
if ($form->is_cancelled()) {
    redirect(new moodle_url('/events.php'));
} else if ($data = $form->get_data()) {
    $id = \theme_nextgen\local\events::save_event($id, $data);

    redirect(
        new moodle_url('/event.php', ['id' => $id]),
        get_string('event_saved', 'theme_nextgen'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/eventmanage.php', $id ? ['id' => $id] : []));
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string($id ? 'eventedit' : 'eventcreate', 'theme_nextgen'));
$PAGE->set_heading(get_string($id ? 'eventedit' : 'eventcreate', 'theme_nextgen'));
$PAGE->add_body_class('nextgen-public nextgen-events');

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_nextgen/event_form', [
    'title' => get_string($id ? 'eventedit' : 'eventcreate', 'theme_nextgen'),
    'eventsurl' => (new moodle_url('/events.php'))->out(false),
    'form' => $form->render(),
]);
echo $OUTPUT->footer();
