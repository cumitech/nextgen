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

use core_user\form\contactsitesupport_form;

/**
 * Site contact form with editable name and email and theme button labels.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class contact extends contactsitesupport_form {
    /**
     * Define the form fields used on the public contact page.
     */
    #[\Override]
    public function definition(): void {
        global $CFG;

        $mform = $this->_form;
        $user = $this->_customdata;
        $required = get_string('required');

        $mform->addElement('text', 'name', get_string('contactfieldname', 'theme_nextgen'), [
            'size' => 48,
            'placeholder' => get_string('contactnameplaceholder', 'theme_nextgen'),
            'autocomplete' => 'name',
        ]);
        $mform->addRule('name', $required, 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('text', 'email', get_string('contactfieldemail', 'theme_nextgen'), [
            'size' => 48,
            'placeholder' => get_string('contactemailplaceholder', 'theme_nextgen'),
            'autocomplete' => 'email',
        ]);
        $mform->addRule('email', get_string('missingemail'), 'required', null, 'client');
        $mform->setType('email', PARAM_EMAIL);

        $mform->addElement('text', 'subject', get_string('contactfieldsubject', 'theme_nextgen'), [
            'size' => 48,
            'placeholder' => get_string('contactsubjectplaceholder', 'theme_nextgen'),
        ]);
        $mform->addRule('subject', $required, 'required', null, 'client');
        $mform->setType('subject', PARAM_TEXT);

        $mform->addElement('textarea', 'message', get_string('contactfieldmessage', 'theme_nextgen'), [
            'rows' => 7,
            'cols' => 50,
            'placeholder' => get_string('contactmessageplaceholder', 'theme_nextgen'),
        ]);
        $mform->addRule('message', $required, 'required', null, 'client');
        $mform->setType('message', PARAM_TEXT);

        if (isloggedin() && !isguestuser() && $user) {
            $mform->setDefault('name', fullname($user));
            $mform->setDefault('email', $user->email);
        }

        if (!empty($CFG->recaptchapublickey) && !empty($CFG->recaptchaprivatekey)) {
            $mform->addElement('recaptcha', 'recaptcha_element', get_string('security_question', 'auth'));
            $mform->addHelpButton('recaptcha_element', 'recaptcha', 'auth');
            $mform->closeHeaderBefore('recaptcha_element');
        }

        $this->add_action_buttons(true, get_string('contactsubmit', 'theme_nextgen'));
    }
}
