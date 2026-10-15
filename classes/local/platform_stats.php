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

namespace theme_nextgen\local;

/**
 * Home page figures taken from courses the current user is allowed to see.
 *
 * Counts use Moodle's course catalogue and course-contact roles. Certification
 * is the share of those courses that list a certificate. Membership is the
 * share a learner can join without being placed there by staff.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class platform_stats {
    /**
     * Four figures for the site home hero.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function for_landing(): array {
        $courseids = self::visible_course_ids();
        $total = count($courseids);

        return [
            [
                'value' => self::format_count($total),
                'label' => get_string('statcourses', 'theme_nextgen'),
            ],
            [
                'value' => self::format_count(self::instructor_count($courseids)),
                'label' => get_string('statinstructors', 'theme_nextgen'),
            ],
            [
                'value' => self::format_percent(self::certified_course_count($courseids), $total),
                'label' => get_string('statcertification', 'theme_nextgen'),
            ],
            [
                'value' => self::format_percent(self::membership_course_count($courseids), $total),
                'label' => get_string('statmembership', 'theme_nextgen'),
            ],
        ];
    }

    /**
     * Visible courses, excluding the site front page course.
     *
     * @return int[]
     */
    private static function visible_course_ids(): array {
        $ids = [];
        $courses = \core_course_category::top()->get_courses(['recursive' => true]);
        foreach ($courses as $course) {
            $id = (int) $course->id;
            if ($id > 0 && $id !== (int) SITEID) {
                $ids[] = $id;
            }
        }
        return $ids;
    }

    /**
     * Distinct people shown as course contacts on these courses.
     *
     * Uses the site course-contact roles. When that setting is empty, teacher
     * archetypes are used so the figure still reflects who teaches.
     *
     * @param int[] $courseids
     * @return int
     */
    private static function instructor_count(array $courseids): int {
        global $CFG, $DB;

        if (!$courseids) {
            return 0;
        }

        $roleids = array_filter(array_map('intval', explode(',', (string) ($CFG->coursecontact ?? ''))));
        if (!$roleids) {
            $roleids = array_map('intval', $DB->get_fieldset_select(
                'role',
                'id',
                'archetype = :editing OR archetype = :teacher',
                ['editing' => 'editingteacher', 'teacher' => 'teacher']
            ));
        }
        if (!$roleids) {
            return 0;
        }

        [$coursesql, $courseparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'icid');
        [$rolesql, $roleparams] = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED, 'irid');
        $params = $courseparams + $roleparams;
        $params['level'] = CONTEXT_COURSE;

        return (int) $DB->count_records_sql(
            "SELECT COUNT(DISTINCT ra.userid)
               FROM {role_assignments} ra
               JOIN {context} ctx ON ctx.id = ra.contextid
               JOIN {user} u ON u.id = ra.userid
              WHERE ctx.contextlevel = :level
                AND ctx.instanceid {$coursesql}
                AND ra.roleid {$rolesql}
                AND u.deleted = 0",
            $params
        );
    }

    /**
     * Courses that offer a certificate.
     *
     * A course counts when its certificate custom field says so, or when it
     * contains a visible certificate activity. The same signals are used on
     * the course page.
     *
     * @param int[] $courseids
     * @return int
     */
    private static function certified_course_count(array $courseids): int {
        global $DB;

        if (!$courseids) {
            return 0;
        }

        $found = [];
        [$coursesql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'certid');
        $params['shortname'] = 'ngcertificate';
        $records = $DB->get_records_sql(
            "SELECT d.instanceid, d.value
               FROM {customfield_data} d
               JOIN {customfield_field} f ON f.id = d.fieldid
              WHERE f.shortname = :shortname
                AND d.instanceid {$coursesql}",
            $params
        );
        foreach ($records as $record) {
            if (self::certificate_offered((string) $record->value)) {
                $found[(int) $record->instanceid] = true;
            }
        }

        $modnames = [];
        foreach (['customcert', 'certificate', 'coursecertificate'] as $name) {
            if ($DB->record_exists('modules', ['name' => $name, 'visible' => 1])) {
                $modnames[] = $name;
            }
        }
        if ($modnames) {
            [$coursesql, $courseparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'ccid');
            [$modsql, $modparams] = $DB->get_in_or_equal($modnames, SQL_PARAMS_NAMED, 'mod');
            $activitycourses = $DB->get_fieldset_sql(
                "SELECT DISTINCT cm.course
                   FROM {course_modules} cm
                   JOIN {modules} m ON m.id = cm.module
                  WHERE cm.course {$coursesql}
                    AND m.name {$modsql}
                    AND cm.visible = 1
                    AND cm.deletioninprogress = 0",
                $courseparams + $modparams
            );
            foreach ($activitycourses as $courseid) {
                $found[(int) $courseid] = true;
            }
        }

        return count($found);
    }

    /**
     * Whether a stored certificate field means the course offers one.
     *
     * @param string $value
     * @return bool
     */
    private static function certificate_offered(string $value): bool {
        return theme_nextgen_certificate_is_offered($value);
    }

    /**
     * Courses a learner can join themselves.
     *
     * Self enrolment counts only while new enrolments are allowed. Fee and
     * PayPal count when that method is enabled for the course. Manual
     * enrolment is staff-assigned, so it is not membership.
     *
     * @param int[] $courseids
     * @return int
     */
    private static function membership_course_count(array $courseids): int {
        global $CFG, $DB;

        if (!$courseids) {
            return 0;
        }

        $enabled = array_flip(array_filter(array_map(
            'trim',
            explode(',', (string) ($CFG->enrol_plugins_enabled ?? ''))
        )));
        $paid = [];
        foreach (['fee', 'paypal'] as $name) {
            if (isset($enabled[$name])) {
                $paid[] = $name;
            }
        }
        $selfopen = isset($enabled['self']);
        if (!$paid && !$selfopen) {
            return 0;
        }

        [$coursesql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'mcid');
        $params['instancestatus'] = ENROL_INSTANCE_ENABLED;
        $clauses = [];
        if ($selfopen) {
            $params['selfenrol'] = 'self';
            $params['newenrols'] = 1;
            $clauses[] = '(e.enrol = :selfenrol AND e.customint6 = :newenrols)';
        }
        if ($paid) {
            [$pluginsql, $pluginparams] = $DB->get_in_or_equal($paid, SQL_PARAMS_NAMED, 'enrol');
            $params += $pluginparams;
            $clauses[] = "e.enrol {$pluginsql}";
        }
        $methodsql = implode(' OR ', $clauses);

        return (int) $DB->count_records_sql(
            "SELECT COUNT(DISTINCT e.courseid)
               FROM {enrol} e
              WHERE e.courseid {$coursesql}
                AND e.status = :instancestatus
                AND ({$methodsql})",
            $params
        );
    }

    /**
     * Count shown with a plus once there is at least one.
     *
     * @param int $count
     * @return string
     */
    private static function format_count(int $count): string {
        if ($count < 1) {
            return '0';
        }
        return get_string('statcountplus', 'theme_nextgen', $count);
    }

    /**
     * Whole-number percentage of a total.
     *
     * @param int $part
     * @param int $total
     * @return string
     */
    private static function format_percent(int $part, int $total): string {
        $percent = 0;
        if ($total > 0) {
            $percent = (int) round(($part / $total) * 100);
        }
        return get_string('statpercent', 'theme_nextgen', $percent);
    }
}
