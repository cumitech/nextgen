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
 * Course catalogue presentation. Moodle still supplies the search form and course tree.
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
        $html .= $this->render_from_template('theme_nextgen/catalogue_filters', $context);
        $html .= parent::course_category($category);
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
     * Enrolment page widgets, without the default course info box.
     *
     * The sales layout already shows the course. The forms themselves are unchanged.
     *
     * @param \stdClass $course
     * @param string[] $widgets
     * @param \core\url|null $returnurl
     * @return string
     */
    #[\Override]
    public function enrolment_options(\stdClass $course, array $widgets, ?\core\url $returnurl = null): string {
        $message = '';
        $continuebutton = '';
        if (!$widgets) {
            if (isguestuser()) {
                $message = get_string('noguestaccess', 'enrol');
                $continuebutton = $this->output->continue_button(get_login_url());
            } else if ($returnurl) {
                $message = get_string('notenrollable', 'enrol');
                $continuebutton = $this->output->continue_button($returnurl);
            } else {
                $url = get_local_referer(false);
                if (empty($url)) {
                    $url = new \moodle_url('/index.php');
                }
                $message = get_string('notenrollable', 'enrol');
                $continuebutton = $this->output->continue_button($url);
            }
        }

        return $this->render_from_template('theme_nextgen/enrolment_options', [
            'widgets' => array_values($widgets),
            'message' => $message,
            'continuebutton' => $continuebutton,
        ]);
    }
}
