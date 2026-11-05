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
 * Create, edit, and remove custom team members.
 *
 * Access is controlled by the theme/nextgen:manageteam system capability.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/bootstrap.php');

defined('MOODLE_INTERNAL') || die();

global $OUTPUT, $PAGE;

require_login();
if (!\theme_nextgen\local\team::can_manage()) {
    throw new moodle_exception('nopermissions', 'error', '', get_string('teammanage', 'theme_nextgen'));
}

$id = optional_param('id', 0, PARAM_INT);
$create = optional_param('create', 0, PARAM_BOOL);
$delete = optional_param('delete', 0, PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->add_body_class('nextgen-public nextgen-team');

if ($delete > 0) {
    $member = \theme_nextgen\local\team::get_member($delete);
    $PAGE->set_url(new moodle_url('/teammanage.php', ['delete' => $delete]));
    $PAGE->set_title(get_string('teammanage', 'theme_nextgen'));
    $PAGE->set_heading(get_string('teammanage', 'theme_nextgen'));

    if (optional_param('confirm', 0, PARAM_BOOL)) {
        require_sesskey();
        \theme_nextgen\local\team::delete_member($delete);
        redirect(
            new moodle_url('/teammanage.php'),
            get_string('teamdeleted', 'theme_nextgen'),
            null,
            \core\output\notification::NOTIFY_SUCCESS
        );
    }

    echo $OUTPUT->header();
    echo $OUTPUT->confirm(
        get_string('teamconfirmdelete', 'theme_nextgen', format_string($member->name)),
        new moodle_url('/teammanage.php', ['delete' => $delete, 'confirm' => 1]),
        new moodle_url('/teammanage.php')
    );
    echo $OUTPUT->footer();
    exit;
}

if (!$id && !$create) {
    $PAGE->set_url(new moodle_url('/teammanage.php'));
    $PAGE->set_title(get_string('teammanage', 'theme_nextgen'));
    $PAGE->set_heading(get_string('teammanage', 'theme_nextgen'));

    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template(
        'theme_nextgen/team_management',
        \theme_nextgen\local\team::management_context()
    );
    echo $OUTPUT->footer();
    exit;
}

$record = $id
    ? \theme_nextgen\local\team::get_member($id)
    : (object) [
        'id' => 0,
        'name' => '',
        'role' => '',
        'title' => '',
        'bio' => '',
        'imageurl' => '',
    ];

$form = new \theme_nextgen\form\team_member(null, ['record' => $record]);
$form->set_data($record);
if ($form->is_cancelled()) {
    redirect(new moodle_url('/teammanage.php'));
} else if ($data = $form->get_data()) {
    \theme_nextgen\local\team::save_member($id, $data);
    redirect(
        new moodle_url('/teammanage.php'),
        get_string('teamsaved', 'theme_nextgen'),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

$PAGE->set_url(new moodle_url('/teammanage.php', $id ? ['id' => $id] : ['create' => 1]));
$PAGE->set_title(get_string($id ? 'teamedit' : 'teamcreate', 'theme_nextgen'));
$PAGE->set_heading(get_string($id ? 'teamedit' : 'teamcreate', 'theme_nextgen'));

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_nextgen/team_form', [
    'title' => get_string($id ? 'teamedit' : 'teamcreate', 'theme_nextgen'),
    'manageurl' => (new moodle_url('/teammanage.php'))->out(false),
    'form' => $form->render(),
]);
echo $OUTPUT->footer();
