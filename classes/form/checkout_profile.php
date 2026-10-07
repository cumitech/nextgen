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
 * Minimal account details collected on the enrolment checkout page.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class checkout_profile extends \moodleform {
    /**
     * Define first name, last name, and email.
     */
    #[\Override]
    protected function definition(): void {
        $mform = $this->_form;
        /** @var \stdClass $user */
        $user = $this->_customdata['user'];
        $required = get_string('required');

        $mform->addElement('text', 'firstname', get_string('firstname'), [
            'size' => 48,
            'autocomplete' => 'given-name',
        ]);
        $mform->setType('firstname', PARAM_NOTAGS);
        $mform->addRule('firstname', $required, 'required', null, 'client');
        $mform->setDefault('firstname', $user->firstname);

        $mform->addElement('text', 'lastname', get_string('lastname'), [
            'size' => 48,
            'autocomplete' => 'family-name',
        ]);
        $mform->setType('lastname', PARAM_NOTAGS);
        $mform->addRule('lastname', $required, 'required', null, 'client');
        $mform->setDefault('lastname', $user->lastname);

        $mform->addElement('text', 'email', get_string('email'), [
            'size' => 48,
            'autocomplete' => 'email',
        ]);
        $mform->setType('email', PARAM_RAW_TRIMMED);
        $mform->addRule('email', get_string('missingemail'), 'required', null, 'client');
        $mform->addRule('email', get_string('invalidemail'), 'email', null, 'client');
        $mform->setDefault('email', $user->email);

        $this->add_action_buttons(true, get_string('checkoutprofilecta', 'theme_nextgen'));
    }

    /**
     * Extra validation for email uniqueness and site email rules.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    #[\Override]
    public function validation($data, $files): array {
        global $CFG, $DB, $USER;

        $errors = parent::validation($data, $files);
        $email = trim((string) ($data['email'] ?? ''));
        if ($email === '' || !validate_email($email)) {
            $errors['email'] = get_string('invalidemail');
            return $errors;
        }
        if (!empty($CFG->forbid_email_change) && strcasecmp($email, (string) $USER->email) !== 0) {
            $errors['email'] = get_string('checkoutemaillocked', 'theme_nextgen');
            return $errors;
        }
        $select = $DB->sql_equal('email', ':email', false) . ' AND id <> :userid AND deleted = 0';
        if ($DB->record_exists_select('user', $select, [
            'email' => $email,
            'userid' => (int) $USER->id,
        ])) {
            $errors['email'] = get_string('emailexists');
        }
        return $errors;
    }
}
