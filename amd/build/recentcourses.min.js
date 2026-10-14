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
 * Limit Recently accessed courses to the user's enrolments.
 *
 * Core also returns guest-enrol courses the user has opened. Course overview
 * stays on Moodle's enrolled-course lists.
 *
 * @module     theme_nextgen/recentcourses
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['core_course/repository', 'block_recentlyaccessedcourses/main'], function(CoursesRepository, Main) {
    var original = CoursesRepository.getLastAccessedCourses.bind(CoursesRepository);

    /**
     * Recently accessed courses that the user is enrolled in.
     *
     * @param {number} userid
     * @param {number} limit
     * @param {number} offset
     * @param {string} sort
     * @return {object} Promise resolved with courses
     */
    var enrolledOnly = function(userid, limit, offset, sort) {
        var fetchlimit = (limit && limit > 0) ? Math.max(limit, 50) : 50;
        return original(userid, fetchlimit, offset, sort).then(function(courses) {
            return CoursesRepository.getEnrolledCoursesByTimelineClassification('all', 0).then(function(result) {
                var enrolled = {};
                var list = (result && result.courses) ? result.courses : [];
                list.forEach(function(course) {
                    enrolled[Number(course.id)] = true;
                });
                var filtered = (courses || []).filter(function(course) {
                    return enrolled[Number(course.id)];
                });
                if (limit && limit > 0) {
                    return filtered.slice(0, limit);
                }
                return filtered;
            });
        });
    };

    return {
        /**
         * Start the core block after restricting its course list to enrolments.
         *
         * @param {number} userid
         * @param {object} root
         */
        init: function(userid, root) {
            CoursesRepository.getLastAccessedCourses = enrolledOnly;
            Main.init(userid, root);
        }
    };
});
