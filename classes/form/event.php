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

namespace theme_nextgen\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/formslib.php');

/**
 * Event creation and editing form.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class event extends \moodleform {
    /**
     * Define event fields.
     */
    #[\Override]
    protected function definition(): void {
        $mform = $this->_form;
        $mform->addElement('text', 'title', get_string('eventtitle', 'theme_nextgen'), ['size' => 64]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('required'), 'required', null, 'client');
        $mform->addRule('title', get_string('maximumchars', '', 120), 'maxlength', 120, 'client');

        $mform->addElement('select', 'eventtype', get_string('eventtype', 'theme_nextgen'), \theme_nextgen\local\events::types());
        $mform->setType('eventtype', PARAM_ALPHANUMEXT);

        $mform->addElement('select', 'delivery', get_string('eventdelivery', 'theme_nextgen'), \theme_nextgen\local\events::deliveries());
        $mform->setType('delivery', PARAM_ALPHANUMEXT);

        $mform->addElement('textarea', 'summary', get_string('eventsummary', 'theme_nextgen'), [
            'rows' => 3,
            'cols' => 64,
        ]);
        $mform->setType('summary', PARAM_TEXT);
        $mform->addRule('summary', get_string('required'), 'required', null, 'client');
        $mform->addRule('summary', get_string('maximumchars', '', 280), 'maxlength', 280, 'client');

        $mform->addElement('textarea', 'description', get_string('eventdescription', 'theme_nextgen'), [
            'rows' => 8,
            'cols' => 64,
        ]);
        $mform->setType('description', PARAM_TEXT);
        $mform->addRule('description', get_string('required'), 'required', null, 'client');

        $mform->addElement('date_time_selector', 'timestart', get_string('eventstart', 'theme_nextgen'));
        $mform->addElement('date_time_selector', 'timeend', get_string('eventend', 'theme_nextgen'));

        $mform->addElement('text', 'venue', get_string('eventvenue', 'theme_nextgen'), ['size' => 64]);
        $mform->setType('venue', PARAM_TEXT);
        $mform->addRule('venue', get_string('maximumchars', '', 180), 'maxlength', 180, 'client');

        $mform->addElement('text', 'cost', get_string('eventcost', 'theme_nextgen'), ['size' => 32]);
        $mform->setType('cost', PARAM_TEXT);
        $mform->addHelpButton('cost', 'eventcost', 'theme_nextgen');

        $mform->addElement('text', 'registrationurl', get_string('eventregistration', 'theme_nextgen'), [
            'size' => 64,
            'placeholder' => 'https://',
        ]);
        $mform->setType('registrationurl', PARAM_URL);
        $mform->addHelpButton('registrationurl', 'eventregistration', 'theme_nextgen');

        $mform->addElement('select', 'status', get_string('eventstatus', 'theme_nextgen'), [
            0 => get_string('eventstatusdraft', 'theme_nextgen'),
            1 => get_string('eventstatuspublished', 'theme_nextgen'),
            2 => get_string('eventstatuscancelled', 'theme_nextgen'),
        ]);
        $mform->setType('status', PARAM_INT);

        $this->add_action_buttons(true, get_string('eventsave', 'theme_nextgen'));
    }

    /**
     * Validate event dates and registration URL scheme.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    #[\Override]
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);
        if ((int) ($data['timeend'] ?? 0) <= (int) ($data['timestart'] ?? 0)) {
            $errors['timeend'] = get_string('eventendafterstart', 'theme_nextgen');
        }

        $url = trim((string) ($data['registrationurl'] ?? ''));
        if ($url !== '' && \theme_nextgen\local\events::safe_registration_url($url) === '') {
            $errors['registrationurl'] = get_string('eventregistrationinvalid', 'theme_nextgen');
        }

        return $errors;
    }
}
