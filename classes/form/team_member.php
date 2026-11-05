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
 * Create or edit a custom team member.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class team_member extends \moodleform {
    /**
     * Define team-member fields.
     */
    #[\Override]
    protected function definition(): void {
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('teamname', 'theme_nextgen'), ['size' => 64]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 120), 'maxlength', 120, 'client');

        $mform->addElement('text', 'role', get_string('teamrole', 'theme_nextgen'), ['size' => 64]);
        $mform->setType('role', PARAM_TEXT);
        $mform->addRule('role', get_string('required'), 'required', null, 'client');
        $mform->addRule('role', get_string('maximumchars', '', 180), 'maxlength', 180, 'client');

        $mform->addElement('text', 'title', get_string('teamtitlefield', 'theme_nextgen'), ['size' => 64]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', get_string('maximumchars', '', 180), 'maxlength', 180, 'client');

        $mform->addElement('textarea', 'bio', get_string('teambio', 'theme_nextgen'), [
            'rows' => 8,
            'cols' => 64,
        ]);
        $mform->setType('bio', PARAM_TEXT);
        $mform->addRule('bio', get_string('maximumchars', '', 5000), 'maxlength', 5000, 'client');

        $mform->addElement('text', 'imageurl', get_string('teamimage', 'theme_nextgen'), [
            'size' => 64,
            'maxlength' => 255,
            'placeholder' => 'https://',
        ]);
        $mform->setType('imageurl', PARAM_URL);
        $mform->addRule('imageurl', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('imageurl', 'teamimage', 'theme_nextgen');

        $this->add_action_buttons(true, get_string('teamsave', 'theme_nextgen'));
    }

    /**
     * Validate the optional profile image URL.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    #[\Override]
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);
        $name = trim((string) ($data['name'] ?? ''));
        $role = trim((string) ($data['role'] ?? ''));
        $title = trim((string) ($data['title'] ?? ''));
        $bio = trim((string) ($data['bio'] ?? ''));

        if ($name === '') {
            $errors['name'] = get_string('required');
        } else if (\core_text::strlen($name) > 120) {
            $errors['name'] = get_string('maximumchars', '', 120);
        }

        if ($role === '') {
            $errors['role'] = get_string('required');
        } else if (\core_text::strlen($role) > 180) {
            $errors['role'] = get_string('maximumchars', '', 180);
        }

        if (\core_text::strlen($title) > 180) {
            $errors['title'] = get_string('maximumchars', '', 180);
        }

        if (\core_text::strlen($bio) > 5000) {
            $errors['bio'] = get_string('maximumchars', '', 5000);
        }

        $imageurl = trim((string) ($data['imageurl'] ?? ''));
        if (\core_text::strlen($imageurl) > 255) {
            $errors['imageurl'] = get_string('maximumchars', '', 255);
        } else if ($imageurl !== '' && \theme_nextgen\local\team::safe_image_url($imageurl) === '') {
            $errors['imageurl'] = get_string('teamimageinvalid', 'theme_nextgen');
        }

        return $errors;
    }
}
