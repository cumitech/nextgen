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
 * Readable addresses for a course module (a course section).
 *
 * /learn/{course}/{module} runs Moodle's own course/section.php, so editing,
 * availability, and the activity chooser stay intact. The trailing -s{id} keeps
 * an address working after the module is renamed.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class module_page {
    /** @var \moodle_url|null Address of the module page currently being rendered. */
    public static ?\moodle_url $currenturl = null;

    /** @var int Section id of that module, or 0. */
    public static int $currentsectionid = 0;

    /** @var array<int, array<string, string>> Request cache of section id to public URL. */
    private static array $linkmaps = [];

    /**
     * Send /learn.php and /learn/{course}/{module} to the right Moodle page.
     *
     * When the address is a module, this returns course/section.php. The caller
     * must include that file from the script itself. Including it from a method
     * hides Moodle's global page object.
     *
     * @return string|null
     */
    public static function dispatch(): ?string {
        $coursekey = self::clean_key(optional_param('course', '', PARAM_TEXT));
        $moduleslug = self::clean_key(optional_param('module', '', PARAM_TEXT));
        if ($coursekey !== '' && $moduleslug !== '') {
            return self::prepare_section($coursekey, $moduleslug);
        }

        $courseid = optional_param('id', 0, PARAM_INT);
        if ($courseid > 0) {
            self::redirect_course($courseid);
        }

        redirect(new \moodle_url('/my/'));
        return null;
    }

    /**
     * Send a plain course/section.php view to the readable address.
     *
     * Edit, sesskey, and the editing notices are kept so the editor still toggles.
     * Guests stay on Moodle's page, which asks them to log in without putting the
     * module name in the address first.
     */
    public static function redirect_plain_section(): void {
        global $DB;

        if (!self::pretty_urls_ready()) {
            return;
        }
        if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
            return;
        }
        if (defined('AJAX_SCRIPT') && AJAX_SCRIPT) {
            return;
        }
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
            return;
        }
        if (!isloggedin() || isguestuser()) {
            return;
        }

        $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
        if (!str_ends_with($script, '/course/section.php')) {
            return;
        }

        $sectionid = optional_param('id', 0, PARAM_INT);
        if ($sectionid < 1) {
            return;
        }
        $section = $DB->get_record('course_sections', ['id' => $sectionid], 'id, course, section, name');
        if (!$section || (int) $section->course === SITEID) {
            return;
        }

        self::ensure_course_library();
        $course = get_course($section->course);
        if (!course_format_uses_sections($course->format)) {
            return;
        }

        try {
            $info = get_fast_modinfo($course)->get_section_info_by_id($sectionid, IGNORE_MISSING);
        } catch (\moodle_exception) {
            return;
        }
        if (!$info || !$info->uservisible) {
            return;
        }

        $url = self::url($course, $info);
        self::forward_section_params($url);
        redirect($url);
    }

    /**
     * Public URL for one section.
     *
     * @param \stdClass $course
     * @param \section_info|\stdClass $section
     * @return \moodle_url
     */
    public static function url(\stdClass $course, \section_info|\stdClass $section): \moodle_url {
        self::ensure_course_library();
        $name = course_get_format($course)->get_section_name($section);
        $slug = self::text_slug($name);
        if ($slug === '') {
            $slug = 'module';
        }
        if (\core_text::strlen($slug) > 60) {
            $slug = \core_text::substr($slug, 0, 60);
            $hyphen = strrpos($slug, '-');
            if ($hyphen > 20) {
                $slug = substr($slug, 0, $hyphen);
            }
            $slug = trim($slug, '-');
        }
        $path = '/learn/' . self::course_key($course) . '/' . $slug . '-s' . (int) $section->id;
        return new \moodle_url($path);
    }

    /**
     * Section id to public URL, for links the course index and section titles print.
     *
     * @param \stdClass $course
     * @return array<string, string>
     */
    public static function link_map(\stdClass $course): array {
        $courseid = (int) ($course->id ?? 0);
        if ($courseid < 1 || $courseid === SITEID) {
            return [];
        }
        if (isset(self::$linkmaps[$courseid])) {
            return self::$linkmaps[$courseid];
        }
        self::ensure_course_library();
        if (!course_format_uses_sections($course->format ?? '')) {
            self::$linkmaps[$courseid] = [];
            return [];
        }

        $map = [];
        foreach (get_fast_modinfo($course)->get_section_info_all() as $section) {
            if (!$section->uservisible) {
                continue;
            }
            $map[(string) $section->id] = self::url($course, $section)->out(false);
        }
        self::$linkmaps[$courseid] = $map;
        return $map;
    }

    /**
     * Point course-index anchors at the readable address.
     *
     * @param string $html
     * @param \stdClass $course
     * @return string
     */
    public static function rewrite_section_links(string $html, \stdClass $course): string {
        if ($html === '') {
            return $html;
        }
        foreach (self::link_map($course) as $sectionid => $pretty) {
            $plain = (new \moodle_url('/course/section.php', ['id' => (int) $sectionid]))->out(false);
            $pattern = '#' . preg_quote($plain, '#') . '(?!\d)#';
            $updated = preg_replace_callback($pattern, function() use ($pretty) {
                return $pretty;
            }, $html);
            if (is_string($updated)) {
                $html = $updated;
            }
        }
        return $html;
    }

    /**
     * Course format helpers live in course/lib.php, which a normal page has not loaded yet.
     */
    private static function ensure_course_library(): void {
        global $CFG;

        require_once($CFG->dirroot . '/course/lib.php');
    }

    /**
     * Slug used in /learn/{course}/.
     *
     * @param \stdClass $course
     * @return string
     */
    public static function course_key(\stdClass $course): string {
        $key = self::text_slug((string) $course->shortname);
        if ($key === '') {
            $key = 'course';
        }
        return $key;
    }

    /**
     * Resolve a readable module address and move to the course directory.
     *
     * course/section.php includes its library with a relative path, so the
     * working directory has to be the course directory before the caller includes it.
     *
     * @param string $coursekey
     * @param string $moduleslug
     * @return string
     */
    private static function prepare_section(string $coursekey, string $moduleslug): string {
        global $CFG, $DB;

        $course = self::find_course($coursekey);
        $sectionid = self::section_id_from_slug($moduleslug);
        if (!$course || $sectionid < 1) {
            redirect(new \moodle_url('/course/index.php'));
        }

        $section = $DB->get_record('course_sections', [
            'id' => $sectionid,
            'course' => $course->id,
        ]);
        if (!$section) {
            redirect(new \moodle_url('/course/view.php', ['id' => $course->id]));
        }

        self::$currenturl = self::url($course, $section);
        self::$currentsectionid = (int) $section->id;
        $_GET['id'] = (string) $section->id;
        if (isset($_POST['id'])) {
            $_POST['id'] = (string) $section->id;
        }

        // section.php includes lib.php relative to the course directory.
        $coursedir = $CFG->dirroot . DIRECTORY_SEPARATOR . 'course';
        chdir($coursedir);
        return $coursedir . DIRECTORY_SEPARATOR . 'section.php';
    }

    /**
     * Open the Moodle course page for a signed-in learner.
     *
     * @param int $courseid
     */
    private static function redirect_course(int $courseid): void {
        global $USER;

        if (!isloggedin() || isguestuser()) {
            $wantsurl = (new \moodle_url('/learn.php', ['id' => $courseid]))->out_as_local_url(false);
            redirect(new \moodle_url('/login/index.php', ['wantsurl' => $wantsurl]));
        }

        $course = get_course($courseid);
        if (!$course || (int) $course->id === SITEID) {
            redirect(new \moodle_url('/my/'));
        }

        $context = \context_course::instance($course->id);
        if (!is_enrolled($context, $USER, '', true) && !has_capability('moodle/course:view', $context)) {
            redirect(new \moodle_url('/course/view.php', ['id' => $course->id]));
        }

        redirect(new \moodle_url('/course/view.php', ['id' => $course->id]));
    }

    /**
     * @param string $key
     * @return \stdClass|null
     */
    private static function find_course(string $key): ?\stdClass {
        global $DB;

        $select = 'id <> :site AND ' . $DB->sql_equal('shortname', ':shortname', false, false);
        $course = $DB->get_record_select('course', $select, [
            'site' => SITEID,
            'shortname' => $key,
        ], '*', IGNORE_MULTIPLE);
        if ($course) {
            return $course;
        }

        $courses = $DB->get_records_select('course', 'id <> :site', ['site' => SITEID], '', 'id, shortname');
        foreach ($courses as $candidate) {
            if (self::course_key($candidate) === $key) {
                return get_course($candidate->id);
            }
        }
        return null;
    }

    /**
     * @param string $slug
     * @return int
     */
    private static function section_id_from_slug(string $slug): int {
        if (!preg_match('/-s(\d+)$/', $slug, $match)) {
            return 0;
        }
        return (int) $match[1];
    }

    /**
     * @param string $value
     * @return string
     */
    private static function clean_key(string $value): string {
        $value = \core_text::strtolower(trim($value));
        if (!preg_match('/^[a-z0-9-]+$/', $value)) {
            return '';
        }
        return $value;
    }

    /**
     * @param string $text
     * @return string
     */
    private static function text_slug(string $text): string {
        $text = \core_text::strtolower(strip_tags($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        if (!is_string($text)) {
            return '';
        }
        return trim($text, '-');
    }

    /**
     * Keep the query Moodle's section page uses to toggle editing.
     *
     * @param \moodle_url $url
     */
    private static function forward_section_params(\moodle_url $url): void {
        $edit = optional_param('edit', '', PARAM_ALPHANUMEXT);
        if ($edit === '0' || $edit === '1' || $edit === 'on' || $edit === 'off') {
            $url->param('edit', $edit);
        }
        $sesskey = optional_param('sesskey', '', PARAM_ALPHANUM);
        if ($sesskey !== '') {
            $url->param('sesskey', $sesskey);
        }
        foreach (['notifyeditingon', 'notifyeditingoff'] as $flag) {
            if (optional_param($flag, 0, PARAM_BOOL)) {
                $url->param($flag, 1);
            }
        }
        $expand = optional_param('expandsection', 0, PARAM_INT);
        if ($expand > 0) {
            $url->param('expandsection', $expand);
        }
    }

    /**
     * The readable path only works once the site-root script and rewrite exist.
     *
     * @return bool
     */
    private static function pretty_urls_ready(): bool {
        global $CFG;

        if (empty($CFG->dirroot) || !is_file($CFG->dirroot . '/learn.php')) {
            return false;
        }
        $htaccess = @file_get_contents($CFG->dirroot . '/.htaccess');
        return is_string($htaccess) && str_contains($htaccess, 'learn.php?course=$1&module=$2');
    }
}
