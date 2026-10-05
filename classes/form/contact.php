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
 * Site contact form with name and email left editable.
 *
 * Moodle's form fills those fields from the signed-in account and then freezes
 * them. This form keeps the account values as the starting point.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class contact extends contactsitesupport_form {
    /**
     * Define the form, then unlock name and email.
     */
    #[\Override]
    public function definition(): void {
        parent::definition();

        $mform = $this->_form;
        $rules = [
            'name' => get_string('required'),
            'email' => get_string('missingemail'),
        ];
        foreach ($rules as $field => $message) {
            if (!$mform->elementExists($field)) {
                continue;
            }
            $element = $mform->getElement($field);
            if (!$element->isFrozen()) {
                continue;
            }
            $element->unfreeze();
            $mform->addRule($field, $message, 'required', null, 'client');
        }
    }
}
