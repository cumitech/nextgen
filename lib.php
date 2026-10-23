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

/**
 * NextGen LMS theme callbacks.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/boost/lib.php');

/**
 * Public name of this webapp.
 *
 * Headers, footers, titles, and marketing lines use this so the product
 * is not also called NextGen or NextGen LMS.
 *
 * @return string
 */
function theme_nextgen_app_name(): string {
    return get_string('appname', 'theme_nextgen');
}

/**
 * Add the theme body class used by the shell stylesheet.
 *
 * @param moodle_page $page
 */
function theme_nextgen_page_init($page) {
    $page->add_body_class('nextgen');
    if ($page->pagelayout === 'frontpage' && !$page->user_is_editing()) {
        $page->add_body_class('nextgen-landing');
    }
    if (str_starts_with((string) $page->pagetype, 'course-index')) {
        $page->add_body_class('nextgen-catalogue');
    }
    if (theme_nextgen_is_course_detail_page($page)) {
        $page->add_body_class('nextgen-course');
    }
    if ((string) $page->pagetype === 'theme-nextgen-course') {
        $page->add_body_class('nextgen-sales');
    }
    if ((string) $page->pagetype === 'enrol-index') {
        $page->add_body_class('nextgen-enrol-checkout');
    }
    if ($page->pagelayout === 'mydashboard') {
        $page->add_body_class('nextgen-dashboard');
    }
    if ((string) $page->pagetype === 'blog-index') {
        $page->add_body_class('nextgen-blog');
        if ($page->url->param('entryid')) {
            $page->add_body_class('nextgen-blog-entry');
            $page->requires->js_call_amd('theme_nextgen/blogshare', 'init');
        }
        if (!isloggedin() || isguestuser()) {
            $page->add_body_class('nextgen-blog-visitor');
        }
    }
    if ((string) $page->pagetype === 'blog-edit') {
        $page->requires->js_call_amd('theme_nextgen/blogpublish', 'init');
    }
    $reading = ['mod-page-view', 'mod-book-view', 'mod-resource-view', 'mod-url-view', 'mod-folder-view'];
    if (in_array((string) $page->pagetype, $reading, true)) {
        $page->add_body_class('nextgen-lesson');
    }

    // section.php sets its own URL before the header. Put the readable address
    // back so the editing switch and the rest of the page stay on it.
    if (\theme_nextgen\local\module_page::$currenturl instanceof moodle_url) {
        $page->set_url(\theme_nextgen\local\module_page::$currenturl);
    }
    $course = $page->course;
    $learningpage = !empty($course->id) && (int) $course->id !== SITEID
        && (in_array($page->pagelayout, ['course', 'incourse'], true)
            || \theme_nextgen\local\module_page::$currentsectionid > 0);
    if ($learningpage) {
        $page->requires->js_call_amd('theme_nextgen/sectionlinks', 'init', [
            \theme_nextgen\local\module_page::link_map($course),
            \theme_nextgen\local\module_page::$currentsectionid,
        ]);
    }

    if ($page->pagelayout === 'frontpage' && !$page->user_is_editing()) {
        $page->requires->js_call_amd('theme_nextgen/faculty', 'init');
    }
    $page->requires->js_call_amd('theme_nextgen/loadline', 'init');
    $page->requires->js_call_amd('theme_nextgen/showmore', 'init');
    if ($page->pagelayout !== 'maintenance') {
        $page->requires->js_call_amd('theme_nextgen/gtranslate', 'init');
    }
}

/**
 * Welcome row for the signed-in dashboard.
 *
 * Counts come from the user's enrolments and course completion. A course with
 * no completion setup is counted only in the total.
 *
 * @return array|null
 */
function theme_nextgen_dashboard_context(): ?array {
    global $DB, $PAGE, $USER;

    if ($PAGE->pagelayout !== 'mydashboard' || !isloggedin() || isguestuser()) {
        return null;
    }

    $name = trim((string) $USER->firstname);
    if ($name === '') {
        $name = fullname($USER);
    }

    $courses = enrol_get_my_courses(['enablecompletion']);
    $all = count($courses);
    $inprogress = 0;
    $past = 0;
    foreach ($courses as $course) {
        $percent = \core_completion\progress::get_course_progress_percentage($course);
        if ($percent === null) {
            continue;
        }
        if ($percent >= 100) {
            $past++;
        } else if ($percent > 0) {
            $inprogress++;
        }
    }

    $radius = 46;
    $circ = round(2 * pi() * $radius, 2);
    $offset = function (int $count, bool $full = false) use ($all, $circ): string {
        if ($all <= 0 || $count <= 0) {
            return (string) $circ;
        }
        $portion = $full ? 1 : min(1, $count / $all);
        return (string) round($circ * (1 - $portion), 2);
    };

    $recentname = '';
    $recenturl = '';
    $continuetitle = '';
    $continuecontext = '';
    $continueurl = (new moodle_url('/my/courses.php'))->out(false);
    $enrolled = enrol_get_my_courses(['id', 'shortname', 'fullname'], 'sortorder ASC');
    if (!empty($enrolled)) {
        $firstenrolled = reset($enrolled);
        $continueurl = (new moodle_url('/course/view.php', ['id' => (int) $firstenrolled->id]))->out(false);
        $continuetitle = format_string($firstenrolled->fullname, true, [
            'context' => context_course::instance($firstenrolled->id),
            'escape' => false,
        ]);
    }
    if ($DB->get_manager()->table_exists('block_recentlyaccessedcourses')) {
        $recent = $DB->get_record_sql(
            "SELECT c.id, c.fullname
               FROM {block_recentlyaccessedcourses} r
               JOIN {course} c ON c.id = r.courseid
              WHERE r.userid = :userid
           ORDER BY r.timeaccess DESC",
            ['userid' => $USER->id],
            IGNORE_MULTIPLE
        );
        if ($recent && isset($enrolled[(int) $recent->id])) {
            $context = context_course::instance($recent->id);
            $recentname = format_string($recent->fullname, true, [
                'context' => $context,
                'escape' => false,
            ]);
            $recenturl = (new moodle_url('/course/view.php', ['id' => $recent->id]))->out(false);
            $continueurl = $recenturl;
            $continuetitle = $recentname;
        }
    }
    if ($DB->get_manager()->table_exists('block_recentlyaccesseditems')) {
        $item = $DB->get_record_sql(
            "SELECT r.courseid, r.cmid
               FROM {block_recentlyaccesseditems} r
              WHERE r.userid = :userid
           ORDER BY r.timeaccess DESC",
            ['userid' => $USER->id],
            IGNORE_MULTIPLE
        );
        if ($item && isset($enrolled[(int) $item->courseid])) {
            try {
                [$itemcourse, $cm] = get_course_and_cm_from_cmid((int) $item->cmid, '', (int) $item->courseid);
                if (!empty($cm->uservisible) && $cm->url instanceof moodle_url) {
                    $continuetitle = $cm->get_formatted_name();
                    $continueurl = $cm->url->out(false);
                    $continuecontext = get_string(
                        'dashboardlessonin',
                        'theme_nextgen',
                        format_string($itemcourse->fullname, true, [
                            'context' => context_course::instance($itemcourse->id),
                            'escape' => false,
                        ])
                    );
                }
            } catch (moodle_exception $e) {
                // The activity was removed, or this learner can no longer open it.
                debugging($e->getMessage(), DEBUG_DEVELOPER);
            }
        }
    }

    $lastlogin = '';
    if (!empty($USER->lastlogin)) {
        $lastlogin = format_time(max(1, time() - (int) $USER->lastlogin));
    }

    return [
        'title' => get_string('myhome'),
        'hello' => get_string('dashboardhello', 'theme_nextgen', $name),
        'today' => get_string('dashboardtoday', 'theme_nextgen', userdate(time(), get_string('strftimedaydate', 'langconfig'))),
        'haslastlogin' => $lastlogin !== '',
        'lastlogin' => get_string('dashboardlastlogin', 'theme_nextgen', $lastlogin),
        'hasrecent' => $recentname !== '',
        'recentname' => $recentname,
        'recenturl' => $recenturl,
        'hascontinue' => $continuetitle !== '',
        'continuetitle' => $continuetitle,
        'continuecontext' => $continuecontext,
        'continueurl' => $continueurl,
        'ready' => get_string('dashboardready', 'theme_nextgen'),
        'allcount' => $all,
        'inprogresscount' => $inprogress,
        'pastcount' => $past,
        'circumference' => (string) $circ,
        'alloffset' => $offset($all, true),
        'inprogressoffset' => $offset($inprogress),
        'pastoffset' => $offset($past),
    ];
}

/**
 * Serve files from theme settings.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool
 */
function theme_nextgen_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    $allowed = ['logo', 'favicon', 'loginbackgroundimage'];
    if ($context->contextlevel == CONTEXT_SYSTEM && in_array($filearea, $allowed, true)) {
        $theme = theme_config::load('nextgen');
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        return $theme->setting_file_serve($filearea, $args, $forcedownload, $options);
    }
    send_file_not_found();
}

/**
 * SCSS content inherited from Boost.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_nextgen_get_main_scss_content($theme) {
    return theme_boost_get_main_scss_content($theme);
}

/**
 * Precompiled Boost CSS, used when SCSS compilation is unavailable.
 *
 * @return string
 */
function theme_nextgen_get_precompiled_css() {
    return theme_boost_get_precompiled_css();
}

/**
 * SCSS variables prepended before the Boost preset.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_nextgen_get_pre_scss($theme) {
    $primary = theme_nextgen_color(theme_nextgen_setting($theme, 'primarycolor'), '#163A4A');
    return '$primary: ' . $primary . ";\n" .
        '$success: #116306;' . "\n" .
        '$warning: #FCD116;' . "\n" .
        '$danger: #CE1126;' . "\n";
}

/**
 * Extra CSS appended after the Boost preset.
 *
 * Brand tokens are emitted here so theme settings can change them without a Tailwind rebuild.
 * The compiled Tailwind sheet is loaded after this file and references the same variables.
 *
 * @param theme_config $theme
 * @return string
 */
function theme_nextgen_get_extra_scss($theme) {
    $primary = theme_nextgen_color(theme_nextgen_setting($theme, 'primarycolor'), '#163A4A');
    $secondary = theme_nextgen_color(theme_nextgen_setting($theme, 'secondarycolor'), '#102833');
    $accent = theme_nextgen_color(theme_nextgen_setting($theme, 'accentcolor'), '#FCD116');

    // Vivid green and yellow fail text contrast on white; use them as fills. Anything on yellow uses brand-ink.
    $css = ":root {\n";
    $css .= "--ng-brand: #23AB0C;\n";
    $css .= "--ng-brand-dark: #116306;\n";
    $css .= "--ng-brand-ink: #0A3A03;\n";
    $css .= "--ng-brand-soft: #E9F7E6;\n";
    $css .= "--ng-yellow: #FCD116;\n";
    $css .= "--ng-yellow-hover: #FDE05A;\n";
    $css .= "--ng-yellow-soft: #FFF7D1;\n";
    $css .= "--ng-red: #CE1126;\n";
    $css .= "--ng-red-soft: #FCE8EA;\n";
    $css .= '--ng-primary: ' . $primary . ";\n";
    $css .= '--ng-primary-hover: ' . theme_nextgen_darken($primary, 0.12) . ";\n";
    $css .= '--ng-primary-foreground: #FFFFFF' . ";\n";
    $css .= '--ng-secondary: ' . $secondary . ";\n";
    $css .= '--ng-accent: ' . $accent . ";\n";
    $css .= "--ng-gold: #C4842A;\n";
    $css .= "--ng-gold-text: #8A5410;\n";
    $css .= "--ng-gold-soft: #F8EBD3;\n";
    $css .= "--ng-clay: #C45C3E;\n";
    $css .= "--ng-ink: #163A4A;\n";
    $css .= "--ng-background: #F8F9FA;\n";
    $css .= "--ng-surface: #FFFFFF;\n";
    $css .= "--bs-body-bg: #F8F9FA;\n";
    $css .= "--bs-body-bg-rgb: 248, 249, 250;\n";
    $css .= "--bs-white: #FFFFFF;\n";
    $css .= "--bs-white-rgb: 255, 255, 255;\n";
    $css .= "--bs-card-bg: #FFFFFF;\n";
    $css .= "--bs-card-cap-bg: #FFFFFF;\n";
    $css .= "--ng-muted: #EEF2F4;\n";
    $css .= "--ng-border: #E1E6EA;\n";
    $css .= "--ng-text: #1A2330;\n";
    $css .= "--ng-text-muted: #5C6770;\n";
    $css .= "--ng-success: #116306;\n";
    $css .= "--ng-warning: #8A5410;\n";
    $css .= "--ng-error: #CE1126;\n";
    $css .= "--ng-info: #1F5C73;\n";
    $css .= "--ng-radius-sm: 6px;\n";
    $css .= "--ng-radius-md: 10px;\n";
    $css .= "--ng-radius-lg: 16px;\n";
    $css .= "--ng-shadow-sm: 0 1px 2px rgb(16 40 51 / 0.06), inset 0 1px 0 rgb(255 255 255 / 0.8);\n";
    $css .= "--ng-shadow-md: 0 14px 32px rgb(16 40 51 / 0.12), inset 0 1px 0 rgb(255 255 255 / 0.85);\n";
    $css .= "}\n";

    $css .= theme_nextgen_font_faces();

    $shell = __DIR__ . '/scss/shell.scss';
    if (is_readable($shell)) {
        $css .= file_get_contents($shell);
    }

    $loginbackground = $theme->setting_file_url('loginbackgroundimage', 'loginbackgroundimage');
    if (!empty($loginbackground)) {
        $loginbackground = str_replace(["'", '\\', "\n", "\r"], '', $loginbackground);
        $css .= 'body.nextgen.pagelayout-login .ng-login-panel {';
        $css .= "background-image: url('" . $loginbackground . "');";
        $css .= "}\n";
    }

    $custom = theme_nextgen_setting($theme, 'customcss');
    if ($custom !== '') {
        $custom = str_replace(["\0", '</style'], '', $custom);
        $css .= "\n" . $custom . "\n";
    }

    return $css;
}

/**
 * @font-face rules pointing at self-hosted Manrope files.
 *
 * @return string
 */
function theme_nextgen_font_faces(): string {
    $weights = [
        400 => 'manrope-latin-400-normal.woff2',
        500 => 'manrope-latin-500-normal.woff2',
        600 => 'manrope-latin-600-normal.woff2',
        700 => 'manrope-latin-700-normal.woff2',
    ];
    $css = '';
    foreach ($weights as $weight => $file) {
        if (!is_readable(__DIR__ . '/fonts/' . $file)) {
            continue;
        }
        $url = (new moodle_url('/theme/nextgen/fonts/' . $file))->out(false);
        $css .= "@font-face{font-family:'Manrope';font-style:normal;font-weight:{$weight};font-display:swap;";
        $css .= "src:url('{$url}') format('woff2');}\n";
    }
    return $css;
}

/**
 * Read a theme setting as a string.
 *
 * @param theme_config $theme
 * @param string $name
 * @param string $default
 * @return string
 */
function theme_nextgen_setting($theme, string $name, string $default = ''): string {
    if (!isset($theme->settings->{$name}) || $theme->settings->{$name} === '') {
        return $default;
    }
    return (string) $theme->settings->{$name};
}

/**
 * Theme copy that should follow the current language.
 *
 * A saved value is kept when an administrator has changed it. The English
 * default is replaced with the string for the language in use.
 *
 * @param theme_config $theme
 * @param string $name
 * @param string $stringkey
 * @return string
 */
function theme_nextgen_localised_setting($theme, string $name, string $stringkey): string {
    $stored = trim(theme_nextgen_setting($theme, $name));
    $english = get_string_manager()->get_string($stringkey, 'theme_nextgen', null, 'en');
    if ($stored === '' || $stored === $english) {
        return get_string($stringkey, 'theme_nextgen');
    }
    return $stored;
}

/**
 * Translate a link or office label when it is still the English default.
 *
 * @param string $label
 * @return string
 */
function theme_nextgen_localise_label(string $label): string {
    $label = trim($label);
    if ($label === '') {
        return $label;
    }
    $pairs = [
        ['core', 'home'],
        ['core', 'myhome'],
        ['theme_nextgen', 'coursesnav'],
        ['theme_nextgen', 'categoriesnav'],
        ['theme_nextgen', 'explorecourses'],
        ['theme_nextgen', 'learnmore'],
        ['theme_nextgen', 'mylearning'],
        ['theme_nextgen', 'aboutnav'],
        ['theme_nextgen', 'teamnav'],
        ['theme_nextgen', 'contactnav'],
        ['theme_nextgen', 'blognav'],
        ['theme_nextgen', 'faqnav'],
        ['theme_nextgen', 'browseall'],
        ['theme_nextgen', 'privacytitle'],
        ['theme_nextgen', 'termstitle'],
        ['theme_nextgen', 'officemain'],
        ['theme_nextgen', 'officebranch'],
    ];
    $manager = get_string_manager();
    foreach ($pairs as [$component, $key]) {
        if ($label === $manager->get_string($key, $component, null, 'en')) {
            return get_string($key, $component);
        }
    }
    return $label;
}

/**
 * Accept only hex colours so settings cannot inject CSS.
 *
 * @param string $value
 * @param string $fallback
 * @return string
 */
function theme_nextgen_color(string $value, string $fallback): string {
    $value = trim($value);
    if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
        return $value;
    }
    return $fallback;
}

/**
 * Darken a hex colour by mixing it toward black.
 *
 * @param string $hex
 * @param float $amount 0 to 1
 * @return string
 */
function theme_nextgen_darken(string $hex, float $amount): string {
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $channels = [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
    foreach ($channels as $index => $channel) {
        $channels[$index] = max(0, (int) round($channel * (1 - $amount)));
    }
    return sprintf('#%02X%02X%02X', $channels[0], $channels[1], $channels[2]);
}

/**
 * Parse "Label|/url" lines into safe link records.
 *
 * @param string $raw
 * @param bool $localise Replace English default labels with the current language
 * @return array<int, array{label: string, url: string, external: bool}>
 */
function theme_nextgen_parse_links(string $raw, bool $localise = true): array {
    $items = [];
    foreach (preg_split("/\r\n|\n|\r/", $raw) as $line) {
        $line = trim($line);
        if ($line === '' || !str_contains($line, '|')) {
            continue;
        }
        [$label, $url] = array_map('trim', explode('|', $line, 2));
        $external = (bool) preg_match('#^https?://#i', $url);
        $safe = theme_nextgen_safe_url($url);
        if ($label === '' || $safe === null) {
            continue;
        }
        $items[] = [
            'label' => $localise ? theme_nextgen_localise_label($label) : $label,
            'url' => $safe,
            'external' => $external,
        ];
    }
    return $items;
}

/**
 * Normalise an internal path or absolute URL.
 *
 * @param string $url
 * @return string|null
 */
function theme_nextgen_safe_url(string $url): ?string {
    $url = trim($url);
    if ($url === '') {
        return null;
    }
    if (preg_match('/^#[a-z0-9_-]+$/i', $url)) {
        return $url;
    }
    if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
        return (new moodle_url($url))->out(false);
    }
    $clean = clean_param($url, PARAM_URL);
    if (empty($clean) || !preg_match('#^https?://#i', $clean)) {
        return null;
    }
    return $clean;
}

/**
 * Custom primary-navigation items from theme settings.
 *
 * @return array<int, array{label: string, url: string, key: string}>
 */
function theme_nextgen_custom_nav_items(): array {
    if (during_initial_install()) {
        return [];
    }
    $theme = theme_config::load('nextgen');
    $raw = theme_nextgen_setting($theme, 'customnav');
    $separated = preg_replace(
        '#^(Categories\|/course/index\.php)\s*$#m',
        'Categories|/course/index.php?browse=categories',
        $raw
    );
    if (is_string($separated) && $separated !== $raw) {
        set_config('customnav', $separated, 'theme_nextgen');
        $raw = $separated;
    }
    $items = [];
    foreach (theme_nextgen_parse_links($raw, false) as $link) {
        $slug = preg_replace('/[^a-z0-9]+/', '', strtolower($link['label']));
        $items[] = [
            'label' => theme_nextgen_localise_label($link['label']),
            'url' => $link['url'],
            'key' => 'nextgen-' . ($slug !== '' ? $slug : 'link'),
        ];
    }
    return $items;
}

/**
 * Template context for the marketing footer columns.
 *
 * @param theme_config $theme
 * @return array
 */
function theme_nextgen_footer_context($theme): array {
    $about = theme_nextgen_localised_setting($theme, 'footerabout', 'footeraboutdefault');

    $columns = [];
    $groups = [
        'quicklinks' => get_string('quicklinks', 'theme_nextgen'),
        'learninglinks' => get_string('learninglinks', 'theme_nextgen'),
        'supportlinks' => get_string('supportlinks', 'theme_nextgen'),
    ];
    foreach ($groups as $setting => $title) {
        $links = theme_nextgen_parse_links(theme_nextgen_setting($theme, $setting));
        if ($links) {
            $columns[] = [
                'title' => $title,
                'links' => $links,
            ];
        }
    }

    $social = \theme_nextgen\local\social::links();

    $email = trim(theme_nextgen_setting($theme, 'contactemail'));
    if ($email !== '' && !validate_email($email)) {
        $email = '';
    }
    $phone = trim(theme_nextgen_setting($theme, 'contactphone'));
    $dial = preg_replace('/[^0-9+]/', '', $phone);
    $offices = \theme_nextgen\local\organisation::offices();

    $copyright = theme_nextgen_setting($theme, 'copyright');
    if ($copyright === '') {
        $copyright = get_string('copyrightdefault', 'theme_nextgen', (object) [
            'year' => date('Y'),
            'name' => theme_nextgen_app_name(),
        ]);
    }

    $privacy = theme_nextgen_safe_url(theme_nextgen_setting($theme, 'privacyurl'));
    if ($privacy === null) {
        $privacy = \theme_nextgen\local\public_page::url('privacy')->out(false);
    }
    $terms = theme_nextgen_safe_url(theme_nextgen_setting($theme, 'termsurl'));
    if ($terms === null) {
        $terms = \theme_nextgen\local\public_page::url('terms')->out(false);
    }
    $columns = theme_nextgen_with_faq_link($columns);
    $columns = theme_nextgen_with_blog_link($columns);
    $columns = theme_nextgen_with_team_link($columns);

    return [
        'sitename' => theme_nextgen_app_name(),
        'about' => format_text($about, FORMAT_HTML, ['context' => context_system::instance()]),
        'columns' => $columns,
        'hassocial' => !empty($social),
        'social' => $social,
        'variant' => 'footer',
        'showlabels' => false,
        'hascontact' => ($email !== '' || $phone !== '' || $offices),
        'offices' => $offices,
        'contactemail' => $email,
        'contactphone' => $phone,
        'contactphonehref' => $dial,
        'copyright' => $copyright,
        'privacyurl' => $privacy,
        'termsurl' => $terms,
        'partnersblock' => \theme_nextgen\local\organisation::partners_block('footer'),
    ];
}

/**
 * Add the FAQ link to the Support column when it is not already there.
 *
 * @param array $columns
 * @return array
 */
function theme_nextgen_with_faq_link(array $columns): array {
    $faqurl = \theme_nextgen\local\public_page::url('faq')->out(false);
    foreach ($columns as $column) {
        foreach ($column['links'] as $link) {
            if ($link['url'] === $faqurl) {
                return $columns;
            }
        }
    }

    $faqlink = [
        'label' => get_string('faqnav', 'theme_nextgen'),
        'url' => $faqurl,
        'external' => false,
    ];
    $supporttitle = get_string('supportlinks', 'theme_nextgen');
    foreach ($columns as $index => $column) {
        if ($column['title'] === $supporttitle) {
            $columns[$index]['links'][] = $faqlink;
            return $columns;
        }
    }
    $columns[] = [
        'title' => $supporttitle,
        'links' => [$faqlink],
    ];
    return $columns;
}

/**
 * Add the blog to Quick links when blogs are enabled and the column does not already list it.
 *
 * @param array $columns
 * @return array
 */
function theme_nextgen_with_blog_link(array $columns): array {
    global $CFG;

    if (empty($CFG->enableblogs)) {
        return $columns;
    }

    $blogurl = (new moodle_url('/blog/index.php'))->out(false);
    foreach ($columns as $column) {
        foreach ($column['links'] as $link) {
            if ($link['url'] === $blogurl) {
                return $columns;
            }
        }
    }

    $bloglink = [
        'label' => get_string('blognav', 'theme_nextgen'),
        'url' => $blogurl,
        'external' => false,
    ];
    $abouturl = (new moodle_url('/about.php'))->out(false);
    $contacturl = (new moodle_url('/contact.php'))->out(false);
    foreach ($columns as $index => $column) {
        foreach ($column['links'] as $linkindex => $link) {
            if ($link['url'] === $abouturl || $link['url'] === $contacturl) {
                array_splice($columns[$index]['links'], $linkindex, 0, [$bloglink]);
                return $columns;
            }
        }
    }
    $quicktitle = get_string('quicklinks', 'theme_nextgen');
    foreach ($columns as $index => $column) {
        if ($column['title'] === $quicktitle) {
            $columns[$index]['links'][] = $bloglink;
            return $columns;
        }
    }
    $columns[] = [
        'title' => $quicktitle,
        'links' => [$bloglink],
    ];
    return $columns;
}

/**
 * Add the team page to Quick links when that column does not already list it.
 *
 * @param array $columns
 * @return array
 */
function theme_nextgen_with_team_link(array $columns): array {
    $teamurl = \theme_nextgen\local\public_page::url('team')->out(false);
    foreach ($columns as $column) {
        foreach ($column['links'] as $link) {
            if ($link['url'] === $teamurl) {
                return $columns;
            }
        }
    }

    $teamlink = [
        'label' => get_string('teamnav', 'theme_nextgen'),
        'url' => $teamurl,
        'external' => false,
    ];
    $quicktitle = get_string('quicklinks', 'theme_nextgen');
    foreach ($columns as $index => $column) {
        if ($column['title'] === $quicktitle) {
            $columns[$index]['links'][] = $teamlink;
            return $columns;
        }
    }
    $columns[] = [
        'title' => $quicktitle,
        'links' => [$teamlink],
    ];
    return $columns;
}

/**
 * Point shipped navigation at the public pages when a site still has the original defaults.
 */
function theme_nextgen_upgrade_public_pages(): void {
    $customnav = get_config('theme_nextgen', 'customnav');
    if (is_string($customnav)) {
        $legacyheaders = [
            "Courses|/course/index.php\nCategories|/course/index.php\nContact|/user/contactsitesupport.php",
            "Courses|/course/index.php\nCategories|/course/index.php?browse=categories\nContact|/user/contactsitesupport.php",
        ];
        if (in_array(theme_nextgen_normalise_lines($customnav), $legacyheaders, true)) {
            set_config('customnav', "Courses|/course/index.php", 'theme_nextgen');
        }
    }

    $quicklinks = get_config('theme_nextgen', 'quicklinks');
    if (is_string($quicklinks) && theme_nextgen_normalise_lines($quicklinks) === "Home|/\nCourses|/course/index.php") {
        set_config(
            'quicklinks',
            "Home|/\nCourses|/course/index.php\nCategories|/course/index.php?browse=categories",
            'theme_nextgen'
        );
    }

    $supportlinks = get_config('theme_nextgen', 'supportlinks');
    if (is_string($supportlinks) && theme_nextgen_normalise_lines($supportlinks) === 'Contact support|/user/contactsitesupport.php') {
        set_config('supportlinks', "Frequently asked questions|/faq.php", 'theme_nextgen');
    }

    if (!get_config('theme_nextgen', 'privacyurl')) {
        set_config('privacyurl', '/privacy.php', 'theme_nextgen');
    }
    if (!get_config('theme_nextgen', 'termsurl')) {
        set_config('termsurl', '/terms.php', 'theme_nextgen');
    }
}

/**
 * Move stored links off /theme/nextgen and publish the public pages at the site root.
 *
 * The pages stay implemented by this theme. The site-root scripts only hand the
 * request to that code, so the address bar does not show the theme directory.
 */
function theme_nextgen_publish_public_paths(): void {
    global $CFG;

    if (during_initial_install() || empty($CFG->dirroot)) {
        return;
    }
    if (get_config('theme_nextgen', 'publicpathrev') === '2026102201') {
        return;
    }

    $ready = theme_nextgen_retire_public_scripts();
    $ready = theme_nextgen_ensure_public_scripts() && $ready;
    $ready = theme_nextgen_ensure_learn_script() && $ready;
    $ready = theme_nextgen_ensure_public_rewrites() && $ready;
    theme_nextgen_rewrite_public_urls();
    if ($ready) {
        set_config('publicpathrev', '2026102201', 'theme_nextgen');
    }
}

/**
 * Remove a public page this theme used to publish at the site root.
 *
 * Only a script that carries the theme marker is deleted. A file the site
 * created itself is left in place. Stored footer and header lines that still
 * point at the retired address are dropped.
 *
 * @return bool
 */
function theme_nextgen_retire_public_scripts(): bool {
    global $CFG;

    $ready = true;
    foreach (['programmes'] as $key) {
        $path = $CFG->dirroot . DIRECTORY_SEPARATOR . $key . '.php';
        if (!is_file($path)) {
            continue;
        }
        $head = (string) file_get_contents($path, false, null, 0, 900);
        if (!str_contains($head, 'theme_nextgen public page:')) {
            continue;
        }
        if (!unlink($path)) {
            $ready = false;
        }
    }

    foreach (['customnav', 'quicklinks', 'learninglinks', 'supportlinks'] as $name) {
        $value = get_config('theme_nextgen', $name);
        if (!is_string($value) || !str_contains($value, '/programmes.php')) {
            continue;
        }
        $kept = [];
        foreach (preg_split("/\r\n|\n|\r/", $value) as $line) {
            if (!str_contains($line, '/programmes.php')) {
                $kept[] = $line;
            }
        }
        set_config($name, implode("\n", $kept), 'theme_nextgen');
    }

    return $ready;
}

/**
 * Write the site-root scripts for About, Team, Contact, Privacy, Terms, and FAQ.
 *
 * A file that is already there and was not created for this theme is left alone.
 *
 * @return bool True when every page is either published or intentionally left as a foreign file
 */
function theme_nextgen_ensure_public_scripts(): bool {
    global $CFG;

    $ready = true;
    foreach (\theme_nextgen\local\public_page::keys() as $key) {
        $path = $CFG->dirroot . DIRECTORY_SEPARATOR . $key . '.php';
        if (is_file($path)) {
            $head = (string) file_get_contents($path, false, null, 0, 900);
            if (!str_contains($head, 'theme_nextgen public page:')) {
                continue;
            }
            if (str_contains($head, "public_page::render('{$key}')")) {
                continue;
            }
        }
        $written = file_put_contents($path, theme_nextgen_public_script_source($key));
        if ($written === false) {
            $ready = false;
        }
    }
    return $ready;
}

/**
 * PHP source for one site-root public page.
 *
 * @param string $key
 * @return string
 */
function theme_nextgen_public_script_source(string $key): string {
    $source = <<<'PHP'
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

/**
 * theme_nextgen public page: KEY
 *
 * Published at the site root so the address does not include the theme directory.
 * The page itself is rendered by theme_nextgen.
 *
 * @package    core
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/config.php');

defined('MOODLE_INTERNAL') || die();

\theme_nextgen\local\public_page::render('KEY');

PHP;
    return str_replace('KEY', $key, $source);
}

/**
 * Write the site-root learn.php that serves readable module addresses.
 *
 * A file that is already there and was not created for this theme is left alone.
 *
 * @return bool
 */
function theme_nextgen_ensure_learn_script(): bool {
    global $CFG;

    $path = $CFG->dirroot . DIRECTORY_SEPARATOR . 'learn.php';
    if (is_file($path)) {
        $head = (string) file_get_contents($path);
        if (!str_contains($head, 'theme_nextgen learn page')) {
            return true;
        }
        if (str_contains($head, 'is_string($sectionfile)')) {
            return true;
        }
    }

    $source = <<<'PHP'
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

/**
 * theme_nextgen learn page
 *
 * Published at the site root so a module address does not include the theme directory.
 * The page itself is Moodle's course section, rendered by theme_nextgen.
 *
 * @package    core
 * @copyright  2026 NextGen LMS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/config.php');

defined('MOODLE_INTERNAL') || die();

$sectionfile = \theme_nextgen\local\module_page::dispatch();
if (is_string($sectionfile)) {
    require($sectionfile);
    exit(0);
}

PHP;
    return file_put_contents($path, $source) !== false;
}

/**
 * Map extensionless public addresses onto the site-root scripts.
 *
 * /contact and /contact.php both run the contact page. The same applies to
 * about, team, privacy, terms, and faq, including a trailing slash and the older
 * /theme/nextgen/ addresses. /learn/{course}/{module} runs learn.php.
 * /en and /fr (and the other switcher languages) stay in the address and are
 * removed here so Moodle still receives the real script.
 * The substitution is an absolute URL path and the flag is END, because a
 * per-directory L flag still lets FallbackResource answer the original
 * address with a 404.
 *
 * @return bool
 */
function theme_nextgen_ensure_public_rewrites(): bool {
    global $CFG;

    $path = $CFG->dirroot . DIRECTORY_SEPARATOR . '.htaccess';
    $start = '# theme_nextgen public pages';
    $end = '# end theme_nextgen public pages';
    $keys = implode('|', \theme_nextgen\local\public_page::keys());
    $prefix = parse_url($CFG->wwwroot, PHP_URL_PATH);
    if (!is_string($prefix) || $prefix === '/') {
        $prefix = '';
    }
    $prefix = rtrim($prefix, '/');
    $base = ($prefix === '') ? '/' : $prefix . '/';
    $langs = 'en|fr|it|es';
    $home = ($prefix === '') ? '/index.php' : $prefix . '/index.php';
    $block = $start . "\n" .
        "RewriteEngine On\n" .
        'RewriteBase ' . $base . "\n" .
        'RewriteRule ^(' . $langs . ')/(' . $keys . ')/?$ ' . $prefix . '/$2.php [END,QSA]' . "\n" .
        'RewriteRule ^(' . $langs . ')/theme/nextgen/(' . $keys . ')/?$ ' . $prefix . '/$2.php [END,QSA]' . "\n" .
        'RewriteRule ^(' . $langs . ')/learn/([a-z0-9-]+)/([a-z0-9-]+)/?$ ' . $prefix .
            '/learn.php?course=$2&module=$3 [END,QSA]' . "\n" .
        'RewriteRule ^(' . $langs . ')/?$ ' . $home . ' [END,QSA]' . "\n" .
        'RewriteRule ^(' . $langs . ')/(.+)$ ' . $prefix . '/$2 [END,QSA]' . "\n" .
        'RewriteRule ^(' . $keys . ')/?$ ' . $prefix . '/$1.php [END,QSA]' . "\n" .
        'RewriteRule ^theme/nextgen/(' . $keys . ')/?$ ' . $prefix . '/$1.php [END,QSA]' . "\n" .
        'RewriteRule ^learn/([a-z0-9-]+)/([a-z0-9-]+)/?$ ' . $prefix . '/learn.php?course=$1&module=$2 [END,QSA]' . "\n" .
        $end . "\n";

    $current = is_file($path) ? (string) file_get_contents($path) : "DirectoryIndex index.php\n";
    $pattern = '/' . preg_quote($start, '/') . '.*?' . preg_quote($end, '/') . '\R?/s';
    if (preg_match($pattern, $current)) {
        // A plain preg_replace replacement treats $1 in the block as a backreference.
        $updated = preg_replace_callback($pattern, function() use ($block) {
            return $block;
        }, $current, 1);
    } else {
        $updated = rtrim($current) . "\n\n" . $block;
    }
    if (!is_string($updated) || $updated === $current) {
        return is_file($path) && str_contains($current, $block);
    }
    return file_put_contents($path, $updated) !== false;
}

/**
 * Replace theme-directory addresses already saved in theme settings.
 */
function theme_nextgen_rewrite_public_urls(): void {
    $map = [
        '/theme/nextgen/about.php' => '/about.php',
        '/theme/nextgen/team.php' => '/team.php',
        '/theme/nextgen/contact.php' => '/contact.php',
        '/theme/nextgen/privacy.php' => '/privacy.php',
        '/theme/nextgen/terms.php' => '/terms.php',
        '/theme/nextgen/faq.php' => '/faq.php',
        '/theme/nextgen/course.php' => '/course/view.php',
    ];
    $settings = ['customnav', 'quicklinks', 'learninglinks', 'supportlinks', 'privacyurl', 'termsurl'];
    foreach ($settings as $name) {
        $value = get_config('theme_nextgen', $name);
        if (!is_string($value) || !str_contains($value, '/theme/nextgen/')) {
            continue;
        }
        $updated = str_replace(array_keys($map), array_values($map), $value);
        if ($updated !== $value) {
            set_config($name, $updated, 'theme_nextgen');
        }
    }
}

/**
 * Compare link settings without empty or mixed line endings.
 *
 * @param string $raw
 * @return string
 */
function theme_nextgen_normalise_lines(string $raw): string {
    $lines = preg_split("/\r\n|\n|\r/", trim($raw));
    $kept = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line !== '') {
            $kept[] = $line;
        }
    }
    return implode("\n", $kept);
}

/**
 * HTML for the site home landing page, or an empty string on other layouts.
 *
 * Editing the front page shows Moodle's own front page instead of the marketing layout.
 *
 * @return string
 */
function theme_nextgen_frontpage_landing(): string {
    global $OUTPUT, $PAGE;

    if ($PAGE->pagelayout !== 'frontpage' || $PAGE->user_is_editing()) {
        return '';
    }

    return $OUTPUT->render_from_template('theme_nextgen/landing', theme_nextgen_landing_context());
}

/**
 * Context for the site home landing page.
 *
 * Course and category data comes from Moodle. Marketing copy comes from theme settings.
 *
 * @return array
 */
function theme_nextgen_landing_context(): array {
    $theme = theme_config::load('nextgen');
    $courses = theme_nextgen_featured_courses();
    $categories = theme_nextgen_landing_categories(0);
    $instructors = theme_nextgen_landing_instructors();

    $primary = theme_nextgen_parse_links(theme_nextgen_setting(
        $theme,
        'heroprimary',
        get_string('heroprimarydefault', 'theme_nextgen')
    ));
    $signedin = isloggedin() && !isguestuser();
    $secondary = [[
        'label' => get_string($signedin ? 'dashboardcontinue' : 'herostartnow', 'theme_nextgen'),
        'url' => (new moodle_url($signedin ? '/my/' : '/login/index.php'))->out(false),
        'external' => false,
    ]];

    $herotitle = theme_nextgen_localised_setting($theme, 'herotitle', 'herotitledefault');
    $herotext = theme_nextgen_localised_setting($theme, 'herotext', 'herotextdefault');

    $tones = ['primary', 'clay', 'ink', 'gold'];
    $reasons = [];
    foreach ([1, 2, 3, 4] as $offset => $index) {
        $reasons[] = [
            'title' => get_string('reason' . $index . 'title', 'theme_nextgen'),
            'text' => get_string('reason' . $index . 'text', 'theme_nextgen'),
            'tone' => $tones[$offset],
        ];
    }
    $facultypoints = [];
    foreach ([1, 2, 3] as $index) {
        $facultypoints[] = [
            'title' => get_string('instructorpoint' . $index, 'theme_nextgen'),
        ];
    }
    $steps = [];
    foreach ([1, 2, 3] as $index) {
        $steps[] = [
            'number' => $index,
            'title' => get_string('step' . $index . 'title', 'theme_nextgen'),
            'text' => get_string('step' . $index . 'text', 'theme_nextgen'),
        ];
    }
    $benefits = [];
    foreach ([1, 2, 3, 4] as $index) {
        $benefits[] = [
            'title' => get_string('benefit' . $index . 'title', 'theme_nextgen'),
            'text' => get_string('benefit' . $index . 'text', 'theme_nextgen'),
        ];
    }

    $quotes = theme_nextgen_landing_quotes();
    $offers = [];
    $offericons = ['packages', 'cohorts', 'custom'];
    foreach ([1, 2, 3] as $offset => $index) {
        $icon = $offericons[$offset];
        $offers[] = [
            'title' => get_string('institution' . $index . 'title', 'theme_nextgen'),
            'text' => get_string('institution' . $index . 'text', 'theme_nextgen'),
            'packages' => $icon === 'packages',
            'cohorts' => $icon === 'cohorts',
            'custom' => $icon === 'custom',
        ];
    }

    return [
        'herotitle' => $herotitle,
        'herotext' => $herotext,
        'hasprimary' => !empty($primary),
        'primary' => $primary[0] ?? null,
        'hassecondary' => !empty($secondary),
        'secondary' => $secondary[0] ?? null,
        'stats' => \theme_nextgen\local\platform_stats::for_landing(),
        'hascourses' => !empty($courses),
        'courses' => $courses,
        'hascategories' => !empty($categories),
        'categories' => $categories,
        'hasinstructors' => !empty($instructors),
        'instructors' => $instructors,
        'instructorcount' => count($instructors),
        'hasslider' => count($instructors) > 1,
        'facultypoints' => $facultypoints,
        'reasons' => $reasons,
        'steps' => $steps,
        'benefits' => $benefits,
        'ctaimage' => $theme->image_url('hero-home', 'theme')->out(false),
        'coursesurl' => (new moodle_url('/course/index.php'))->out(false),
        'contacturl' => \theme_nextgen\local\public_page::url('contact')->out(false),
        'loginurl' => (new moodle_url('/login/index.php'))->out(false),
        'offers' => $offers,
        'isloggedin' => $signedin,
        'partnersblock' => \theme_nextgen\local\organisation::partners_block('landing'),
        'forwardtext' => theme_nextgen_catalogue_lead('forwardtext'),
        'hasquotes' => !empty($quotes),
        'quotes' => $quotes,
    ];
}

/**
 * Noun phrase for the number of published courses.
 *
 * Lower case, so it can sit in the middle of a sentence. Pass $lead when it opens one.
 *
 * @param bool $lead
 * @return string
 */
function theme_nextgen_catalogue_size_label(bool $lead = false): string {
    $count = \theme_nextgen\local\platform_stats::published_count();
    if ($count === 1) {
        $label = get_string('cataloguesizeone', 'theme_nextgen');
    } else if ($count < 1) {
        $label = get_string('cataloguesizenone', 'theme_nextgen');
    } else {
        $label = get_string('cataloguesize', 'theme_nextgen', $count);
    }
    if ($lead && $label !== '') {
        $label = \core_text::strtoupper(\core_text::substr($label, 0, 1)) . \core_text::substr($label, 1);
    }
    return $label;
}

/**
 * A catalogue sentence whose {$a} is the published course count.
 *
 * @param string $stringkey
 * @return string
 */
function theme_nextgen_catalogue_lead(string $stringkey): string {
    return get_string($stringkey, 'theme_nextgen', theme_nextgen_catalogue_size_label(true));
}

/**
 * About paragraph that names the published course count without overstating it.
 *
 * @return string
 */
function theme_nextgen_about_who_text(): string {
    $count = \theme_nextgen\local\platform_stats::published_count();
    if ($count === 1) {
        return get_string('aboutwhotext2one', 'theme_nextgen');
    }
    if ($count < 1) {
        return get_string('aboutwhotext2none', 'theme_nextgen');
    }
    return get_string('aboutwhotext2', 'theme_nextgen', $count);
}

/**
 * Recent course reviews for the home page.
 *
 * At most three written reviews, newest first, on courses the visitor can see.
 * An empty list hides the section.
 *
 * @return array
 */
function theme_nextgen_landing_quotes(): array {
    global $DB;

    if (!theme_nextgen_review_table_ready()) {
        return [];
    }

    $namefields = \core_user\fields::for_name()->with_userpic()->get_sql('u', false, '', '', false)->selects;
    $records = $DB->get_records_sql(
        "SELECT r.id AS reviewid, r.rating, r.reviewtext, r.courseid, {$namefields}
           FROM {theme_nextgen_review} r
           JOIN {user} u ON u.id = r.userid
           JOIN {course} c ON c.id = r.courseid
          WHERE u.deleted = 0
            AND r.reviewtext <> ''
            AND c.id <> :siteid
       ORDER BY r.timemodified DESC",
        ['siteid' => SITEID],
        0,
        30
    );

    $quotes = [];
    $visible = [];
    foreach (\core_course_category::top()->get_courses(['recursive' => true]) as $listed) {
        $visible[(int) $listed->id] = $listed;
    }
    foreach ($records as $record) {
        $courseid = (int) $record->courseid;
        if (!isset($visible[$courseid])) {
            continue;
        }
        $course = $visible[$courseid];
        $context = context_course::instance($courseid);
        $raw = trim((string) $record->reviewtext);
        // A word or two is not a testimonial. Keep written reviews only.
        if (\core_text::strlen($raw) < 20) {
            continue;
        }
        $text = theme_nextgen_text_excerpt($raw, 280);
        if ($text === '') {
            continue;
        }
        $quotes[] = [
            'name' => fullname($record),
            'avatar' => theme_nextgen_review_avatar($record),
            'text' => $text,
            'course' => format_string($course->fullname, true, [
                'context' => $context,
                'escape' => false,
            ]),
            'courseurl' => theme_nextgen_course_public_url($courseid)->out(false),
            'stars' => theme_nextgen_star_states(max(1, min(5, (int) $record->rating))),
        ];
        if (count($quotes) >= 3) {
            break;
        }
    }
    return array_slice($quotes, 0, 3);
}

/**
 * Up to four visible courses, in the site sort order.
 *
 * @return array
 */
function theme_nextgen_featured_courses(): array {
    $cards = [];
    $courses = core_course_category::top()->get_courses([
        'recursive' => true,
        'summary' => true,
        'limit' => 4,
        'sort' => ['sortorder' => 1],
    ]);
    $courseids = [];
    foreach ($courses as $course) {
        $courseids[] = (int) $course->id;
    }
    theme_nextgen_prepare_card_facts($courseids);
    foreach ($courses as $course) {
        $cards[] = theme_nextgen_course_card_data($course);
    }
    return $cards;
}

/**
 * Published courses in each category, including courses in child categories.
 *
 * One catalogue read is shared by the header and the home page. Counting each
 * category on its own repeats that read once per category.
 *
 * @return array<int, int>
 */
function theme_nextgen_category_course_counts(): array {
    global $DB;

    static $counts = null;
    if ($counts !== null) {
        return $counts;
    }

    $parents = $DB->get_records_menu('course_categories', null, '', 'id, parent');
    $direct = [];
    foreach (core_course_category::top()->get_courses(['recursive' => true]) as $course) {
        $categoryid = (int) $course->category;
        $direct[$categoryid] = ($direct[$categoryid] ?? 0) + 1;
    }

    $counts = [];
    foreach (array_keys($parents) as $categoryid) {
        $categoryid = (int) $categoryid;
        $total = 0;
        foreach ($direct as $owner => $count) {
            $current = (int) $owner;
            $guard = 0;
            while ($current > 0 && $guard < 12) {
                if ($current === $categoryid) {
                    $total += $count;
                    break;
                }
                $current = (int) ($parents[$current] ?? 0);
                $guard++;
            }
        }
        $counts[$categoryid] = $total;
    }
    return $counts;
}

/**
 * Facts already loaded for a set of course cards.
 *
 * @param int $courseid
 * @param array|null $store
 * @return array|null
 */
function theme_nextgen_card_facts(int $courseid, ?array $store = null): ?array {
    static $facts = [];
    if ($store !== null) {
        $facts[$courseid] = $store;
    }
    return $facts[$courseid] ?? null;
}

/**
 * Load prices, reviews, teachers, and pictures for many cards in a few queries.
 *
 * @param int[] $courseids
 */
function theme_nextgen_prepare_card_facts(array $courseids): void {
    global $CFG, $DB;

    $missing = [];
    foreach ($courseids as $courseid) {
        $courseid = (int) $courseid;
        if ($courseid > 0 && theme_nextgen_card_facts($courseid) === null) {
            $missing[$courseid] = $courseid;
        }
    }
    $missing = array_values($missing);
    if (!$missing) {
        return;
    }

    $blankextra = [
        'ngduration' => '',
        'nglevel' => '',
        'ngeffort' => '',
        'ngcertificate' => '',
        'nglanguage' => '',
        'ngrequirements' => '',
        'ngoutcomes' => '',
        'ngratingdist' => '',
        'ngreviewlist' => '',
    ];
    foreach ($missing as $courseid) {
        theme_nextgen_card_facts($courseid, [
            'price' => '',
            'rating' => null,
            'reviews' => null,
            'bestseller' => false,
            'extra' => $blankextra,
            'reviewstats' => null,
            'enrolments' => 0,
            'feeprice' => '',
            'teachers' => [],
            'image' => '',
        ]);
    }

    $handler = \core_course\customfield\course_handler::create();
    foreach ($handler->get_instances_data($missing, true) as $courseid => $fields) {
        $fact = theme_nextgen_card_facts((int) $courseid);
        if ($fact === null) {
            continue;
        }
        foreach ($fields as $data) {
            $shortname = $data->get_field()->get('shortname');
            $value = trim((string) $data->get_value());
            if ($shortname === 'ngprice') {
                $fact['price'] = $value;
            } else if ($shortname === 'ngrating' && $value !== '' && is_numeric($value)) {
                $fact['rating'] = max(0, min(5, (float) $value));
            } else if ($shortname === 'ngreviews' && $value !== '' && is_numeric($value)) {
                $fact['reviews'] = max(0, (int) $value);
            } else if ($shortname === 'ngbestseller') {
                $fact['bestseller'] = ((int) $value) === 1;
            } else if (array_key_exists($shortname, $fact['extra'])) {
                $fact['extra'][$shortname] = $value;
            }
        }
        theme_nextgen_card_facts((int) $courseid, $fact);
    }

    if (theme_nextgen_review_table_ready()) {
        [$insql, $params] = $DB->get_in_or_equal($missing, SQL_PARAMS_NAMED);
        $rows = $DB->get_records_sql(
            "SELECT r.courseid, COUNT(1) AS reviewcount, AVG(r.rating) AS ratingavg
               FROM {theme_nextgen_review} r
               JOIN {user} u ON u.id = r.userid AND u.deleted = 0
              WHERE r.courseid {$insql}
           GROUP BY r.courseid",
            $params
        );
        foreach ($rows as $row) {
            if ((int) $row->reviewcount < 1) {
                continue;
            }
            $fact = theme_nextgen_card_facts((int) $row->courseid);
            if ($fact === null) {
                continue;
            }
            $fact['reviewstats'] = [
                'average' => round((float) $row->ratingavg, 1),
                'count' => (int) $row->reviewcount,
            ];
            theme_nextgen_card_facts((int) $row->courseid, $fact);
        }
    }

    $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
    if ($studentrole) {
        [$insql, $params] = $DB->get_in_or_equal($missing, SQL_PARAMS_NAMED);
        $params['roleid'] = $studentrole;
        $params['level'] = CONTEXT_COURSE;
        $rows = $DB->get_records_sql(
            "SELECT ctx.instanceid AS courseid, COUNT(DISTINCT ra.userid) AS usercount
               FROM {role_assignments} ra
               JOIN {context} ctx ON ctx.id = ra.contextid
               JOIN {user} u ON u.id = ra.userid AND u.deleted = 0
              WHERE ra.roleid = :roleid
                AND ctx.contextlevel = :level
                AND ctx.instanceid {$insql}
           GROUP BY ctx.instanceid",
            $params
        );
        foreach ($rows as $row) {
            $fact = theme_nextgen_card_facts((int) $row->courseid);
            if ($fact === null) {
                continue;
            }
            $fact['enrolments'] = (int) $row->usercount;
            theme_nextgen_card_facts((int) $row->courseid, $fact);
        }
    }

    if (!empty($CFG->enrol_plugins_enabled) && str_contains($CFG->enrol_plugins_enabled, 'fee')) {
        [$insql, $params] = $DB->get_in_or_equal($missing, SQL_PARAMS_NAMED);
        $params['fee'] = 'fee';
        $params['status'] = ENROL_INSTANCE_ENABLED;
        $rows = $DB->get_records_sql(
            "SELECT e.id, e.courseid, e.cost, e.currency
               FROM {enrol} e
              WHERE e.enrol = :fee
                AND e.status = :status
                AND e.courseid {$insql}
           ORDER BY e.sortorder ASC, e.id ASC",
            $params
        );
        $defaults = theme_nextgen_fee_defaults();
        foreach ($rows as $row) {
            $fact = theme_nextgen_card_facts((int) $row->courseid);
            if ($fact === null || $fact['feeprice'] !== '') {
                continue;
            }
            $currency = strtoupper(trim((string) $row->currency));
            if ($currency === '') {
                $currency = $defaults['currency'];
            }
            $cost = (float) $row->cost;
            if ($cost <= 0) {
                $cost = $defaults['cost'];
            }
            if ($cost > 0 && preg_match('/^[A-Z]{3}$/', $currency)) {
                $fact['feeprice'] = \core_payment\helper::get_cost_as_string($cost, $currency);
                theme_nextgen_card_facts((int) $row->courseid, $fact);
            }
        }
    }

    $roleids = array_filter(array_map('intval', explode(',', (string) ($CFG->coursecontact ?? ''))));
    if ($roleids) {
        $namefields = \core_user\fields::for_name()->get_sql('u', false, '', '', false)->selects;
        [$coursesql, $courseparams] = $DB->get_in_or_equal($missing, SQL_PARAMS_NAMED, 'cfid');
        [$rolesql, $roleparams] = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED, 'cfrid');
        $params = $courseparams + $roleparams;
        $params['level'] = CONTEXT_COURSE;
        $rows = $DB->get_recordset_sql(
            "SELECT ctx.instanceid AS courseid, ra.roleid, u.id, {$namefields}
               FROM {role_assignments} ra
               JOIN {context} ctx ON ctx.id = ra.contextid
               JOIN {user} u ON u.id = ra.userid AND u.deleted = 0
              WHERE ctx.contextlevel = :level
                AND ctx.instanceid {$coursesql}
                AND ra.roleid {$rolesql}",
            $params
        );
        $bucket = [];
        foreach ($rows as $row) {
            $bucket[(int) $row->courseid][] = $row;
        }
        $rows->close();
        $rank = array_flip($roleids);
        foreach ($bucket as $courseid => $users) {
            $fact = theme_nextgen_card_facts($courseid);
            if ($fact === null) {
                continue;
            }
            usort($users, function ($left, $right) use ($rank): int {
                return ($rank[(int) $left->roleid] ?? 99) <=> ($rank[(int) $right->roleid] ?? 99);
            });
            $names = [];
            $seen = [];
            foreach ($users as $user) {
                $userid = (int) $user->id;
                if (isset($seen[$userid])) {
                    continue;
                }
                $seen[$userid] = true;
                $names[] = fullname($user);
            }
            $fact['teachers'] = $names;
            theme_nextgen_card_facts($courseid, $fact);
        }
    }

    if (!empty($CFG->courseoverviewfileslimit)) {
        [$insql, $params] = $DB->get_in_or_equal($missing, SQL_PARAMS_NAMED);
        $params['level'] = CONTEXT_COURSE;
        $params['component'] = 'course';
        $params['filearea'] = 'overviewfiles';
        $rows = $DB->get_records_sql(
            "SELECT f.id, f.contextid, f.filepath, f.filename, f.mimetype, ctx.instanceid AS courseid
               FROM {files} f
               JOIN {context} ctx ON ctx.id = f.contextid
              WHERE ctx.contextlevel = :level
                AND ctx.instanceid {$insql}
                AND f.component = :component
                AND f.filearea = :filearea
                AND f.filename <> '.'
           ORDER BY f.filename ASC",
            $params
        );
        foreach ($rows as $row) {
            $fact = theme_nextgen_card_facts((int) $row->courseid);
            if ($fact === null || $fact['image'] !== '') {
                continue;
            }
            $mimetype = (string) $row->mimetype;
            if ($mimetype !== '' && !str_starts_with($mimetype, 'image/')) {
                continue;
            }
            $fact['image'] = moodle_url::make_pluginfile_url(
                (int) $row->contextid,
                'course',
                'overviewfiles',
                null,
                $row->filepath,
                $row->filename
            )->out(false);
            theme_nextgen_card_facts((int) $row->courseid, $fact);
        }
    }
}

/**
 * Card fields for one course list element.
 *
 * @param core_course_list_element $course
 * @return array
 */
function theme_nextgen_course_card_data(core_course_list_element $course): array {
    global $DB, $OUTPUT, $USER;

    $context = context_course::instance($course->id);
    $fact = theme_nextgen_card_facts((int) $course->id);
    $image = '';
    if ($fact !== null) {
        $image = $fact['image'];
    } else {
        foreach ($course->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $image = moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false);
                break;
            }
        }
    }
    if ($image === '') {
        $image = $OUTPUT->get_generated_image_for_id($course->id);
    }

    if ($fact !== null) {
        $teachers = $fact['teachers'];
    } else {
        $teachers = theme_nextgen_course_teachers($context);
    }
    $teacherlabel = '';
    if ($teachers) {
        $teacherlabel = $teachers[0];
        $extra = count($teachers) - 1;
        if ($extra > 0) {
            $teacherlabel .= ' (+' . $extra . ')';
        }
    }

    $commercial = theme_nextgen_course_commercial($course->id);
    $ratingvalue = $commercial['rating'];
    $reviews = $commercial['reviews'];
    $stars = [];
    $hasrating = $ratingvalue !== null;
    if ($hasrating) {
        for ($i = 1; $i <= 5; $i++) {
            if ($ratingvalue >= $i) {
                $state = 'full';
            } else if ($ratingvalue >= ($i - 0.5)) {
                $state = 'half';
            } else {
                $state = 'empty';
            }
            $stars[] = ['state' => $state];
        }
    }

    $submittedstats = theme_nextgen_submitted_review_stats((int) $course->id);
    if ($submittedstats !== null) {
        $ratingvalue = $submittedstats['average'];
        $reviews = $submittedstats['count'];
        $hasrating = true;
        $stars = theme_nextgen_star_states($ratingvalue);
    }

    $ratingtext = $hasrating ? number_format($ratingvalue, 1) : '';
    $ratinglabel = '';
    $reviewslabel = '';
    if ($hasrating) {
        $a = (object) ['rating' => $ratingtext, 'reviews' => $reviews];
        if ($reviews !== null) {
            $reviewslabel = theme_nextgen_review_count_label((int) $reviews);
            $ratinglabel = ((int) $reviews === 1)
                ? get_string('ratinglabelreview', 'theme_nextgen', $a)
                : get_string('ratinglabelreviews', 'theme_nextgen', $a);
        } else {
            $ratinglabel = get_string('ratinglabel', 'theme_nextgen', $a);
        }
    }

    if ($fact !== null) {
        $enrolments = (int) $fact['enrolments'];
    } else {
        $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
        $enrolments = $studentrole ? count_role_users($studentrole, $context) : count_enrolled_users($context);
    }
    $students = '';
    if ($enrolments === 1) {
        $students = get_string('studentcountone', 'theme_nextgen');
    } else if ($enrolments > 1) {
        $students = get_string('studentcount', 'theme_nextgen', $enrolments);
    }
    $timestamp = !empty($course->startdate) ? (int) $course->startdate : (int) $course->timecreated;
    $date = preg_replace('/^0/', '', userdate($timestamp, '%d %B %Y'));

    $price = theme_nextgen_course_price((int) $course->id, $commercial['price']);

    $excerpt = '';
    if ($course->has_summary()) {
        $formatted = format_text($course->summary, (int) $course->summaryformat, [
            'context' => $context,
            'filter' => false,
        ]);
        $excerpt = theme_nextgen_text_excerpt(html_to_text($formatted, 0, false), 160);
    }

    $enrolled = isloggedin() && !isguestuser() && is_enrolled($context, $USER, '', true);
    if ($enrolled) {
        $entry = new moodle_url('/course/view.php', ['id' => $course->id]);
        $actionurl = $entry->out(false);
        $actionlabel = get_string('coursecontinue', 'theme_nextgen');
    } else {
        $entry = theme_nextgen_course_public_url((int) $course->id);
        $actionurl = $entry->out(false);
        $actionlabel = get_string('viewcourse', 'theme_nextgen');
    }

    return [
        'fullname' => format_string($course->fullname, true, [
            'context' => $context,
            'escape' => false,
        ]),
        'hasexcerpt' => $excerpt !== '',
        'excerpt' => $excerpt,
        'image' => $image,
        'url' => $entry->out(false),
        'bestseller' => $commercial['bestseller'],
        'hasteachers' => $teacherlabel !== '',
        'teachers' => $teacherlabel,
        'hasrating' => $hasrating,
        'rating' => $ratingtext,
        'ratinglabel' => $ratinglabel,
        'stars' => $stars,
        'hasreviews' => $reviews !== null,
        'reviews' => $reviews ?? 0,
        'reviewslabel' => $reviewslabel,
        'hasprice' => $price !== '',
        'price' => $price,
        'actionurl' => $actionurl,
        'actionlabel' => $actionlabel,
        'hasenrolments' => $students !== '',
        'students' => $students,
        'enrolments' => $enrolments,
        'date' => $date,
    ];
}

/**
 * Teacher names for a course, in course-contact role order.
 *
 * @param context_course $context
 * @return string[]
 */
function theme_nextgen_course_teachers(context_course $context): array {
    global $CFG;

    $roleids = array_filter(array_map('intval', explode(',', (string) ($CFG->coursecontact ?? ''))));
    if (!$roleids) {
        return [];
    }

    $namefields = \core_user\fields::for_name()->get_sql('u', false, '', '', false)->selects;
    $teachers = [];
    foreach ($roleids as $roleid) {
        $users = get_role_users($roleid, $context, false, 'u.id, ' . $namefields, 'u.lastname, u.firstname');
        foreach ($users as $user) {
            if (!isset($teachers[$user->id])) {
                $teachers[$user->id] = fullname($user);
            }
        }
    }
    return array_values($teachers);
}

/**
 * Price, rating, review count, and bestseller flag from course custom fields.
 *
 * @param int $courseid
 * @return array
 */
function theme_nextgen_course_commercial(int $courseid): array {
    $fact = theme_nextgen_card_facts($courseid);
    if ($fact !== null) {
        return [
            'price' => $fact['price'],
            'rating' => $fact['rating'],
            'reviews' => $fact['reviews'],
            'bestseller' => $fact['bestseller'],
        ];
    }

    $result = [
        'price' => '',
        'rating' => null,
        'reviews' => null,
        'bestseller' => false,
    ];

    $handler = \core_course\customfield\course_handler::create();
    foreach ($handler->get_instance_data($courseid, true) as $data) {
        $shortname = $data->get_field()->get('shortname');
        $value = trim((string) $data->get_value());
        if ($shortname === 'ngprice') {
            $result['price'] = $value;
        } else if ($shortname === 'ngrating' && $value !== '' && is_numeric($value)) {
            $result['rating'] = max(0, min(5, (float) $value));
        } else if ($shortname === 'ngreviews' && $value !== '' && is_numeric($value)) {
            $result['reviews'] = max(0, (int) $value);
        } else if ($shortname === 'ngbestseller') {
            $result['bestseller'] = ((int) $value) === 1;
        }
    }
    return $result;
}

/**
 * Format a stored price for the card.
 *
 * A bare number uses the site fee currency. Text that already names a currency is kept.
 *
 * @param string $raw
 * @param string $currency
 * @return string
 */
function theme_nextgen_format_price(string $raw, string $currency = ''): string {
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    if (preg_match('/[^0-9.,\s]/', $raw)) {
        return $raw;
    }
    $number = (float) str_replace(',', '', $raw);
    if ($number <= 0) {
        return '';
    }
    if (preg_match('/^[A-Z]{3}$/', $currency)) {
        return \core_payment\helper::get_cost_as_string($number, $currency);
    }
    return number_format($number, 2);
}

/**
 * Site fee enrolment defaults from the fee plugin.
 *
 * @return array{cost: float, currency: string}
 */
function theme_nextgen_fee_defaults(): array {
    $plugin = enrol_get_plugin('fee');
    if (!$plugin) {
        return ['cost' => 0.0, 'currency' => ''];
    }
    return [
        'cost' => (float) $plugin->get_config('cost'),
        'currency' => strtoupper(trim((string) $plugin->get_config('currency'))),
    ];
}

/**
 * Price shown for a course.
 *
 * An enabled fee enrolment wins, including the site default when that instance
 * has no cost of its own. A price typed on the course is next. Every other
 * course shows the site default fee when one is configured.
 *
 * @param int $courseid
 * @param string $manual Price typed on the course, without assuming a currency
 * @return string
 */
function theme_nextgen_course_price(int $courseid, string $manual = ''): string {
    $charged = theme_nextgen_enrol_fee_price($courseid);
    if ($charged !== '') {
        return $charged;
    }
    $defaults = theme_nextgen_fee_defaults();
    $typed = theme_nextgen_format_price($manual, $defaults['currency']);
    if ($typed !== '') {
        return $typed;
    }
    if ($defaults['cost'] > 0 && $defaults['currency'] !== '') {
        return \core_payment\helper::get_cost_as_string($defaults['cost'], $defaults['currency']);
    }
    return '';
}

/**
 * Localised cost of the first enabled fee enrolment, including XAF.
 *
 * Uses the same rule as the fee plugin: a teacher-set cost replaces the site
 * default, and an empty instance cost uses that default. An empty string means
 * this course has no fee enrolment to display.
 *
 * @param int $courseid
 * @return string
 */
function theme_nextgen_enrol_fee_price(int $courseid): string {
    global $CFG;

    $fact = theme_nextgen_card_facts($courseid);
    if ($fact !== null) {
        return $fact['feeprice'];
    }

    if (empty($CFG->enrol_plugins_enabled) || !str_contains($CFG->enrol_plugins_enabled, 'fee')) {
        return '';
    }
    $defaults = theme_nextgen_fee_defaults();
    foreach (enrol_get_instances($courseid, true) as $instance) {
        if ($instance->enrol !== 'fee') {
            continue;
        }
        $currency = strtoupper(trim((string) $instance->currency));
        if ($currency === '') {
            $currency = $defaults['currency'];
        }
        $cost = (float) $instance->cost;
        if ($cost <= 0) {
            $cost = $defaults['cost'];
        }
        if ($cost > 0 && preg_match('/^[A-Z]{3}$/', $currency)) {
            return \core_payment\helper::get_cost_as_string($cost, $currency);
        }
    }
    return '';
}

/**
 * Course custom fields used by the commercial card.
 *
 * Safe to call more than once.
 */
function theme_nextgen_install_commercial_fields(): void {
    $handler = \core_course\customfield\course_handler::create();
    foreach ($handler->get_categories_with_fields() as $category) {
        foreach ($category->get_fields() as $field) {
            if ($field->get('shortname') === 'ngprice') {
                return;
            }
        }
    }

    $categoryid = $handler->create_category(get_string('commercialfields', 'theme_nextgen'));
    $category = \core_customfield\category_controller::create($categoryid);
    $fields = [
        ['shortname' => 'ngprice', 'name' => 'fieldprice', 'description' => 'fieldpricedesc', 'type' => 'text'],
        ['shortname' => 'ngrating', 'name' => 'fieldrating', 'description' => 'fieldratingdesc', 'type' => 'text'],
        ['shortname' => 'ngreviews', 'name' => 'fieldreviews', 'description' => 'fieldreviewsdesc', 'type' => 'text'],
        ['shortname' => 'ngbestseller', 'name' => 'fieldbestseller', 'description' => 'fieldbestsellerdesc', 'type' => 'checkbox'],
        ['shortname' => 'nglevel', 'name' => 'fieldlevel', 'description' => 'fieldleveldesc', 'type' => 'text'],
        ['shortname' => 'ngduration', 'name' => 'fieldlength', 'description' => 'fieldlengthdesc', 'type' => 'text'],
        ['shortname' => 'ngeffort', 'name' => 'fieldeffort', 'description' => 'fieldeffortdesc', 'type' => 'text'],
        ['shortname' => 'ngcertificate', 'name' => 'fieldcertificate', 'description' => 'fieldcertificatedesc', 'type' => 'text'],
        ['shortname' => 'nglanguage', 'name' => 'fieldlanguage', 'description' => 'fieldlanguagedesc', 'type' => 'text'],
        ['shortname' => 'ngratingdist', 'name' => 'fieldratingdist', 'description' => 'fieldratingdistdesc', 'type' => 'text'],
        ['shortname' => 'ngreviewlist', 'name' => 'fieldreviewlist', 'description' => 'fieldreviewlistdesc', 'type' => 'text'],
    ];
    $sort = 0;
    foreach ($fields as $spec) {
        $sort++;
        $record = (object) [
            'name' => get_string($spec['name'], 'theme_nextgen'),
            'shortname' => $spec['shortname'],
            'type' => $spec['type'],
            'description' => get_string($spec['description'], 'theme_nextgen'),
            'descriptionformat' => FORMAT_HTML,
            'sortorder' => $sort,
            'configdata' => [
                'required' => 0,
                'uniquevalues' => 0,
                'locked' => 0,
                'visibility' => \core_course\customfield\course_handler::VISIBLETOALL,
                'defaultvalue' => '',
                'displaysize' => 30,
                'maxlength' => 30,
                'checkbydefault' => 0,
            ],
        ];
        $field = \core_customfield\field_controller::create(0, (object) ['type' => $spec['type']], $category);
        $handler->save_field_configuration($field, $record);
    }
}

/**
 * Add course-page fact fields when an older install only has the price fields.
 */
function theme_nextgen_install_fact_fields(): void {
    $handler = \core_course\customfield\course_handler::create();
    $present = [];
    $category = null;
    foreach ($handler->get_categories_with_fields() as $existing) {
        foreach ($existing->get_fields() as $field) {
            $present[$field->get('shortname')] = true;
            if ($field->get('shortname') === 'ngprice') {
                $category = $existing;
            }
        }
    }
    if ($category === null) {
        theme_nextgen_install_commercial_fields();
        return;
    }

    $fields = [
        ['shortname' => 'nglevel', 'name' => 'fieldlevel', 'description' => 'fieldleveldesc', 'type' => 'text'],
        ['shortname' => 'ngduration', 'name' => 'fieldlength', 'description' => 'fieldlengthdesc', 'type' => 'text'],
        ['shortname' => 'ngeffort', 'name' => 'fieldeffort', 'description' => 'fieldeffortdesc', 'type' => 'text'],
        ['shortname' => 'ngcertificate', 'name' => 'fieldcertificate', 'description' => 'fieldcertificatedesc', 'type' => 'text'],
        ['shortname' => 'nglanguage', 'name' => 'fieldlanguage', 'description' => 'fieldlanguagedesc', 'type' => 'text'],
        ['shortname' => 'ngratingdist', 'name' => 'fieldratingdist', 'description' => 'fieldratingdistdesc', 'type' => 'text'],
        ['shortname' => 'ngreviewlist', 'name' => 'fieldreviewlist', 'description' => 'fieldreviewlistdesc', 'type' => 'text'],
    ];
    $sort = count($present);
    foreach ($fields as $spec) {
        if (!empty($present[$spec['shortname']])) {
            continue;
        }
        $sort++;
        $record = (object) [
            'name' => get_string($spec['name'], 'theme_nextgen'),
            'shortname' => $spec['shortname'],
            'type' => $spec['type'],
            'description' => get_string($spec['description'], 'theme_nextgen'),
            'descriptionformat' => FORMAT_HTML,
            'sortorder' => $sort,
            'configdata' => [
                'required' => 0,
                'uniquevalues' => 0,
                'locked' => 0,
                'visibility' => \core_course\customfield\course_handler::VISIBLETOALL,
                'defaultvalue' => '',
                'displaysize' => 30,
                'maxlength' => 1333,
                'checkbydefault' => 0,
            ],
        ];
        $field = \core_customfield\field_controller::create(0, (object) ['type' => $spec['type']], $category);
        $handler->save_field_configuration($field, $record);
    }
}

/**
 * Collapse a course summary into one readable line for a banner or card.
 *
 * List markers left by HTML-to-text conversion are removed so the line reads
 * as prose.
 *
 * @param string $plain
 * @param int $length
 * @return string
 */
function theme_nextgen_text_excerpt(string $plain, int $length): string {
    $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $plain = str_replace(["\u{00A0}", "\u{200B}"], ' ', $plain);
    $plain = preg_replace('/(?:^|\s)[*•]\s+/u', ' ', $plain);
    $plain = preg_replace('/\s+/u', ' ', (string) $plain);
    $plain = trim((string) $plain);
    if ($plain === '') {
        return '';
    }
    return shorten_text($plain, $length, false);
}

/**
 * Language code and display name for a course.
 *
 * A custom field wins. Otherwise the course language is used, then the site
 * language. The value is stable for filtering. The label is the translated name.
 *
 * @param string $custom
 * @param string $courselang
 * @return array{value: string, label: string}
 */
function theme_nextgen_course_language(string $custom, string $courselang): array {
    global $CFG;

    static $translations = null;
    if ($translations === null) {
        $translations = get_string_manager()->get_list_of_translations();
    }

    $raw = trim($custom);
    $code = '';
    if ($raw === '') {
        $code = trim($courselang) !== '' ? trim($courselang) : (string) ($CFG->lang ?? '');
    } else if (isset($translations[$raw])) {
        $code = $raw;
    }

    if ($code === '') {
        return ['value' => $raw, 'label' => $raw];
    }

    $label = $translations[$code] ?? $code;
    $label = str_replace("\u{200E}", '', (string) $label);
    $label = trim((string) preg_replace('/\s*\([a-z]{2}(?:_[A-Za-z0-9]+)?\)\s*$/i', '', $label));
    if ($label === '') {
        $label = $code;
    }
    return ['value' => $code, 'label' => $label];
}

/**
 * Whether a certificate field or activity means the course awards one.
 *
 * An empty field does not count. Explicit refusals such as "no" or "n/a" do
 * not count. Any other text counts, as does a visible certificate activity.
 *
 * @param string $value
 * @param bool $hasactivity
 * @return bool
 */
function theme_nextgen_certificate_is_offered(string $value, bool $hasactivity = false): bool {
    if ($hasactivity) {
        return true;
    }
    $value = \core_text::strtolower(trim($value));
    if ($value === '') {
        return false;
    }
    $declined = ['0', 'no', 'none', 'false', 'n', '-', 'n/a', 'na', 'not available', 'not included'];
    if (in_array($value, $declined, true) || str_starts_with($value, 'no ')) {
        return false;
    }
    return true;
}

/**
 * Courses that contain a visible certificate activity.
 *
 * Moodle does not store a certificate flag on the course. The certificate,
 * custom certificate, and course certificate activities are the signal.
 *
 * @param int[] $courseids
 * @return array<int, bool>
 */
function theme_nextgen_certificate_activity_ids(array $courseids): array {
    global $DB;

    $courseids = array_values(array_unique(array_filter(array_map('intval', $courseids))));
    if (!$courseids) {
        return [];
    }

    $names = [];
    foreach (['customcert', 'certificate', 'coursecertificate'] as $name) {
        if ($DB->record_exists('modules', ['name' => $name, 'visible' => 1])) {
            $names[] = $name;
        }
    }
    if (!$names) {
        return [];
    }

    [$coursesql, $courseparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'ccid');
    [$modsql, $modparams] = $DB->get_in_or_equal($names, SQL_PARAMS_NAMED, 'mod');
    $found = $DB->get_fieldset_sql(
        "SELECT DISTINCT cm.course
           FROM {course_modules} cm
           JOIN {modules} m ON m.id = cm.module
          WHERE cm.course {$coursesql}
            AND m.name {$modsql}
            AND cm.visible = 1
            AND cm.deletioninprogress = 0",
        $courseparams + $modparams
    );

    $map = [];
    foreach ($found as $courseid) {
        $map[(int) $courseid] = true;
    }
    return $map;
}

/**
 * Learner-facing name for a course format.
 *
 * @param string $format
 * @return string
 */
function theme_nextgen_course_format_label(string $format): string {
    $format = trim($format);
    if ($format === '') {
        return '';
    }
    $key = 'courseformat_' . $format;
    if (get_string_manager()->string_exists($key, 'theme_nextgen')) {
        return get_string($key, 'theme_nextgen');
    }
    $component = 'format_' . $format;
    if (get_string_manager()->string_exists('pluginname', $component)) {
        return get_string('pluginname', $component);
    }
    return '';
}

/**
 * Phrase shown for the certificate fact.
 *
 * A plain "yes" becomes the localised Yes. A more specific field value, such
 * as "Yes, on completion", is kept.
 *
 * @param string $value
 * @param bool $offered
 * @return string
 */
function theme_nextgen_certificate_label(string $value, bool $offered): string {
    if (!$offered) {
        return get_string('no');
    }
    $raw = trim($value);
    $generic = ['', 'yes', 'y', '1', 'true', 'included'];
    if (in_array(\core_text::strtolower($raw), $generic, true)) {
        return get_string('yes');
    }
    return $raw;
}

/**
 * Context for the course catalogue intro.
 *
 * @return array
 */
function theme_nextgen_catalogue_context(): array {
    global $OUTPUT;

    $categoryid = optional_param('categoryid', 0, PARAM_INT);
    $filters = theme_nextgen_catalogue_request_filters();
    $kept = [];
    foreach (['search', 'cost', 'fee', 'certificate', 'level', 'language', 'length', 'effort'] as $key) {
        if ($filters[$key] !== '') {
            $kept[$key] = $filters[$key];
        }
    }
    $browse = optional_param('browse', '', PARAM_ALPHA) === 'categories';
    $linkparams = $kept;
    if ($browse) {
        $linkparams['browse'] = 'categories';
    }

    $categories = [];
    $categoryname = '';
    foreach (theme_nextgen_landing_categories(0) as $category) {
        $category['active'] = ((int) $category['id'] === $categoryid);
        if ($category['active']) {
            $categoryname = $category['name'];
        }
        $category['url'] = (new moodle_url('/course/index.php', $linkparams + [
            'categoryid' => $category['id'],
        ]))->out(false);
        $categories[] = $category;
    }

    $ids = theme_nextgen_catalogue_visible_ids($categoryid);
    $listed = theme_nextgen_catalogue_filter_rows($ids);
    $costs = theme_nextgen_catalogue_fixed_options($filters['cost'], [
        'free' => get_string('cataloguefree', 'theme_nextgen'),
        'paid' => get_string('cataloguepaid', 'theme_nextgen'),
    ]);
    $fees = theme_nextgen_catalogue_filter_options($listed, 'filterfee', $filters['fee']);
    $certificates = theme_nextgen_catalogue_fixed_options($filters['certificate'], [
        'yes' => get_string('yes'),
        'no' => get_string('no'),
    ]);
    $levels = theme_nextgen_catalogue_filter_options($listed, 'filterlevel', $filters['level']);
    $languages = theme_nextgen_catalogue_filter_options($listed, 'filterlanguage', $filters['language']);
    $lengths = theme_nextgen_catalogue_filter_options($listed, 'filterlength', $filters['length']);
    $efforts = theme_nextgen_catalogue_filter_options($listed, 'filtereffort', $filters['effort']);
    $matched = theme_nextgen_catalogue_apply_filters($listed, $filters);

    $perpage = theme_nextgen_catalogue_per_page();
    $page = optional_param('page', 0, PARAM_INT);
    if ($page < 0) {
        $page = 0;
    }
    $total = count($matched);
    $lastpage = $total > 0 ? (int) ceil($total / $perpage) - 1 : 0;
    if ($page > $lastpage) {
        $page = $lastpage;
    }
    $pageids = [];
    foreach (array_slice($matched, $page * $perpage, $perpage) as $row) {
        $pageids[] = (int) $row['id'];
    }
    $courses = theme_nextgen_catalogue_cards($pageids);

    if ($courses) {
        $empty = '';
    } else if ($categoryid > 0 && !$ids) {
        $empty = get_string('catalogueemptycategory', 'theme_nextgen');
    } else if ($kept) {
        $empty = get_string('catalogueemptysearch', 'theme_nextgen');
    } else if ($categoryid > 0) {
        $empty = get_string('catalogueemptycategory', 'theme_nextgen');
    } else {
        $empty = get_string('catalogueempty', 'theme_nextgen');
    }

    $clear = ['browse' => 'categories'];
    if ($categoryid > 0) {
        $clear['categoryid'] = $categoryid;
    }

    $base = [];
    if ($categoryid > 0) {
        $base['categoryid'] = $categoryid;
    }
    if ($browse) {
        $base['browse'] = 'categories';
    }
    $filterurl = static function (array $extra) use ($base): string {
        return (new moodle_url('/course/index.php', $base + $extra))->out(false);
    };
    $labelof = static function (array $options, string $value): string {
        foreach ($options as $option) {
            if ($option['value'] === $value) {
                return $option['label'];
            }
        }
        return $value;
    };
    $active = [];
    $choices = [
        'search' => ['cataloguesearch', $filters['search']],
        'cost' => ['catalogueprice', $labelof($costs, $filters['cost'])],
        'fee' => ['cataloguefee', $labelof($fees, $filters['fee'])],
        'certificate' => ['fieldcertificate', $labelof($certificates, $filters['certificate'])],
        'level' => ['fieldlevel', $labelof($levels, $filters['level'])],
        'language' => ['fieldlanguage', $labelof($languages, $filters['language'])],
        'length' => ['fieldlength', $labelof($lengths, $filters['length'])],
        'effort' => ['fieldeffort', $labelof($efforts, $filters['effort'])],
    ];
    foreach ($choices as $key => $choice) {
        if ($filters[$key] === '') {
            continue;
        }
        $rest = $kept;
        unset($rest[$key]);
        $active[] = [
            'label' => get_string($choice[0], 'theme_nextgen'),
            'value' => $choice[1],
            'url' => $filterurl($rest),
        ];
    }

    $advancedcount = 0;
    foreach (['cost', 'fee', 'certificate', 'level', 'language', 'length', 'effort'] as $key) {
        if ($filters[$key] !== '') {
            $advancedcount++;
        }
    }
    if ($total === 1) {
        $resultslabel = get_string('catalogueresult', 'theme_nextgen');
    } else {
        $resultslabel = get_string('catalogueresults', 'theme_nextgen', $total);
    }
    $paging = '';
    if ($total > $perpage) {
        $paging = $OUTPUT->paging_bar(
            $total,
            $page,
            $perpage,
            new moodle_url('/course/index.php', $base + $kept)
        );
    }

    return [
        'hascourses' => !empty($courses),
        'courses' => $courses,
        'hascategories' => !empty($categories),
        'categories' => $categories,
        'allcoursesurl' => (new moodle_url('/course/index.php', $linkparams))->out(false),
        'allactive' => $categoryid === 0,
        'hascategory' => $categoryname !== '',
        'categoryname' => $categoryname,
        'categoryid' => $categoryid,
        'hascategoryid' => $categoryid > 0,
        'browse' => $browse,
        'search' => $filters['search'],
        'searchaction' => (new moodle_url('/course/index.php'))->out(false),
        'costs' => $costs,
        'hasfees' => !empty($fees),
        'fees' => $fees,
        'certificates' => $certificates,
        'haslevels' => !empty($levels),
        'levels' => $levels,
        'languages' => $languages,
        'haslengths' => !empty($lengths),
        'lengths' => $lengths,
        'hasefforts' => !empty($efforts),
        'efforts' => $efforts,
        'advancedopen' => $advancedcount > 0,
        'advancedsummary' => $advancedcount
            ? get_string('catalogueadvancedcount', 'theme_nextgen', $advancedcount)
            : get_string('catalogueadvanced', 'theme_nextgen'),
        'hasactivefilters' => !empty($active),
        'activefilters' => $active,
        'resultslabel' => $resultslabel,
        'haspaging' => $paging !== '',
        'paging' => $paging,
        'clearurl' => (new moodle_url('/course/index.php', $clear))->out(false),
        'emptytext' => $empty,
    ];
}

/**
 * Search and advanced-filter values from the catalogue request.
 *
 * @return array
 */
function theme_nextgen_catalogue_request_filters(): array {
    $cost = \core_text::strtolower(trim(optional_param('cost', '', PARAM_ALPHA)));
    if (!in_array($cost, ['free', 'paid'], true)) {
        $cost = '';
    }
    $certificate = \core_text::strtolower(trim(optional_param('certificate', '', PARAM_ALPHA)));
    if (!in_array($certificate, ['yes', 'no'], true)) {
        $certificate = '';
    }
    return [
        'search' => trim(optional_param('search', '', PARAM_TEXT)),
        'cost' => $cost,
        'fee' => theme_nextgen_normalise_fee(optional_param('fee', '', PARAM_TEXT)),
        'certificate' => $certificate,
        'level' => trim(optional_param('level', '', PARAM_TEXT)),
        'language' => trim(optional_param('language', '', PARAM_TEXT)),
        'length' => trim(optional_param('length', '', PARAM_TEXT)),
        'effort' => trim(optional_param('effort', '', PARAM_TEXT)),
    ];
}

/**
 * Collapse spacing in a displayed fee so the filter value matches the card.
 *
 * @param string $fee
 * @return string
 */
function theme_nextgen_normalise_fee(string $fee): string {
    $fee = str_replace("\u{00A0}", ' ', $fee);
    $fee = preg_replace('/\s+/u', ' ', trim($fee));
    return is_string($fee) ? $fee : '';
}

/**
 * Filter choices that stay the same whatever the catalogue contains.
 *
 * @param string $current
 * @param array $options value => label
 * @return array
 */
function theme_nextgen_catalogue_fixed_options(string $current, array $options): array {
    $result = [];
    foreach ($options as $value => $label) {
        $result[] = [
            'value' => (string) $value,
            'label' => $label,
            'selected' => ((string) $value === $current),
        ];
    }
    return $result;
}

/**
 * Distinct values for one advanced filter, with the current choice marked.
 *
 * @param array $courses
 * @param string $key
 * @param string $current
 * @return array
 */
function theme_nextgen_catalogue_filter_options(array $courses, string $key, string $current): array {
    $found = [];
    foreach ($courses as $course) {
        $value = trim((string) ($course[$key] ?? ''));
        if ($value === '') {
            continue;
        }
        $label = trim((string) ($course[$key . 'label'] ?? ''));
        if ($label === '') {
            $label = $value;
        }
        $found[$value] = $label;
    }
    uasort($found, 'strnatcasecmp');
    $options = [];
    foreach ($found as $value => $label) {
        $options[] = [
            'value' => $value,
            'label' => $label,
            'selected' => ($value === $current),
        ];
    }
    return $options;
}

/**
 * Keep cards that match the search text and the advanced filters.
 *
 * @param array $courses
 * @param array $filters
 * @return array
 */
function theme_nextgen_catalogue_apply_filters(array $courses, array $filters): array {
    $search = \core_text::strtolower($filters['search']);
    $kept = [];
    foreach ($courses as $course) {
        if ($search !== '' && !str_contains((string) $course['filtersearch'], $search)) {
            continue;
        }
        if ($filters['level'] !== '' && (string) $course['filterlevel'] !== $filters['level']) {
            continue;
        }
        if ($filters['language'] !== '' && (string) $course['filterlanguage'] !== $filters['language']) {
            continue;
        }
        if ($filters['certificate'] !== '' && (string) $course['filtercertificate'] !== $filters['certificate']) {
            continue;
        }
        if ($filters['cost'] !== '' && (string) $course['filtercost'] !== $filters['cost']) {
            continue;
        }
        if ($filters['fee'] !== '' && (string) $course['filterfee'] !== $filters['fee']) {
            continue;
        }
        if ($filters['length'] !== '' && (string) $course['filterlength'] !== $filters['length']) {
            continue;
        }
        if ($filters['effort'] !== '' && (string) $course['filtereffort'] !== $filters['effort']) {
            continue;
        }
        unset(
            $course['filtersearch'],
            $course['filterlevel'],
            $course['filterlanguage'],
            $course['filterlanguagelabel'],
            $course['filtercertificate'],
            $course['filtercertificatelabel'],
            $course['filtercost'],
            $course['filtercostlabel'],
            $course['filterfee'],
            $course['filterfeelabel'],
            $course['filterlength'],
            $course['filtereffort']
        );
        $kept[] = $course;
    }
    return $kept;
}

/**
 * Courses shown on one catalogue page.
 *
 * @return int
 */
function theme_nextgen_catalogue_per_page(): int {
    return 10;
}

/**
 * Visible course ids in catalogue order.
 *
 * @param int $categoryid
 * @return int[]
 */
function theme_nextgen_catalogue_visible_ids(int $categoryid): array {
    if ($categoryid > 0) {
        $source = core_course_category::get($categoryid, IGNORE_MISSING, true);
        if (!$source || !$source->is_uservisible()) {
            return [];
        }
    } else {
        $source = core_course_category::top();
    }

    $ids = [];
    foreach ($source->get_courses(['recursive' => true, 'idonly' => true]) as $courseid) {
        $courseid = (int) $courseid;
        if ($courseid > 0 && $courseid !== SITEID) {
            $ids[] = $courseid;
        }
    }
    return $ids;
}

/**
 * Filter fields for every visible course, without building the cards.
 *
 * @param int[] $courseids
 * @return array
 */
function theme_nextgen_catalogue_filter_rows(array $courseids): array {
    global $DB;

    $courseids = array_values(array_unique(array_filter(array_map('intval', $courseids))));
    if (!$courseids) {
        return [];
    }

    [$sql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
    $records = $DB->get_records_select(
        'course',
        "id {$sql}",
        $params,
        '',
        'id, fullname, shortname, summary, lang, category'
    );
    $fields = theme_nextgen_catalogue_custom_fields($courseids);
    $certified = theme_nextgen_certificate_activity_ids($courseids);
    $fees = theme_nextgen_catalogue_fee_prices($courseids);
    $teachers = theme_nextgen_catalogue_teacher_names($courseids);
    $defaults = theme_nextgen_fee_defaults();
    $fallback = '';
    if ($defaults['cost'] > 0 && $defaults['currency'] !== '') {
        $fallback = \core_payment\helper::get_cost_as_string($defaults['cost'], $defaults['currency']);
    }

    $rows = [];
    foreach ($courseids as $courseid) {
        if (!isset($records[$courseid])) {
            continue;
        }
        $course = $records[$courseid];
        $extra = $fields[$courseid] ?? [];
        $language = theme_nextgen_course_language($extra['nglanguage'] ?? '', (string) $course->lang);
        $offered = theme_nextgen_certificate_is_offered(
            $extra['ngcertificate'] ?? '',
            !empty($certified[$courseid])
        );
        $charged = $fees[$courseid] ?? '';
        if ($charged !== '') {
            $price = $charged;
        } else {
            $price = theme_nextgen_format_price($extra['ngprice'] ?? '', $defaults['currency']);
            if ($price === '') {
                $price = $fallback;
            }
        }
        $feekey = theme_nextgen_normalise_fee($price);
        $paid = $feekey !== '';

        $categoryname = '';
        if (!empty($course->category)) {
            $category = core_course_category::get((int) $course->category, IGNORE_MISSING, true);
            if ($category) {
                $categoryname = $category->get_formatted_name();
            }
        }
        $bits = [
            $course->fullname,
            $course->shortname,
            $course->summary,
            implode(' ', $teachers[$courseid] ?? []),
            $categoryname,
            $extra['nglevel'] ?? '',
            $language['label'],
            $extra['ngduration'] ?? '',
            $extra['ngeffort'] ?? '',
            $extra['ngcertificate'] ?? '',
            $price,
            get_string($paid ? 'cataloguepaid' : 'cataloguefree', 'theme_nextgen'),
        ];
        if ($offered) {
            $bits[] = get_string('coursecertificate', 'theme_nextgen');
        }
        $plain = trim(html_entity_decode(strip_tags(implode(' ', $bits)), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        $rows[] = [
            'id' => $courseid,
            'filterlevel' => $extra['nglevel'] ?? '',
            'filterlanguage' => $language['value'],
            'filterlanguagelabel' => $language['label'],
            'filtercertificate' => $offered ? 'yes' : 'no',
            'filtercertificatelabel' => get_string($offered ? 'yes' : 'no'),
            'filtercost' => $paid ? 'paid' : 'free',
            'filtercostlabel' => get_string($paid ? 'cataloguepaid' : 'cataloguefree', 'theme_nextgen'),
            'filterfee' => $paid ? $feekey : '',
            'filterfeelabel' => $paid ? $price : '',
            'filterlength' => $extra['ngduration'] ?? '',
            'filtereffort' => $extra['ngeffort'] ?? '',
            'filtersearch' => \core_text::strtolower($plain),
        ];
    }
    return $rows;
}

/**
 * Custom field values used by the catalogue filters.
 *
 * @param int[] $courseids
 * @return array<int, array<string, string>>
 */
function theme_nextgen_catalogue_custom_fields(array $courseids): array {
    $wanted = [
        'ngprice' => true,
        'nglevel' => true,
        'ngeffort' => true,
        'ngcertificate' => true,
        'nglanguage' => true,
        'ngduration' => true,
    ];
    $fields = [];
    $handler = \core_course\customfield\course_handler::create();
    foreach ($handler->get_instances_data($courseids, true) as $courseid => $datafields) {
        $values = [];
        foreach ($datafields as $data) {
            $shortname = $data->get_field()->get('shortname');
            if (isset($wanted[$shortname])) {
                $values[$shortname] = trim((string) $data->get_value());
            }
        }
        $fields[(int) $courseid] = $values;
    }
    return $fields;
}

/**
 * Localised fee for each course that has an enabled fee enrolment.
 *
 * @param int[] $courseids
 * @return array<int, string>
 */
function theme_nextgen_catalogue_fee_prices(array $courseids): array {
    global $CFG, $DB;

    if (!$courseids || empty($CFG->enrol_plugins_enabled) || !str_contains($CFG->enrol_plugins_enabled, 'fee')) {
        return [];
    }

    [$insql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
    $params['fee'] = 'fee';
    $params['status'] = ENROL_INSTANCE_ENABLED;
    $rows = $DB->get_records_sql(
        "SELECT e.id, e.courseid, e.cost, e.currency
           FROM {enrol} e
          WHERE e.enrol = :fee
            AND e.status = :status
            AND e.courseid {$insql}
       ORDER BY e.sortorder ASC, e.id ASC",
        $params
    );
    $defaults = theme_nextgen_fee_defaults();
    $prices = [];
    foreach ($rows as $row) {
        $courseid = (int) $row->courseid;
        if (isset($prices[$courseid])) {
            continue;
        }
        $currency = strtoupper(trim((string) $row->currency));
        if ($currency === '') {
            $currency = $defaults['currency'];
        }
        $cost = (float) $row->cost;
        if ($cost <= 0) {
            $cost = $defaults['cost'];
        }
        if ($cost > 0 && preg_match('/^[A-Z]{3}$/', $currency)) {
            $prices[$courseid] = \core_payment\helper::get_cost_as_string($cost, $currency);
        }
    }
    return $prices;
}

/**
 * Course-contact names, for catalogue search.
 *
 * @param int[] $courseids
 * @return array<int, string[]>
 */
function theme_nextgen_catalogue_teacher_names(array $courseids): array {
    global $CFG, $DB;

    $roleids = array_filter(array_map('intval', explode(',', (string) ($CFG->coursecontact ?? ''))));
    if (!$courseids || !$roleids) {
        return [];
    }

    $namefields = \core_user\fields::for_name()->get_sql('u', false, '', '', false)->selects;
    [$coursesql, $courseparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'cfid');
    [$rolesql, $roleparams] = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED, 'cfrid');
    $params = $courseparams + $roleparams;
    $params['level'] = CONTEXT_COURSE;
    $rows = $DB->get_recordset_sql(
        "SELECT ctx.instanceid AS courseid, ra.roleid, u.id, {$namefields}
           FROM {role_assignments} ra
           JOIN {context} ctx ON ctx.id = ra.contextid
           JOIN {user} u ON u.id = ra.userid AND u.deleted = 0
          WHERE ctx.contextlevel = :level
            AND ctx.instanceid {$coursesql}
            AND ra.roleid {$rolesql}",
        $params
    );
    $bucket = [];
    foreach ($rows as $row) {
        $bucket[(int) $row->courseid][] = $row;
    }
    $rows->close();

    $rank = array_flip($roleids);
    $names = [];
    foreach ($bucket as $courseid => $users) {
        usort($users, function ($left, $right) use ($rank): int {
            return ($rank[(int) $left->roleid] ?? 99) <=> ($rank[(int) $right->roleid] ?? 99);
        });
        $seen = [];
        $coursenames = [];
        foreach ($users as $user) {
            $userid = (int) $user->id;
            if (isset($seen[$userid])) {
                continue;
            }
            $seen[$userid] = true;
            $coursenames[] = fullname($user);
        }
        $names[$courseid] = $coursenames;
    }
    return $names;
}

/**
 * Cards for one page of catalogue courses, in the given order.
 *
 * @param int[] $courseids
 * @return array
 */
function theme_nextgen_catalogue_cards(array $courseids): array {
    global $DB;

    $courseids = array_values(array_filter(array_map('intval', $courseids)));
    if (!$courseids) {
        return [];
    }

    [$sql, $params] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
    $records = $DB->get_records_select('course', "id {$sql}", $params);
    theme_nextgen_prepare_card_facts($courseids);

    $cards = [];
    foreach ($courseids as $courseid) {
        if (!isset($records[$courseid])) {
            continue;
        }
        $cards[] = theme_nextgen_course_card_data(new core_course_list_element($records[$courseid]));
    }
    return $cards;
}

/**
 * Categories shown from the header menu.
 *
 * @return array
 */
function theme_nextgen_header_categories(): array {
    global $PAGE;

    $empty = [
        'hascategories' => false,
        'categories' => [],
        'active' => false,
        'url' => '',
    ];
    if (during_initial_install()) {
        return $empty;
    }

    $categoryid = 0;
    $browse = '';
    if (str_starts_with((string) $PAGE->pagetype, 'course-index')) {
        $categoryid = optional_param('categoryid', 0, PARAM_INT);
        $browse = optional_param('browse', '', PARAM_ALPHA);
    }

    $items = [];
    foreach (theme_nextgen_landing_categories(0) as $category) {
        $category['active'] = ((int) $category['id'] === $categoryid);
        $items[] = $category;
    }
    if (!$items) {
        return $empty;
    }

    return [
        'hascategories' => true,
        'categories' => $items,
        'active' => ($browse === 'categories' || $categoryid > 0),
        'url' => (new moodle_url('/course/index.php', ['browse' => 'categories']))->out(false),
    ];
}

/**
 * Place the category menu after Courses and before About.
 *
 * @param navigation_node $primary
 */
function theme_nextgen_add_category_menu(navigation_node $primary): void {
    $menu = theme_nextgen_header_categories();
    if (!$menu['hascategories']) {
        return;
    }

    $node = navigation_node::create(
        get_string('categoriesavailable', 'theme_nextgen'),
        new moodle_url($menu['url']),
        navigation_node::TYPE_CUSTOM,
        null,
        'nextgen-categories'
    );
    $node->showchildreninsubmenu = true;
    $node->add(
        get_string('allcourses', 'theme_nextgen'),
        new moodle_url($menu['url']),
        navigation_node::TYPE_CUSTOM,
        null,
        'nextgen-categories-all'
    );
    foreach ($menu['categories'] as $category) {
        $child = $node->add(
            $category['name'],
            new moodle_url('/course/index.php', ['categoryid' => (int) $category['id']]),
            navigation_node::TYPE_CUSTOM,
            null,
            'nextgen-category-' . (int) $category['id']
        );
        if (!empty($category['active']) && $child) {
            $child->make_active();
        }
    }

    $before = 'nextgen-about';
    foreach ($primary->children as $child) {
        if ($child->action instanceof moodle_url && $child->action->compare(
            \theme_nextgen\local\public_page::url('about'),
            URL_MATCH_BASE
        )) {
            $before = $child->key;
            break;
        }
    }
    $primary->add_node($node, $before);
}

/**
 * Photograph that matches a category name.
 *
 * The files live in pix/ and come from Iwaria, chosen for the words in the name.
 *
 * @param string $name Category name.
 * @return string Theme pix name, without an extension.
 */
function theme_nextgen_category_photo(string $name): string {
    $name = core_text::strtolower($name);
    $photos = [
        'business' => 'category-business-skills',
        'innovation' => 'category-innovation',
        'technolog' => 'category-innovation',
        'industry' => 'category-industry',
        'agricultur' => 'category-agriculture',
        'teach' => 'category-teaching',
        'mentor' => 'category-teaching',
        'faith' => 'category-faith',
        'conflict' => 'category-faith',
    ];
    foreach ($photos as $needle => $file) {
        if (str_contains($name, $needle)) {
            return $file;
        }
    }
    return 'category-business-skills';
}

/**
 * Visible top-level categories.
 *
 * @param int $limit Maximum categories to return. Zero returns every visible category.
 * @return array
 */
function theme_nextgen_landing_categories(int $limit = 6): array {
    global $OUTPUT;

    $counts = theme_nextgen_category_course_counts();
    $items = [];
    foreach (core_course_category::top()->get_children() as $category) {
        $coursecount = $counts[(int) $category->id] ?? 0;
        if ($coursecount < 1) {
            continue;
        }
        if ($coursecount === 1) {
            $countlabel = get_string('categorycoursecount', 'theme_nextgen', $coursecount);
        } else {
            $countlabel = get_string('categorycoursecounts', 'theme_nextgen', $coursecount);
        }
        $items[] = [
            'id' => $category->id,
            'name' => $category->get_formatted_name(['escape' => false]),
            'count' => $coursecount,
            'countlabel' => $countlabel,
            'image' => $OUTPUT->image_url(theme_nextgen_category_photo($category->name), 'theme')->out(false),
            'url' => (new moodle_url('/course/index.php', ['categoryid' => $category->id]))->out(false),
        ];
        if ($limit > 0 && count($items) >= $limit) {
            break;
        }
    }
    return $items;
}

/**
 * Teachers on published courses the visitor can see.
 *
 * Names come from teacher role assignments, so a person appears when they teach
 * a course. More than one person is shown as a slider on the home page.
 *
 * @return array
 */
function theme_nextgen_landing_instructors(): array {
    global $DB;

    $courseids = [];
    foreach (core_course_category::top()->get_courses(['recursive' => true]) as $listed) {
        $courseids[] = (int) $listed->id;
    }
    if (!$courseids) {
        return [];
    }

    $roleids = $DB->get_fieldset_select('role', 'id', 'shortname IN (?, ?)', ['editingteacher', 'teacher']);
    if (!$roleids) {
        return [];
    }

    [$rolesql, $params] = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED, 'role');
    [$coursesql, $courseparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED, 'course');
    $params += $courseparams;
    $params['level'] = CONTEXT_COURSE;

    $assignments = $DB->get_records_sql(
        "SELECT ra.id, ra.userid, ra.roleid, ctx.instanceid AS courseid,
                r.shortname, r.name, r.sortorder
           FROM {role_assignments} ra
           JOIN {context} ctx ON ctx.id = ra.contextid
           JOIN {role} r ON r.id = ra.roleid
           JOIN {user} u ON u.id = ra.userid
          WHERE ra.roleid {$rolesql}
            AND ctx.contextlevel = :level
            AND ctx.instanceid {$coursesql}
            AND u.deleted = 0
            AND u.suspended = 0
       ORDER BY r.sortorder ASC, u.lastname ASC, u.firstname ASC",
        $params
    );

    $chosen = [];
    foreach ($assignments as $assignment) {
        $userid = (int) $assignment->userid;
        if (!isset($chosen[$userid])) {
            $chosen[$userid] = $assignment;
        }
    }

    $people = [];
    foreach ($chosen as $assignment) {
        $user = core_user::get_user((int) $assignment->userid);
        if (!$user || !empty($user->deleted)) {
            continue;
        }
        $role = (object) [
            'id' => (int) $assignment->roleid,
            'shortname' => $assignment->shortname,
            'name' => $assignment->name,
        ];
        $context = context_course::instance((int) $assignment->courseid);
        $people[] = [
            'name' => fullname($user),
            'role' => role_get_name($role, $context, ROLENAME_ALIAS),
            'picture' => theme_nextgen_instructor_picture($user),
            'url' => (new moodle_url('/user/profile.php', ['id' => $user->id]))->out(false),
            'active' => count($people) === 0,
        ];
    }
    return $people;
}

/**
 * Portrait for a teacher.
 *
 * An uploaded profile photo is used as it is. Someone without one gets the
 * initials avatar Moodle draws for that account.
 *
 * @param stdClass $user
 * @return string
 */
function theme_nextgen_instructor_picture(stdClass $user): string {
    global $OUTPUT;

    return $OUTPUT->user_picture($user, [
        'size' => 320,
        'link' => false,
        'class' => 'ng-instructor-photo',
        'alttext' => false,
    ]);
}

/**
 * Avatar for the person who wrote a review.
 *
 * An uploaded profile photo is used as it is. Someone without one gets the
 * initials avatar Moodle draws for that account.
 *
 * @param stdClass $user
 * @return string
 */
function theme_nextgen_review_avatar(stdClass $user): string {
    global $OUTPUT;

    return $OUTPUT->user_picture($user, [
        'size' => 64,
        'link' => false,
        'class' => 'ng-review-photo',
        'alttext' => false,
        'visibletoscreenreaders' => false,
    ]);
}

/**
 * Public address for a course. This is Moodle's course page, not a theme script.
 *
 * Visitors who are not enrolled see the sales layout at this same address.
 *
 * @param int $courseid
 * @return moodle_url
 */
function theme_nextgen_course_public_url(int $courseid): moodle_url {
    return new moodle_url('/course/view.php', ['id' => $courseid]);
}

/**
 * Let visitors read the site blog without an account.
 *
 * Moodle's site-level setting still asks for a login, and a new post defaults to
 * that same audience, which guests cannot see. The site blog is raised to world
 * visibility, and posts for "anyone on this site" are published. Drafts stay private.
 *
 * @return void
 */
function theme_nextgen_open_public_blog(): void {
    global $CFG, $DB;

    if ((int) ($CFG->bloglevel ?? 0) < BLOG_GLOBAL_LEVEL) {
        set_config('bloglevel', BLOG_GLOBAL_LEVEL);
        $CFG->bloglevel = BLOG_GLOBAL_LEVEL;
    }

    // "Anyone on this site" is hidden from signed-out visitors. Publish those
    // posts whenever one is waiting, including posts saved before this ran.
    // Drafts stay private.
    $params = [
        'site' => 'site',
        'blog' => 'blog',
        'external' => 'blog_external',
    ];
    $waiting = "publishstate = :site AND (module = :blog OR module = :external)";
    if ($DB->record_exists_select('post', $waiting, $params)) {
        $DB->set_field_select('post', 'publishstate', 'public', $waiting, $params);
    }
    if (!get_config('theme_nextgen', 'publicblog')) {
        set_config('publicblog', 1, 'theme_nextgen');
    }
}

/**
 * Let visitors open the public site when the rest of Moodle requires a login.
 *
 * Force login stays on for dashboards, course work, and administration.
 * These scripts are the public front: home, catalogue, a course sales page,
 * the information pages, and the public blog.
 *
 * @return void
 */
function theme_nextgen_allow_public_entry(): void {
    global $CFG, $SCRIPT;

    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return;
    }
    if (!is_string($SCRIPT) || $SCRIPT === '') {
        return;
    }

    $public = [
        '/index.php',
        '/about.php',
        '/team.php',
        '/contact.php',
        '/privacy.php',
        '/terms.php',
        '/faq.php',
        '/course/index.php',
        '/course/view.php',
        '/blog/index.php',
        '/blog/rss.php',
    ];
    if (in_array($SCRIPT, $public, true)) {
        $CFG->forcelogin = false;
    }
}

/**
 * Keep the public blog readable when the rest of the site requires a login.
 *
 * Moodle's blog index calls require_login() whenever forcelogin is on, even
 * at world visibility. Editing, preferences, and external blogs stay guarded.
 *
 * @return void
 */
function theme_nextgen_allow_public_blog_read(): void {
    global $CFG;

    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return;
    }
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $uri = str_replace('\\', '/', (string) ($_SERVER['REQUEST_URI'] ?? ''));
    $reading = str_ends_with($script, '/blog/index.php')
        || str_ends_with($script, '/blog/rss.php')
        || preg_match('#/blog/(?:index|rss)\.php(?:\?|$)#', $uri);
    if (!$reading) {
        return;
    }
    $CFG->forcelogin = false;
}

/**
 * Send visitors to sign in before they add, edit, or delete a post.
 *
 * The blog editor looks up the current user before it asks for a login.
 * A signed-out visitor has no user record, so that page fails instead of
 * offering the login form.
 *
 * @return void
 */
function theme_nextgen_guard_blog_actions(): void {
    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return;
    }
    if (isloggedin() && !isguestuser()) {
        return;
    }
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $guarded = [
        '/blog/edit.php',
        '/blog/external_blogs.php',
        '/blog/external_blog_edit.php',
        '/blog/preferences.php',
    ];
    $matched = false;
    foreach ($guarded as $path) {
        if (str_ends_with($script, $path)) {
            $matched = true;
            break;
        }
    }
    if (!$matched) {
        return;
    }
    $wants = new moodle_url(qualified_me());
    redirect(new moodle_url('/login/index.php', [
        'wantsurl' => $wants->out_as_local_url(false),
    ]));
}

/**
 * Serve the public course page before course/view.php requires a login.
 */
function theme_nextgen_after_config(): void {
    global $CFG;

    if (during_initial_install() || !empty($CFG->upgraderunning)) {
        return;
    }
    if (($CFG->theme ?? '') === 'nextgen') {
        theme_nextgen_allow_public_entry();
        theme_nextgen_open_public_blog();
        theme_nextgen_allow_public_blog_read();
        theme_nextgen_guard_blog_actions();
        theme_nextgen_serve_unrewritten_module();
        theme_nextgen_publish_public_paths();
        theme_nextgen_redirect_site_support();
        \theme_nextgen\local\module_page::redirect_plain_section();
    }
    \theme_nextgen\local\course_page::serve_public_course();
}

/**
 * Open a module when /learn/{course}/{module} was not rewritten to learn.php.
 *
 * Some hosts send that address to Moodle's router, which answers with a 404.
 * learn.php already knows how to show the section, so use it for the same path.
 */
function theme_nextgen_serve_unrewritten_module(): void {
    global $CFG;

    if (defined('CLI_SCRIPT') && CLI_SCRIPT) {
        return;
    }
    $script = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    // learn.php already dispatches this address. Other scripts only get here when
    // the web server handed the path to Moodle's router.
    if (!str_ends_with($script, '/r.php')) {
        return;
    }

    $request = parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    if (!is_string($request) || $request === '') {
        return;
    }
    $base = parse_url($CFG->wwwroot, PHP_URL_PATH);
    if (is_string($base) && $base !== '' && $base !== '/') {
        $base = rtrim($base, '/');
        if (str_starts_with($request, $base)) {
            $request = substr($request, strlen($base));
        }
    }
    if ($request === '' || $request[0] !== '/') {
        $request = '/' . $request;
    }
    if (!preg_match('#^/learn/([a-z0-9-]+)/([a-z0-9-]+)/?$#', $request, $match)) {
        return;
    }

    $_GET['course'] = $match[1];
    $_GET['module'] = $match[2];
    $_REQUEST['course'] = $match[1];
    $_REQUEST['module'] = $match[2];

    $sectionfile = \theme_nextgen\local\module_page::dispatch();
    if (!is_string($sectionfile)) {
        return;
    }

    // This file is included from a function, so Moodle's page globals have to be
    // brought into scope. course/section.php is written as a top-level script.
    global $DB, $PAGE, $OUTPUT, $USER, $COURSE, $SESSION, $THEME;
    require($sectionfile);
    exit(0);
}

/**
 * Send Moodle's contact-site-support page to the theme contact page.
 */
function theme_nextgen_redirect_site_support(): void {
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    if (!is_string($script) || !str_ends_with(str_replace('\\', '/', $script), '/user/contactsitesupport.php')) {
        return;
    }
    redirect(\theme_nextgen\local\public_page::url('contact'));
}

/**
 * Where the enrol button goes. A signed-in user opens the enrol page.
 * A visitor is sent through login first, then back to that enrol page.
 *
 * @param int $courseid
 * @return moodle_url
 */
function theme_nextgen_course_enrol_url(int $courseid): moodle_url {
    $enrol = new moodle_url('/enrol/index.php', ['id' => $courseid]);
    if (isloggedin() && !isguestuser()) {
        return $enrol;
    }
    return new moodle_url('/login/index.php', [
        'wantsurl' => $enrol->out_as_local_url(false),
    ]);
}

/**
 * Whether this request should use the course sales layout.
 *
 * Only the public sales page uses that layout. Course, section, and activity
 * pages stay on Moodle's own layout so the course index, editor, and
 * availability tools keep working.
 *
 * @param moodle_page $page
 * @return bool
 */
function theme_nextgen_is_course_detail_page(moodle_page $page): bool {
    return (string) $page->pagetype === 'theme-nextgen-course';
}

/**
 * Save checkout profile fields when the inline form is submitted.
 *
 * @param int $courseid
 */
function theme_nextgen_enrol_checkout_process(int $courseid): void {
    global $CFG, $DB, $SESSION, $USER;

    if (!isloggedin() || isguestuser()) {
        return;
    }
    if (!user_not_fully_set_up($USER, true)) {
        return;
    }

    require_once($CFG->dirroot . '/user/lib.php');

    $enrolurl = new moodle_url('/enrol/index.php', ['id' => $courseid]);
    $form = new \theme_nextgen\form\checkout_profile($enrolurl, ['user' => $USER]);
    if ($form->is_cancelled()) {
        redirect(new moodle_url('/course/view.php', ['id' => $courseid]));
    }
    $data = $form->get_data();
    if (!$data) {
        return;
    }

    $user = $DB->get_record('user', ['id' => (int) $USER->id], '*', MUST_EXIST);
    $user->firstname = trim((string) $data->firstname);
    $user->lastname = trim((string) $data->lastname);
    $newemail = trim((string) $data->email);
    $emailchanged = (strcasecmp($newemail, (string) $user->email) !== 0);
    $emailblocked = false;
    if ($emailchanged) {
        if (!empty($CFG->emailchangeconfirmation) && !has_capability('moodle/user:update', context_system::instance())) {
            $emailblocked = true;
        } else {
            $user->email = $newemail;
            $user->emailstop = 0;
        }
    }
    $user->timemodified = time();
    user_update_user($user, false, false);
    unset($SESSION->fullysetupstrict);
    \core\session\manager::set_user($DB->get_record('user', ['id' => $user->id], '*', MUST_EXIST));
    if ($emailblocked) {
        redirect(
            $enrolurl,
            get_string('checkoutemailunchanged', 'theme_nextgen'),
            null,
            \core\output\notification::NOTIFY_INFO
        );
    }
    redirect($enrolurl);
}

/**
 * Context for the dedicated enrolment checkout page.
 *
 * @param \stdClass $course
 * @param string[] $widgets
 * @param string $message
 * @param string $continuebutton
 * @return array
 */
function theme_nextgen_enrol_checkout_context(
    \stdClass $course,
    array $widgets,
    string $message = '',
    string $continuebutton = ''
): array {
    global $SESSION, $USER;

    $listelement = new core_course_list_element($course);
    $card = theme_nextgen_course_card_data($listelement);
    $context = context_course::instance((int) $course->id);
    $outline = theme_nextgen_course_outline($listelement, $context);
    $activitycount = (int) $outline['activities'];
    $sectioncount = count($outline['sections']);
    $includes = [];
    if ($activitycount === 1) {
        $includes[] = ['label' => get_string('courseactivity', 'theme_nextgen')];
    } else if ($activitycount > 1) {
        $includes[] = ['label' => get_string('courseactivities', 'theme_nextgen', $activitycount)];
    }
    if ($sectioncount === 1) {
        $includes[] = ['label' => get_string('coursesection', 'theme_nextgen')];
    } else if ($sectioncount > 1) {
        $includes[] = ['label' => get_string('coursesections', 'theme_nextgen', $sectioncount)];
    }
    $price = (string) ($card['price'] ?? '');

    $signedin = isloggedin() && !isguestuser();
    $isguest = isguestuser() || !isloggedin();
    $enrolurl = new moodle_url('/enrol/index.php', ['id' => (int) $course->id]);
    $needsprofile = $signedin && user_not_fully_set_up($USER, true);
    $needsbasic = $needsprofile && (
        empty($USER->firstname) || empty($USER->lastname) || empty($USER->email)
    );
    $needscustom = $needsprofile && !$needsbasic;
    $profileform = '';
    $profileurl = '';
    if ($needsbasic) {
        $form = new \theme_nextgen\form\checkout_profile($enrolurl, ['user' => $USER]);
        $profileform = $form->render();
    } else if ($needscustom) {
        $SESSION->wantsurl = $enrolurl->out(false);
        $profileurl = (new moodle_url('/user/edit.php', [
            'id' => (int) $USER->id,
            'course' => SITEID,
        ]))->out(false);
    }

    $accountname = '';
    $accountemail = '';
    if ($signedin) {
        $accountname = fullname($USER);
        $accountemail = (string) $USER->email;
    }

    $canpay = $signedin && !$needsprofile && !empty($widgets);
    $steps = [
        [
            'label' => get_string('checkoutstepcourse', 'theme_nextgen'),
            'state' => 'done',
            'done' => true,
            'current' => false,
        ],
        [
            'label' => get_string('checkoutstepaccount', 'theme_nextgen'),
            'state' => ($isguest || $needsprofile) ? 'current' : 'done',
            'done' => $signedin && !$needsprofile,
            'current' => $isguest || $needsprofile,
        ],
        [
            'label' => get_string('checkoutsteppayment', 'theme_nextgen'),
            'state' => $canpay ? 'current' : 'todo',
            'done' => false,
            'current' => $canpay,
        ],
    ];

    if ($isguest && $message === '') {
        $message = get_string('checkoutguesttext', 'theme_nextgen');
        $continuebutton = html_writer::link(
            theme_nextgen_course_enrol_url((int) $course->id),
            get_string('checkoutguestcta', 'theme_nextgen'),
            ['class' => 'btn btn-primary']
        );
    } else if (!$isguest && !$needsprofile && !$widgets && $message === '') {
        $message = get_string('checkoutclosedtext', 'theme_nextgen');
        $continuebutton = html_writer::link(
            new moodle_url('/course/view.php', ['id' => (int) $course->id]),
            get_string('checkoutback', 'theme_nextgen'),
            ['class' => 'btn btn-primary']
        );
    }

    return [
        'fullname' => $card['fullname'],
        'image' => $card['image'],
        'hasimage' => !empty($card['image']),
        'courseurl' => (new moodle_url('/course/view.php', ['id' => (int) $course->id]))->out(false),
        'hasprice' => $price !== '',
        'price' => $price,
        'hasincludes' => !empty($includes),
        'includes' => $includes,
        'haswidgets' => $canpay,
        'widgets' => array_values($widgets),
        'hasmessage' => $message !== '' && !$needsbasic && !$needscustom,
        'message' => $message,
        'continuebutton' => $continuebutton,
        'needsprofile' => $needsbasic || $needscustom,
        'needsbasic' => $needsbasic,
        'needscustom' => $needscustom,
        'profileform' => $profileform,
        'profileurl' => $profileurl,
        'hasaccount' => $accountname !== '',
        'accountname' => $accountname,
        'accountemail' => $accountemail,
        'steps' => $steps,
    ];
}

/**
 * Template context for the course sales layout, or null on other pages.
 *
 * @return array|null
 */
function theme_nextgen_course_detail_context(): ?array {
    global $OUTPUT, $PAGE, $SESSION, $USER;

    if (!theme_nextgen_is_course_detail_page($PAGE)) {
        return null;
    }

    $course = new core_course_list_element($PAGE->course);
    $context = context_course::instance($course->id);
    theme_nextgen_prepare_card_facts([(int) $course->id]);
    $card = theme_nextgen_course_card_data($course);
    $extra = theme_nextgen_course_extra_fields($course->id);

    $summary = format_text($course->summary, $course->summaryformat, ['context' => $context]);
    $excerpt = theme_nextgen_text_excerpt(html_to_text($summary, 0, false), 220);

    $instructors = [];
    foreach ($course->get_course_contacts() as $contact) {
        $user = core_user::get_user($contact['user']->id);
        if (!$user || !empty($user->deleted)) {
            continue;
        }
        $credential = trim((string) $user->department);
        if ($credential === '') {
            $credential = trim((string) $user->institution);
        }
        if ($credential === '') {
            $credential = $contact['rolename'];
        }
        $bio = '';
        if (trim(strip_tags((string) $user->description)) !== '') {
            $bio = format_text($user->description, (int) $user->descriptionformat, [
                'context' => \context_user::instance($user->id),
            ]);
        }
        $reach = theme_nextgen_teacher_reach((int) $user->id);
        $instructors[] = [
            'name' => $contact['username'],
            'role' => $contact['rolename'],
            'credential' => $credential,
            'hasbio' => $bio !== '',
            'bio' => $bio,
            'courseslabel' => get_string('instructorcourses', 'theme_nextgen', $reach['courses']),
            'hasstudents' => (int) $reach['students'] > 0,
            'studentslabel' => get_string('instructorstudents', 'theme_nextgen', $reach['students']),
            'picture' => $OUTPUT->user_picture($user, [
                'size' => 160,
                'link' => false,
                'class' => 'ng-course-teacher-photo',
            ]),
            'url' => (new moodle_url('/user/profile.php', ['id' => $user->id]))->out(false),
        ];
    }
    $teacher = $instructors[0] ?? null;

    $customoutcomes = theme_nextgen_learning_from_raw($extra['ngoutcomes'], $context);
    $outcomes = $customoutcomes['points'];
    $outcometitle = $outcomes ? $customoutcomes['title'] : '';
    $outcomelead = $outcomes ? $customoutcomes['lead'] : '';
    $outline = theme_nextgen_course_outline($course, $context);
    $sectionnames = $outline['sectionnames'];
    $summaryoutcomes = $outline['summaryoutcomes'];
    $activitycount = $outline['activities'];
    $certificates = $outline['certificates'];
    if (!$outcomes) {
        $outcomes = $summaryoutcomes;
        $outcometitle = $outline['outcometitle'];
        $outcomelead = $outline['outcomelead'];
    }
    if ($outcometitle === '') {
        $outcometitle = get_string('courseoutcomes', 'theme_nextgen');
    }
    if (!$outcomes) {
        foreach ($sectionnames as $name) {
            $outcomes[] = ['text' => $name];
        }
    }

    $languageinfo = theme_nextgen_course_language($extra['nglanguage'], (string) ($course->lang ?? ''));
    $language = $languageinfo['label'];
    $offered = theme_nextgen_certificate_is_offered($extra['ngcertificate'], !empty($certificates));
    $certificatevalue = theme_nextgen_certificate_label($extra['ngcertificate'], $offered);
    $certificatenote = ($offered && $certificatevalue !== get_string('yes')) ? $certificatevalue : '';

    $categoryname = '';
    $category = null;
    if (!empty($course->category)) {
        $category = core_course_category::get((int) $course->category, IGNORE_MISSING, true);
        if ($category) {
            $categoryname = format_string($category->name, true, [
                'context' => $category->get_context(),
                'escape' => false,
            ]);
        }
    }

    $facts = [];
    $pushfact = static function (array &$facts, string $stringkey, string $value, string $tone = ''): void {
        if ($value === '') {
            return;
        }
        $facts[] = [
            'label' => get_string($stringkey, 'theme_nextgen'),
            'value' => $value,
            'tone' => $tone,
            'hastone' => $tone !== '',
        ];
    };
    $pushfact($facts, 'courselevel', $extra['nglevel']);
    $pushfact($facts, 'courselength', $extra['ngduration']);
    $pushfact($facts, 'courseeffort', $extra['ngeffort']);
    $pushfact($facts, 'courselanguage', $language);
    $pushfact($facts, 'coursecertificate', $certificatevalue, $offered ? 'yes' : 'no');
    if (!empty($course->startdate)) {
        $starts = preg_replace('/^0/', '', userdate((int) $course->startdate, '%d %B %Y'));
        $pushfact($facts, 'coursestarts', $starts);
    }
    if (!empty($course->enddate)) {
        $ends = preg_replace('/^0/', '', userdate((int) $course->enddate, '%d %B %Y'));
        $pushfact($facts, 'courseends', $ends);
    }
    $sectioncount = count($outline['sections']);
    if ($sectioncount > 0) {
        $pushfact($facts, 'coursesectionslabel', (string) $sectioncount);
    }
    if ($activitycount > 0) {
        $pushfact($facts, 'courseactivitieslabel', (string) $activitycount);
    }
    $resourcecount = (int) $outline['resources'];
    if ($resourcecount > 0) {
        $pushfact($facts, 'courseresourceslabel', (string) $resourcecount);
    }
    if (!empty($course->enablecompletion)) {
        $pushfact($facts, 'coursecompletion', get_string('coursecompletiontracked', 'theme_nextgen'));
    }
    $pushfact($facts, 'coursestructure', theme_nextgen_course_format_label((string) $course->format));
    $pushfact($facts, 'coursecategory', $categoryname);

    $enrolled = is_enrolled($context, $USER, '', true);
    if ($enrolled) {
        $enrolurl = (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false);
        $enrollabel = get_string('coursecontinue', 'theme_nextgen');
    } else {
        // Guests and visitors go through login with wantsurl set to the enrol page
        // so a successful sign-in returns them to enrolment, not /my/.
        $enrolurl = theme_nextgen_course_enrol_url((int) $course->id)->out(false);
        $enrollabel = get_string('courseenrol', 'theme_nextgen');
    }

    $breadcrumbs = [
        [
            'label' => get_string('home'),
            'url' => (new moodle_url('/'))->out(false),
        ],
        [
            'label' => get_string('courses'),
            'url' => (new moodle_url('/course/index.php'))->out(false),
        ],
    ];
    if ($category && $categoryname !== '') {
        $breadcrumbs[] = [
            'label' => $categoryname,
            'url' => (new moodle_url('/course/index.php', ['categoryid' => $category->id]))->out(false),
        ];
    }
    $breadcrumbs[] = [
        'label' => $card['fullname'],
        'url' => '',
        'current' => true,
    ];

    $updatedon = '';
    if (!empty($course->timemodified)) {
        $updatedon = preg_replace('/^0/', '', userdate((int) $course->timemodified, '%d %B %Y'));
    }

    $price = $card['price'];
    $distribution = theme_nextgen_rating_distribution($extra['ngratingdist']);
    $reviewlist = theme_nextgen_review_lines($extra['ngreviewlist']);
    $submitted = theme_nextgen_submitted_reviews((int) $course->id);
    if ($submitted !== null) {
        $reviewlist = array_merge($submitted['items'], $reviewlist);
        $distribution = $submitted['distribution'];
    }
    // The rating still counts every review. The list itself stays at three.
    $reviewlist = array_slice($reviewlist, 0, 3);
    $reviewform = theme_nextgen_review_form((int) $course->id);

    $hasenrolsuccess = false;
    if (!empty($SESSION->theme_nextgen_enrolsuccess)
            && (int) $SESSION->theme_nextgen_enrolsuccess === (int) $course->id
            && $enrolled) {
        $hasenrolsuccess = true;
        unset($SESSION->theme_nextgen_enrolsuccess);
    }

    return [
        'fullname' => $card['fullname'],
        'hasexcerpt' => $excerpt !== '',
        'excerpt' => $excerpt,
        'hassummary' => trim(strip_tags($summary)) !== '',
        'summary' => $summary,
        'image' => $card['image'],
        'hasteacher' => $teacher !== null,
        'teacher' => $teacher ?? [],
        'hasinstructors' => !empty($instructors),
        'instructors' => $instructors,
        'sitename' => theme_nextgen_app_name(),
        'hascertificate' => $offered,
        'certificatenote' => $certificatenote,
        'certificates' => $certificates,
        'hasreviews' => !empty($card['hasreviews']),
        'reviewcount' => $card['reviews'] ?? 0,
        'reviewslabel' => $card['reviewslabel'] ?? '',
        'ratingslabel' => $card['reviewslabel'] ?? theme_nextgen_review_count_label((int) ($card['reviews'] ?? 0)),
        'hasrating' => $card['hasrating'],
        'rating' => $card['rating'],
        'ratinglabel' => $card['ratinglabel'],
        'stars' => $card['stars'],
        'hasdistribution' => !empty($distribution),
        'distribution' => $distribution,
        'hasreviewlist' => !empty($reviewlist),
        'reviewlist' => $reviewlist,
        'canreview' => $reviewform['canreview'],
        'reviewlogin' => $reviewform['reviewlogin'],
        'reviewloginurl' => $reviewform['reviewloginurl'],
        'reviewaction' => $reviewform['reviewaction'],
        'sesskey' => $reviewform['sesskey'],
        'reviewcourseid' => $course->id,
        'ratingchoices' => $reviewform['ratingchoices'],
        'ownreview' => $reviewform['ownreview'],
        'reviewbutton' => $reviewform['reviewbutton'],
        'hasprice' => $price !== '',
        'price' => $price,
        'hasfacts' => !empty($facts),
        'facts' => $facts,
        'hasoutcomes' => !empty($outcomes),
        'outcometitle' => $outcometitle,
        'hasoutcomelead' => $outcomelead !== '',
        'outcomelead' => $outcomelead,
        'outcomes' => $outcomes,
        'hasrequirements' => $extra['ngrequirements'] !== '',
        'requirements' => theme_nextgen_text_lines($extra['ngrequirements']),
        'breadcrumbs' => $breadcrumbs,
        'enrolurl' => $enrolurl,
        'enrollabel' => $enrollabel,
        'quoteurl' => \theme_nextgen\local\public_page::url('contact')->out(false),
        'shareurl' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
        'showactivities' => $PAGE->pagelayout === 'course',
        'hasoutline' => !empty($outline['sections']),
        'outlineempty' => empty($outline['sections']),
        'outlinecounts' => $outline['counts'],
        'sections' => $outline['sections'],
        'bestseller' => !empty($card['bestseller']),
        'hasstudents' => (int) ($card['enrolments'] ?? 0) > 0,
        'students' => get_string('studentcount', 'theme_nextgen', (int) ($card['enrolments'] ?? 0)),
        'hasupdated' => $updatedon !== '',
        'updated' => $updatedon === '' ? '' : get_string('lastupdated', 'theme_nextgen', $updatedon),
        'hasmeta' => !empty($card['bestseller']) || $card['hasrating']
            || (int) ($card['enrolments'] ?? 0) > 0 || $updatedon !== '',
        'hasenrolsuccess' => $hasenrolsuccess,
        'enrolsuccessurl' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
    ];
}

/**
 * Visible sections and activities for the course content tab.
 *
 * Names of visible items are catalogue information. A link is included only
 * when this user is allowed to open that activity.
 *
 * @param core_course_list_element $course
 * @param context_course $context
 * @return array
 */
function theme_nextgen_course_outline(core_course_list_element $course, context_course $context): array {
    global $OUTPUT;

    $sections = [];
    $sectionnames = [];
    $objectiveblocks = [];
    $certificates = [];
    $activities = 0;
    $resources = 0;

    $modinfo = get_fast_modinfo($course->id);
    $number = 0;
    foreach ($modinfo->get_section_info_all() as $section) {
        if (empty($section->visible)) {
            continue;
        }
        $cmids = $modinfo->sections[(int) $section->sectionnum] ?? [];
        $items = [];
        foreach ($cmids as $cmid) {
            $cm = $modinfo->get_cm($cmid);
            if (empty($cm->visible) || !empty($cm->deletioninprogress) || $cm->modname === 'label') {
                continue;
            }
            $archetype = plugin_supports('mod', $cm->modname, FEATURE_MOD_ARCHETYPE, MOD_ARCHETYPE_OTHER);
            $isresource = $archetype === MOD_ARCHETYPE_RESOURCE;
            if ($isresource) {
                $resources++;
            } else {
                $activities++;
            }
            $name = format_string($cm->name, true, ['context' => $context]);
            $icon = $cm->get_icon_url($OUTPUT);
            $islocked = !$cm->uservisible;
            $items[] = [
                'name' => $name,
                'iconurl' => $icon ? $icon->out(false) : '',
                'url' => ($cm->uservisible && $cm->url) ? $cm->url->out(false) : '',
                'locked' => $islocked,
            ];
            if (in_array($cm->modname, ['customcert', 'certificate', 'coursecertificate'], true)) {
                $certificates[] = [
                    'name' => $name,
                    'url' => ($cm->uservisible && $cm->url) ? $cm->url->out(false) : '',
                ];
            }
        }

        if ((int) $section->sectionnum === 0 && !$items) {
            continue;
        }
        $named = trim((string) ($section->name ?? '')) !== '';
        if ((int) $section->sectionnum !== 0 && !$named && !$items) {
            continue;
        }

        $name = get_section_name((int) $course->id, $section);
        $number++;
        $sections[] = [
            'number' => $number,
            'name' => $name,
            'open' => $number === 1,
            'hasactivities' => !empty($items),
            'activities' => $items,
        ];
        if ((int) $section->sectionnum !== 0 && $named) {
            $sectionnames[] = $name;
            $block = theme_nextgen_learning_block(
                (string) $section->summary,
                (int) $section->summaryformat,
                $context
            );
            if ($block['points']) {
                $objectiveblocks[] = $block;
            }
        }
    }

    $objectives = theme_nextgen_merge_learning_blocks($objectiveblocks);

    $counts = get_string('contentcounts', 'theme_nextgen', (object) [
        'sections' => count($sections),
        'activities' => $activities,
        'resources' => $resources,
    ]);

    return [
        'sections' => $sections,
        'sectionnames' => $sectionnames,
        'summaryoutcomes' => $objectives['points'],
        'outcometitle' => $objectives['title'],
        'outcomelead' => $objectives['lead'],
        'certificates' => $certificates,
        'activities' => $activities,
        'resources' => $resources,
        'counts' => $counts,
    ];
}

/**
 * Courses and students reached by one teacher.
 *
 * Courses are those where the person is a teacher. Students are the distinct
 * learners enrolled in those courses.
 *
 * @param int $userid
 * @return array{courses: int, students: int}
 */
function theme_nextgen_teacher_reach(int $userid): array {
    global $DB;

    $roleids = $DB->get_fieldset_select('role', 'id', 'shortname IN (?, ?)', ['editingteacher', 'teacher']);
    if (!$roleids) {
        return ['courses' => 0, 'students' => 0];
    }

    [$insql, $params] = $DB->get_in_or_equal($roleids, SQL_PARAMS_NAMED);
    $params['userid'] = $userid;
    $params['level'] = CONTEXT_COURSE;
    $courseids = $DB->get_fieldset_sql(
        "SELECT DISTINCT ctx.instanceid
           FROM {role_assignments} ra
           JOIN {context} ctx ON ctx.id = ra.contextid
          WHERE ra.userid = :userid
            AND ra.roleid {$insql}
            AND ctx.contextlevel = :level",
        $params
    );
    $courseids = array_values(array_filter(array_map('intval', $courseids), function (int $id): bool {
        return $id !== (int) SITEID;
    }));

    $students = 0;
    $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
    if ($courseids && $studentrole) {
        [$csql, $cparams] = $DB->get_in_or_equal($courseids, SQL_PARAMS_NAMED);
        $cparams['roleid'] = $studentrole;
        $cparams['level'] = CONTEXT_COURSE;
        $students = (int) $DB->count_records_sql(
            "SELECT COUNT(DISTINCT ra.userid)
               FROM {role_assignments} ra
               JOIN {context} ctx ON ctx.id = ra.contextid
              WHERE ra.roleid = :roleid
                AND ctx.contextlevel = :level
                AND ctx.instanceid {$csql}",
            $cparams
        );
    }

    return [
        'courses' => count($courseids),
        'students' => $students,
    ];
}

/**
 * Percentages for 5, 4, 3, 2 and 1 stars.
 *
 * The stored value is five numbers separated by commas.
 *
 * @param string $raw
 * @return array
 */
function theme_nextgen_rating_distribution(string $raw): array {
    $parts = array_map('trim', explode(',', $raw));
    if (count($parts) !== 5) {
        return [];
    }
    $rows = [];
    foreach ([5, 4, 3, 2, 1] as $index => $level) {
        if (!is_numeric($parts[$index])) {
            return [];
        }
        $percent = (int) round(max(0, min(100, (float) $parts[$index])));
        $rows[] = [
            'level' => $level,
            'percent' => $percent,
        ];
    }
    return $rows;
}

/**
 * Whether the student review table has been installed.
 *
 * @return bool
 */
function theme_nextgen_review_table_ready(): bool {
    global $DB;

    static $ready = null;
    if ($ready === null) {
        $ready = $DB->get_manager()->table_exists('theme_nextgen_review');
    }
    return $ready;
}

/**
 * Star states for a rating from 0 to 5.
 *
 * @param float $value
 * @return array
 */
function theme_nextgen_star_states(float $value): array {
    $stars = [];
    for ($i = 1; $i <= 5; $i++) {
        if ($value >= $i) {
            $state = 'full';
        } else if ($value >= ($i - 0.5)) {
            $state = 'half';
        } else {
            $state = 'empty';
        }
        $stars[] = ['state' => $state];
    }
    return $stars;
}

/**
 * Singular or plural label for a review count.
 *
 * @param int $count
 * @return string
 */
function theme_nextgen_review_count_label(int $count): string {
    if ($count === 1) {
        return get_string('reviewcountone', 'theme_nextgen', $count);
    }
    return get_string('reviewcount', 'theme_nextgen', $count);
}

/**
 * Average and count of saved student reviews, or null when there are none.
 *
 * @param int $courseid
 * @return array{average: float, count: int}|null
 */
function theme_nextgen_submitted_review_stats(int $courseid): ?array {
    global $DB;

    $fact = theme_nextgen_card_facts($courseid);
    if ($fact !== null) {
        return $fact['reviewstats'];
    }

    if (!theme_nextgen_review_table_ready()) {
        return null;
    }

    $row = $DB->get_record_sql(
        "SELECT COUNT(1) AS reviewcount, AVG(r.rating) AS ratingavg
           FROM {theme_nextgen_review} r
           JOIN {user} u ON u.id = r.userid
          WHERE r.courseid = :courseid AND u.deleted = 0",
        ['courseid' => $courseid]
    );
    if (!$row || (int) $row->reviewcount < 1) {
        return null;
    }
    return [
        'average' => round((float) $row->ratingavg, 1),
        'count' => (int) $row->reviewcount,
    ];
}

/**
 * Saved student reviews for the course page, newest first.
 *
 * @param int $courseid
 * @return array{items: array, distribution: array, count: int, average: float}|null
 */
function theme_nextgen_submitted_reviews(int $courseid): ?array {
    global $DB;

    if (!theme_nextgen_review_table_ready()) {
        return null;
    }

    $namefields = \core_user\fields::for_name()->with_userpic()->get_sql('u', false, '', '', false)->selects;
    $records = $DB->get_records_sql(
        "SELECT r.id AS reviewid, r.rating, r.reviewtext, r.timemodified, {$namefields}
           FROM {theme_nextgen_review} r
           JOIN {user} u ON u.id = r.userid
          WHERE r.courseid = :courseid AND u.deleted = 0
       ORDER BY r.timemodified DESC",
        ['courseid' => $courseid]
    );
    if (!$records) {
        return null;
    }

    $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    $items = [];
    $total = 0;
    foreach ($records as $record) {
        $score = max(1, min(5, (int) $record->rating));
        $counts[$score]++;
        $total += $score;
        $name = fullname($record);
        $date = preg_replace('/^0/', '', userdate((int) $record->timemodified, '%d %B %Y'));
        $items[] = [
            'name' => $name,
            'date' => $date,
            'initials' => theme_nextgen_initials($name),
            'avatar' => theme_nextgen_review_avatar($record),
            'text' => $record->reviewtext,
            'stars' => theme_nextgen_star_states($score),
        ];
    }

    $count = count($records);
    $distribution = [];
    foreach ([5, 4, 3, 2, 1] as $level) {
        $distribution[] = [
            'level' => $level,
            'percent' => (int) round($counts[$level] * 100 / $count),
        ];
    }

    return [
        'items' => $items,
        'distribution' => $distribution,
        'count' => $count,
        'average' => round($total / $count, 1),
    ];
}

/**
 * Save one review for a student. A second save from the same student updates it.
 *
 * @param int $courseid
 * @param int $userid
 * @param int $rating
 * @param string $text
 * @return bool True when an existing review was updated
 */
function theme_nextgen_save_review(int $courseid, int $userid, int $rating, string $text): bool {
    global $DB;

    $existing = $DB->get_record('theme_nextgen_review', [
        'courseid' => $courseid,
        'userid' => $userid,
    ]);
    $now = time();
    if ($existing) {
        $existing->rating = $rating;
        $existing->reviewtext = $text;
        $existing->timemodified = $now;
        $DB->update_record('theme_nextgen_review', $existing);
        return true;
    }

    $record = new stdClass();
    $record->courseid = $courseid;
    $record->userid = $userid;
    $record->rating = $rating;
    $record->reviewtext = $text;
    $record->timecreated = $now;
    $record->timemodified = $now;
    $DB->insert_record('theme_nextgen_review', $record);
    return false;
}

/**
 * Whether this learner has finished every activity that tracks completion.
 *
 * @param stdClass $course
 * @param int $userid
 * @return bool
 */
function theme_nextgen_course_finished(stdClass $course, int $userid): bool {
    $progress = \core_completion\progress::get_course_progress_percentage($course, $userid);
    return $progress !== null && (float) $progress >= 100;
}

/**
 * Last visible section that contains an activity, and that activity.
 *
 * @param stdClass $course
 * @return array{sectionid: int, sectionnum: int, cmid: int}
 */
function theme_nextgen_course_end_point(stdClass $course): array {
    $modinfo = get_fast_modinfo($course);
    $sectionid = 0;
    $sectionnum = 0;
    $cmid = 0;
    foreach ($modinfo->get_section_info_all() as $info) {
        if (!$info->uservisible) {
            continue;
        }
        $cmids = $modinfo->sections[$info->section] ?? [];
        $lastvisible = 0;
        foreach ($cmids as $id) {
            $cm = $modinfo->cms[$id] ?? null;
            if (!$cm || !$cm->uservisible || !empty($cm->deletioninprogress)) {
                continue;
            }
            $lastvisible = (int) $cm->id;
        }
        if ($lastvisible < 1) {
            continue;
        }
        $sectionid = (int) $info->id;
        $sectionnum = (int) $info->section;
        $cmid = $lastvisible;
    }
    return [
        'sectionid' => $sectionid,
        'sectionnum' => $sectionnum,
        'cmid' => $cmid,
    ];
}

/**
 * Review form for a learner who has finished the course.
 *
 * Shown under the course modules, on the last module, and on the last activity.
 * Hidden until completion reaches 100 percent.
 *
 * @return array|null
 */
function theme_nextgen_learning_review_context(): ?array {
    global $PAGE, $USER;

    if ($PAGE->user_is_editing() || !isloggedin() || isguestuser()) {
        return null;
    }
    $course = $PAGE->course;
    if (empty($course->id) || (int) $course->id === SITEID) {
        return null;
    }

    $pagetype = (string) $PAGE->pagetype;
    $sectionid = \theme_nextgen\local\module_page::$currentsectionid;
    $sectionpage = str_contains($pagetype, 'course-view-section');
    if ($sectionpage && $sectionid < 1) {
        $sectionid = optional_param('id', 0, PARAM_INT);
    }
    $coursehome = str_starts_with($pagetype, 'course-view') && !$sectionpage && $sectionid < 1;
    $cm = $PAGE->cm;
    $cmid = ($cm && !empty($cm->id)) ? (int) $cm->id : 0;
    if (!$coursehome && $sectionid < 1 && $cmid < 1) {
        return null;
    }

    $context = context_course::instance($course->id);
    if (!is_enrolled($context, $USER, '', true)) {
        return null;
    }
    if (!theme_nextgen_course_finished($course, (int) $USER->id)) {
        return null;
    }

    $returnkind = '';
    $returnid = 0;
    if ($coursehome) {
        $sectionnum = optional_param('section', null, PARAM_INT);
        $endpoint = $sectionnum === null ? null : theme_nextgen_course_end_point($course);
        if ($sectionnum === null || ($endpoint && (int) $sectionnum === $endpoint['sectionnum'])) {
            $returnkind = 'course';
            $returnid = (int) $course->id;
        }
    } else {
        $endpoint = theme_nextgen_course_end_point($course);
        if ($sectionid > 0 && $sectionid === $endpoint['sectionid']) {
            $returnkind = 'section';
            $returnid = $sectionid;
        } else if ($cmid > 0 && $cmid === $endpoint['cmid']) {
            $returnkind = 'activity';
            $returnid = $cmid;
        }
    }
    if ($returnkind === '') {
        return null;
    }

    $form = theme_nextgen_review_form((int) $course->id);
    if (empty($form['canreview'])) {
        return null;
    }
    $form['returnkind'] = $returnkind;
    $form['returnid'] = $returnid;
    $form['reviewcourseid'] = (int) $course->id;
    return $form;
}

/**
 * Where a saved review sends the learner.
 *
 * The learning pages return to the form. The public course page returns to its reviews tab.
 *
 * @param stdClass $course
 * @param string $kind course, section, activity, or empty
 * @param int $id
 * @return moodle_url
 */
function theme_nextgen_review_redirect_url(stdClass $course, string $kind, int $id): moodle_url {
    global $DB;

    $anchor = 'ng-course-review';
    if ($kind === 'section' && $id > 0) {
        $section = $DB->get_record('course_sections', ['id' => $id, 'course' => $course->id]);
        if ($section) {
            $url = \theme_nextgen\local\module_page::url($course, $section);
            $url->set_anchor($anchor);
            return $url;
        }
    }
    if ($kind === 'activity' && $id > 0) {
        $cm = null;
        $cms = get_fast_modinfo($course)->cms;
        if (isset($cms[$id]) && (int) $cms[$id]->course === (int) $course->id && $cms[$id]->url) {
            $cm = $cms[$id];
        }
        if ($cm) {
            $url = new moodle_url($cm->url);
            $url->set_anchor($anchor);
            return $url;
        }
    }
    if ($kind === 'course' || $kind === 'section' || $kind === 'activity') {
        return new moodle_url('/course/view.php', ['id' => $course->id], $anchor);
    }
    return new moodle_url('/course/view.php', ['id' => $course->id], 'ng-panel-reviews');
}

/**
 * Review form state for the course page.
 *
 * Signed-in users get the form. Everyone else gets a link through the login page.
 *
 * @param int $courseid
 * @return array
 */
function theme_nextgen_review_form(int $courseid): array {
    global $DB, $USER;

    $return = new moodle_url('/course/view.php', ['id' => $courseid], 'ng-panel-reviews');
    $loggedin = isloggedin() && !isguestuser();
    $own = null;
    if ($loggedin && theme_nextgen_review_table_ready()) {
        $own = $DB->get_record('theme_nextgen_review', [
            'courseid' => $courseid,
            'userid' => $USER->id,
        ]);
    }

    $choices = [];
    $ownrating = $own ? (int) $own->rating : 0;
    for ($value = 5; $value >= 1; $value--) {
        $choices[] = [
            'value' => $value,
            'id' => 'ng-review-star-' . $courseid . '-' . $value,
            'label' => get_string('reviewstars', 'theme_nextgen', $value),
            'checked' => $ownrating === $value,
        ];
    }

    return [
        'canreview' => $loggedin,
        'reviewlogin' => !$loggedin,
        'reviewloginurl' => (new moodle_url('/login/index.php', [
            'wantsurl' => $return->out_as_local_url(false),
        ]))->out(false),
        'reviewaction' => (new moodle_url('/theme/nextgen/review.php'))->out(false),
        'sesskey' => $loggedin ? sesskey() : '',
        'ratingchoices' => $choices,
        'ownreview' => $own ? $own->reviewtext : '',
        'reviewbutton' => get_string($own ? 'reviewupdate' : 'reviewsubmit', 'theme_nextgen'),
    ];
}

/**
 * Written reviews stored as one line each: Name|date|stars|text.
 *
 * @param string $raw
 * @return array
 */
function theme_nextgen_review_lines(string $raw): array {
    $reviews = [];
    $lines = preg_split("/\r\n|\n|\r/", trim($raw));
    if (!$lines) {
        return [];
    }
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $bits = array_map('trim', explode('|', $line, 4));
        if (count($bits) < 4 || $bits[0] === '' || $bits[3] === '') {
            continue;
        }
        $score = (int) $bits[2];
        if ($score < 1 || $score > 5) {
            $score = 0;
        }
        $stars = [];
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = ['state' => ($score >= $i) ? 'full' : 'empty'];
        }
        $reviews[] = [
            'name' => $bits[0],
            'date' => $bits[1],
            'initials' => theme_nextgen_initials($bits[0]),
            'text' => $bits[3],
            'stars' => $stars,
        ];
    }
    return $reviews;
}

/**
 * Up to two initials from a person's name.
 *
 * @param string $name
 * @return string
 */
function theme_nextgen_initials(string $name): string {
    $letters = '';
    foreach (preg_split('/\s+/', trim($name)) as $part) {
        if ($part === '') {
            continue;
        }
        $letters .= \core_text::strtoupper(\core_text::substr($part, 0, 1));
        if (\core_text::strlen($letters) >= 2) {
            break;
        }
    }
    return $letters;
}

/**
 * Optional course custom fields. Missing fields stay empty.
 *
 * @param int $courseid
 * @return array
 */
function theme_nextgen_course_extra_fields(int $courseid): array {
    $fact = theme_nextgen_card_facts($courseid);
    if ($fact !== null) {
        return $fact['extra'];
    }

    $result = [
        'ngduration' => '',
        'nglevel' => '',
        'ngeffort' => '',
        'ngcertificate' => '',
        'nglanguage' => '',
        'ngrequirements' => '',
        'ngoutcomes' => '',
        'ngratingdist' => '',
        'ngreviewlist' => '',
    ];
    $handler = \core_course\customfield\course_handler::create();
    foreach ($handler->get_instance_data($courseid, true) as $data) {
        $shortname = $data->get_field()->get('shortname');
        if (array_key_exists($shortname, $result)) {
            $result[$shortname] = trim((string) $data->get_value());
        }
    }
    return $result;
}

/**
 * Turn a learning-objectives field into a title, a lead, and points.
 *
 * Accepts an HTML list, a Markdown list, or one objective per line.
 *
 * @param string $raw
 * @param context $context
 * @return array{title: string, lead: string, points: array}
 */
function theme_nextgen_learning_from_raw(string $raw, context $context): array {
    $raw = trim($raw);
    if ($raw === '') {
        return ['title' => '', 'lead' => '', 'points' => []];
    }
    $format = FORMAT_PLAIN;
    if (preg_match('/<(?:ul|ol|li|p|h[1-6])\b/i', $raw)) {
        $format = FORMAT_HTML;
    } else if (theme_nextgen_looks_like_markdown($raw)) {
        $format = FORMAT_MARKDOWN;
    }
    return theme_nextgen_learning_block($raw, $format, $context);
}

/**
 * Split one summary into its heading, introductory line, and objective points.
 *
 * @param string $raw
 * @param int $format
 * @param context $context
 * @return array{title: string, lead: string, points: array}
 */
function theme_nextgen_learning_block(string $raw, int $format, context $context): array {
    $raw = trim($raw);
    $empty = ['title' => '', 'lead' => '', 'points' => []];
    if ($raw === '') {
        return $empty;
    }

    if ($format === FORMAT_HTML || $format === FORMAT_MOODLE || preg_match('/<(?:ul|ol|li|h[1-6])\b/i', $raw)) {
        $parsed = theme_nextgen_parse_learning_html($raw);
        if ($parsed['points']) {
            return $parsed;
        }
    }

    $html = format_text($raw, $format, ['context' => $context, 'filter' => false]);
    $parsed = theme_nextgen_parse_learning_html($html);
    if ($parsed['points']) {
        return $parsed;
    }

    $plain = trim(html_to_text($html, 0, false));
    if ($format === FORMAT_MARKDOWN || theme_nextgen_looks_like_markdown($raw) || theme_nextgen_looks_like_markdown($plain)) {
        $source = theme_nextgen_looks_like_markdown($raw) ? $raw : $plain;
        $markdown = format_text($source, FORMAT_MARKDOWN, ['context' => $context, 'filter' => false]);
        $parsed = theme_nextgen_parse_learning_html($markdown);
        if ($parsed['points']) {
            return $parsed;
        }
    }

    $fallback = $plain !== '' ? $plain : trim(strip_tags($raw));
    return theme_nextgen_plain_learning_lines($fallback);
}

/**
 * Keep one shared heading and one shared introduction, then list every point.
 *
 * @param array $blocks
 * @return array{title: string, lead: string, points: array}
 */
function theme_nextgen_merge_learning_blocks(array $blocks): array {
    $titles = [];
    $leads = [];
    $points = [];
    $seen = [];
    foreach ($blocks as $block) {
        if ($block['title'] !== '') {
            $titles[$block['title']] = true;
        }
        if ($block['lead'] !== '') {
            $leads[$block['lead']] = true;
        }
        foreach ($block['points'] as $point) {
            $key = core_text::strtolower($point['text']);
            if ($key === '' || isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $points[] = $point;
        }
    }
    return [
        'title' => count($titles) === 1 ? (string) array_key_first($titles) : '',
        'lead' => count($leads) === 1 ? (string) array_key_first($leads) : '',
        'points' => $points,
    ];
}

/**
 * Read a heading, the first short introduction, and the list items from HTML.
 *
 * @param string $html
 * @return array{title: string, lead: string, points: array}
 */
function theme_nextgen_parse_learning_html(string $html): array {
    $result = ['title' => '', 'lead' => '', 'points' => []];
    if (trim(strip_tags($html)) === '') {
        return $result;
    }

    $previous = libxml_use_internal_errors(true);
    $document = new DOMDocument();
    $loaded = $document->loadHTML('<?xml encoding="UTF-8"><div>' . $html . '</div>');
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    if (!$loaded) {
        return $result;
    }

    $xpath = new DOMXPath($document);
    $heading = $xpath->query('//h1|//h2|//h3|//h4|//h5|//h6')->item(0);
    if ($heading) {
        $result['title'] = theme_nextgen_plain_markup(theme_nextgen_collapse_text($heading->textContent));
    }

    $points = [];
    foreach ($xpath->query('//li') as $item) {
        $text = theme_nextgen_plain_markup(theme_nextgen_collapse_text($item->textContent));
        if ($text === '') {
            continue;
        }
        if ($result['title'] !== '' && core_text::strtolower($text) === core_text::strtolower($result['title'])) {
            continue;
        }
        $points[] = ['text' => $text];
    }
    if (!$points) {
        return ['title' => '', 'lead' => '', 'points' => []];
    }

    $paragraph = $xpath->query('//p[not(ancestor::li)]')->item(0);
    if ($paragraph) {
        $lead = theme_nextgen_plain_markup(theme_nextgen_collapse_text($paragraph->textContent));
        if ($lead !== '' && core_text::strlen($lead) <= 180 && theme_nextgen_is_objective_lead($lead)) {
            $result['lead'] = $lead;
        }
    }
    $result['points'] = $points;
    return $result;
}

/**
 * Split plain lines, lifting a "Learning objectives" heading out of the points.
 *
 * @param string $raw
 * @return array{title: string, lead: string, points: array}
 */
function theme_nextgen_plain_learning_lines(string $raw): array {
    $result = ['title' => '', 'lead' => '', 'points' => []];
    $lines = [];
    foreach (preg_split("/\R/u", $raw) ?: [] as $line) {
        $line = theme_nextgen_plain_markup(trim(strip_tags($line)));
        if ($line !== '') {
            $lines[] = $line;
        }
    }
    if (!$lines) {
        return $result;
    }

    $heading = theme_nextgen_objective_heading($lines[0]);
    if ($heading !== '' && count($lines) > 1) {
        $result['title'] = $heading;
        $lines = array_slice($lines, 1);
    }
    if (count($lines) > 1 && theme_nextgen_is_objective_lead($lines[0])) {
        $result['lead'] = $lines[0];
        $lines = array_slice($lines, 1);
    }
    foreach ($lines as $line) {
        if (count($lines) === 1 && core_text::strlen($line) > 280) {
            $line = shorten_text($line, 280);
        }
        $result['points'][] = ['text' => $line];
    }
    return $result;
}

/**
 * Whether the text still contains Markdown headings, emphasis, or list markers.
 *
 * @param string $text
 * @return bool
 */
function theme_nextgen_looks_like_markdown(string $text): bool {
    return (bool) preg_match(
        '/(^|\R)\s*(?:#{1,6}\s+|\*\*|__|(?:[*+\-]|\d+\.)\s+\S)/u',
        $text
    );
}

/**
 * Return the line when it is a learning-objectives heading.
 *
 * @param string $line
 * @return string
 */
function theme_nextgen_objective_heading(string $line): string {
    $line = trim(preg_replace('/^#{1,6}\s+/u', '', $line) ?? $line);
    $line = trim($line, " \t:-");
    if ($line === '' || core_text::strlen($line) > 80) {
        return '';
    }
    if (!preg_match('/learning\s+objectives?/iu', $line)) {
        return '';
    }
    return $line;
}

/**
 * Whether a line introduces the points that follow it.
 *
 * @param string $line
 * @return bool
 */
function theme_nextgen_is_objective_lead(string $line): bool {
    if ($line === '' || core_text::strlen($line) > 180) {
        return false;
    }
    if (preg_match('/:\s*$/u', $line)) {
        return true;
    }
    return (bool) preg_match('/^by the end\b/iu', $line);
}

/**
 * Collapse whitespace.
 *
 * @param string $text
 * @return string
 */
function theme_nextgen_collapse_text(string $text): string {
    return trim(preg_replace('/\s+/u', ' ', $text) ?? $text);
}

/**
 * Turn leftover Markdown markers into readable text.
 *
 * @param string $text
 * @return string
 */
function theme_nextgen_plain_markup(string $text): string {
    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $text = preg_replace('/\[([^\]]+)\]\([^)]+\)/u', '$1', $text) ?? $text;
    $text = preg_replace('/`([^`]+)`/u', '$1', $text) ?? $text;
    $text = preg_replace('/(\*\*|__)(.+?)\1/u', '$2', $text) ?? $text;
    $text = preg_replace('/(?<!\w)[*_]([^*_\n]+)[*_](?!\w)/u', '$1', $text) ?? $text;
    $text = preg_replace('/^\s*(?:[*+\-]|\d+\.|[•·])\s+/u', '', $text) ?? $text;
    return theme_nextgen_collapse_text($text);
}

/**
 * One trimmed line per array item.
 *
 * @param string $raw
 * @return array
 */
function theme_nextgen_text_lines(string $raw): array {
    $lines = [];
    foreach (preg_split("/\R/u", $raw) ?: [] as $line) {
        $line = trim(strip_tags($line));
        if ($line !== '') {
            $lines[] = ['text' => $line];
        }
    }
    return $lines;
}

/**
 * Offer English and French through Moodle's language menu.
 *
 * The French pack is installed with tool_langimport, the same installer as
 * Site administration, Language, Language packs. The menu itself is core:
 * guests see it in the header, and signed-in users see it in the user menu.
 * Choosing a language uses Moodle's lang parameter and is remembered for the session.
 */
function theme_nextgen_enable_languages(): void {
    if (during_initial_install()) {
        return;
    }

    $installed = array_keys(get_string_manager()->get_list_of_translations(true));
    if (!in_array('fr', $installed, true)) {
        $controller = new \tool_langimport\controller();
        $controller->install_languagepacks('fr');
        get_string_manager()->reset_caches();
    }

    set_config('langmenu', 1);
    set_config('langlist', 'en,fr');
    get_string_manager(true);
}

