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

namespace theme_nextgen\output\core;

/**
 * Course catalogue presentation. Categories in the hero filter the course cards.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_renderer extends \core_course_renderer {
    /**
     * Wrap the category listing in the catalogue layout.
     *
     * @param int|\stdClass|\core_course_category $category
     * @return string
     */
    #[\Override]
    public function course_category($category) {
        $context = theme_nextgen_catalogue_context();
        $html = $this->render_from_template('theme_nextgen/catalogue_hero', $context);
        $html .= $this->render_from_template('theme_nextgen/catalogue_search', $context);
        $html .= $this->render_from_template('theme_nextgen/catalogue_body', $context);
        return \html_writer::div($html, 'ng-catalogue');
    }

    /**
     * Catalogue course box, using the commercial card.
     *
     * @param \coursecat_helper $chelper
     * @param \core_course_list_element|\stdClass $course
     * @param string $additionalclasses
     * @return string
     */
    #[\Override]
    protected function coursecat_coursebox(\coursecat_helper $chelper, $course, $additionalclasses = '') {
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof \stdClass) {
            $course = new \core_course_list_element($course);
        }
        $card = $this->render_from_template(
            'theme_nextgen/course_card',
            theme_nextgen_course_card_data($course)
        );
        return \html_writer::div($card, 'coursebox ng-catalogue-course ' . $additionalclasses, [
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ]);
    }

    /**
     * Dedicated checkout page for enrolment and payment widgets.
     *
     * @param \stdClass $course
     * @param string[] $widgets
     * @param \core\url|null $returnurl
     * @return string
     */
    #[\Override]
    public function enrolment_options(\stdClass $course, array $widgets, ?\core\url $returnurl = null): string {
        theme_nextgen_enrol_checkout_process((int) $course->id);

        $message = '';
        $continuebutton = '';
        if (!$widgets && !isguestuser() && isloggedin()) {
            if ($returnurl) {
                $message = get_string('checkoutclosedtext', 'theme_nextgen');
                $continuebutton = $this->output->continue_button($returnurl);
            } else {
                $message = get_string('checkoutclosedtext', 'theme_nextgen');
                $continuebutton = $this->output->continue_button(
                    new \moodle_url('/course/view.php', ['id' => (int) $course->id])
                );
            }
        }

        return $this->render_from_template(
            'theme_nextgen/enrol_checkout',
            theme_nextgen_enrol_checkout_context($course, $widgets, $message, $continuebutton)
        );
    }
}
