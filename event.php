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
 * Public details for one event.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/bootstrap.php');

defined('MOODLE_INTERNAL') || die();

global $OUTPUT, $PAGE;

$id = required_param('id', PARAM_INT);
$context = \theme_nextgen\local\events::detail_context($id);
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/event.php', ['id' => $id]));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($context['event']['title']);
$PAGE->set_heading($context['event']['title']);
$PAGE->add_body_class('nextgen-public nextgen-events');

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_nextgen/event_detail', $context);
echo $OUTPUT->footer();
