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
    return '$primary: ' . $primary . ";\n" . '$success: #1A6C25;' . "\n";
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
    $accent = theme_nextgen_color(theme_nextgen_setting($theme, 'accentcolor'), '#C4842A');

    $css = ":root {\n";
    $css .= "--ng-brand: #1A6C25;\n";
    $css .= "--ng-brand-soft: #E8F3EA;\n";
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
    $css .= "--ng-background: #F4F6F7;\n";
    $css .= "--ng-surface: #FFFFFF;\n";
    $css .= "--ng-muted: #EEF2F4;\n";
    $css .= "--ng-border: #E1E6EA;\n";
    $css .= "--ng-text: #1A2330;\n";
    $css .= "--ng-text-muted: #5C6770;\n";
    $css .= "--ng-success: #1A6C25;\n";
    $css .= "--ng-warning: #8A5410;\n";
    $css .= "--ng-error: #9F2B22;\n";
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
        $css .= 'body.nextgen.pagelayout-login #page .login-layout-left {';
        $css .= "background-image: url('" . $loginbackground . "'); background-size: cover; background-position: center;";
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
 * @return array<int, array{label: string, url: string, external: bool}>
 */
function theme_nextgen_parse_links(string $raw): array {
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
            'label' => $label,
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
    foreach (theme_nextgen_parse_links($raw) as $link) {
        $slug = preg_replace('/[^a-z0-9]+/', '', strtolower($link['label']));
        $items[] = [
            'label' => $link['label'],
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
    global $SITE;

    $about = theme_nextgen_setting($theme, 'footerabout');
    if ($about === '') {
        $about = get_string('footeraboutdefault', 'theme_nextgen');
    }

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

    $social = [];
    $networks = [
        'facebookurl' => get_string('facebook', 'theme_nextgen'),
        'instagramurl' => get_string('instagram', 'theme_nextgen'),
        'linkedinurl' => get_string('linkedin', 'theme_nextgen'),
        'youtubeurl' => get_string('youtube', 'theme_nextgen'),
        'xurl' => get_string('x', 'theme_nextgen'),
    ];
    foreach ($networks as $setting => $label) {
        $safe = theme_nextgen_safe_url(theme_nextgen_setting($theme, $setting));
        if ($safe === null) {
            continue;
        }
        $social[] = [
            'label' => $label,
            'url' => $safe,
            'isfacebook' => $setting === 'facebookurl',
            'isinstagram' => $setting === 'instagramurl',
            'islinkedin' => $setting === 'linkedinurl',
            'isyoutube' => $setting === 'youtubeurl',
            'isx' => $setting === 'xurl',
        ];
    }

    $email = trim(theme_nextgen_setting($theme, 'contactemail'));
    if ($email !== '' && !validate_email($email)) {
        $email = '';
    }
    $phone = trim(theme_nextgen_setting($theme, 'contactphone'));
    $dial = preg_replace('/[^0-9+]/', '', $phone);

    $copyright = theme_nextgen_setting($theme, 'copyright');
    if ($copyright === '') {
        $copyright = get_string('copyrightdefault', 'theme_nextgen', (object) [
            'year' => date('Y'),
            'name' => format_string($SITE->fullname, true, [
                'context' => context_system::instance(),
                'escape' => false,
            ]),
        ]);
    }

    $privacy = theme_nextgen_safe_url(theme_nextgen_setting($theme, 'privacyurl'));
    $terms = theme_nextgen_safe_url(theme_nextgen_setting($theme, 'termsurl'));

    return [
        'sitename' => format_string($SITE->fullname, true, ['context' => context_system::instance()]),
        'about' => format_text($about, FORMAT_HTML, ['context' => context_system::instance()]),
        'columns' => $columns,
        'hassocial' => !empty($social),
        'social' => $social,
        'hascontact' => ($email !== '' || $phone !== ''),
        'contactemail' => $email,
        'contactphone' => $phone,
        'contactphonehref' => $dial,
        'copyright' => $copyright,
        'privacyurl' => $privacy,
        'termsurl' => $terms,
    ];
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
    global $DB;

    $theme = theme_config::load('nextgen');
    $courses = theme_nextgen_featured_courses();
    $categories = theme_nextgen_landing_categories();
    $instructors = theme_nextgen_landing_instructors();

    $coursecount = core_course_category::top()->get_courses_count(['recursive' => true]);
    $categorycount = $DB->count_records('course_categories', ['visible' => 1]);

    $primary = theme_nextgen_parse_links(theme_nextgen_setting(
        $theme,
        'heroprimary',
        get_string('heroprimarydefault', 'theme_nextgen')
    ));
    $secondary = theme_nextgen_parse_links(theme_nextgen_setting(
        $theme,
        'herosecondary',
        get_string('herosecondarydefault', 'theme_nextgen')
    ));

    $herotitle = theme_nextgen_setting($theme, 'herotitle', get_string('herotitledefault', 'theme_nextgen'));
    $herotext = theme_nextgen_setting($theme, 'herotext', get_string('herotextdefault', 'theme_nextgen'));

    $tones = ['primary', 'clay', 'ink', 'gold'];
    $reasons = [];
    foreach ([1, 2, 3, 4] as $offset => $index) {
        $reasons[] = [
            'title' => get_string('reason' . $index . 'title', 'theme_nextgen'),
            'text' => get_string('reason' . $index . 'text', 'theme_nextgen'),
            'tone' => $tones[$offset],
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

    $quotes = theme_nextgen_testimonials(theme_nextgen_setting(
        $theme,
        'testimonials',
        get_string('testimonialsdefault', 'theme_nextgen')
    ));

    return [
        'herotitle' => $herotitle,
        'herotext' => $herotext,
        'hasprimary' => !empty($primary),
        'primary' => $primary[0] ?? null,
        'hassecondary' => !empty($secondary),
        'secondary' => $secondary[0] ?? null,
        'coursecount' => $coursecount,
        'categorycount' => $categorycount,
        'hascourses' => !empty($courses),
        'courses' => $courses,
        'hascategories' => !empty($categories),
        'categories' => $categories,
        'hasinstructors' => !empty($instructors),
        'instructors' => $instructors,
        'reasons' => $reasons,
        'steps' => $steps,
        'benefits' => $benefits,
        'hasquotes' => !empty($quotes),
        'quotes' => $quotes,
        'ctaimage' => $theme->image_url('hero-home', 'theme')->out(false),
        'coursesurl' => (new moodle_url('/course/index.php'))->out(false),
        'loginurl' => (new moodle_url('/login/index.php'))->out(false),
        'isloggedin' => isloggedin() && !isguestuser(),
    ];
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
    foreach ($courses as $course) {
        $cards[] = theme_nextgen_course_card_data($course);
    }
    return $cards;
}

/**
 * Card fields for one course list element.
 *
 * @param core_course_list_element $course
 * @return array
 */
function theme_nextgen_course_card_data(core_course_list_element $course): array {
    global $CFG, $DB, $OUTPUT;

    $context = context_course::instance($course->id);
    $image = '';
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
    if ($image === '') {
        $image = $OUTPUT->get_generated_image_for_id($course->id);
    }

    $teachers = theme_nextgen_course_teachers($context);
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

    $ratingtext = $hasrating ? number_format($ratingvalue, 1) : '';
    $ratinglabel = '';
    if ($hasrating) {
        $a = (object) ['rating' => $ratingtext, 'reviews' => $reviews];
        $ratinglabel = $reviews !== null
            ? get_string('ratinglabelreviews', 'theme_nextgen', $a)
            : get_string('ratinglabel', 'theme_nextgen', $a);
    }

    $studentrole = $DB->get_field('role', 'id', ['shortname' => 'student']);
    $enrolments = $studentrole ? count_role_users($studentrole, $context) : count_enrolled_users($context);
    $timestamp = !empty($course->startdate) ? (int) $course->startdate : (int) $course->timecreated;
    $date = preg_replace('/^0/', '', userdate($timestamp, '%d %B %Y'));

    $price = $commercial['price'];
    if ($price === '' && !empty($CFG->enrol_plugins_enabled) && str_contains($CFG->enrol_plugins_enabled, 'fee')) {
        $price = theme_nextgen_enrol_fee_price($course->id);
    }

    return [
        'fullname' => format_string($course->fullname, true, [
            'context' => $context,
            'escape' => false,
        ]),
        'image' => $image,
        'url' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
        'bestseller' => $commercial['bestseller'],
        'hasteachers' => $teacherlabel !== '',
        'teachers' => $teacherlabel,
        'hasrating' => $hasrating,
        'rating' => $ratingtext,
        'ratinglabel' => $ratinglabel,
        'stars' => $stars,
        'hasreviews' => $reviews !== null,
        'reviews' => $reviews ?? 0,
        'hasprice' => $price !== '',
        'price' => $price,
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
            $result['price'] = theme_nextgen_format_price($value);
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
 * @param string $raw
 * @return string
 */
function theme_nextgen_format_price(string $raw): string {
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    if (preg_match('/[^0-9.,\s]/', $raw)) {
        return $raw;
    }
    $number = (float) str_replace(',', '', $raw);
    return '$' . number_format($number, 2);
}

/**
 * Price from an enabled fee enrolment instance, when the course has no price field.
 *
 * @param int $courseid
 * @return string
 */
function theme_nextgen_enrol_fee_price(int $courseid): string {
    $symbols = [
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'NGN' => '₦',
        'GHS' => 'GH₵',
        'ZAR' => 'R',
        'CAD' => 'CA$',
        'AUD' => 'A$',
    ];
    foreach (enrol_get_instances($courseid, true) as $instance) {
        if ($instance->enrol !== 'fee' || $instance->cost === null || $instance->cost === '') {
            continue;
        }
        $symbol = $symbols[$instance->currency] ?? ($instance->currency . ' ');
        return $symbol . number_format((float) $instance->cost, 2);
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
 * Context for the course catalogue intro.
 *
 * @return array
 */
function theme_nextgen_catalogue_context(): array {
    $count = core_course_category::top()->get_courses_count(['recursive' => true]);
    $categoryid = optional_param('categoryid', 0, PARAM_INT);
    $categories = [];
    foreach (theme_nextgen_landing_categories() as $category) {
        $category['active'] = ((int) $category['id'] === $categoryid);
        $categories[] = $category;
    }
    return [
        'hascourses' => $count > 0,
        'hascategories' => !empty($categories),
        'categories' => $categories,
        'allcoursesurl' => (new moodle_url('/course/index.php'))->out(false),
        'allactive' => $categoryid === 0,
    ];
}

/**
 * Visible top-level categories.
 *
 * @return array
 */
function theme_nextgen_landing_categories(): array {
    global $OUTPUT;

    $photos = ['category-architecture', 'category-business', 'category-data', 'category-social'];
    $items = [];
    foreach (core_course_category::top()->get_children() as $category) {
        $items[] = [
            'id' => $category->id,
            'name' => $category->get_formatted_name(),
            'count' => $category->get_courses_count(),
            'image' => $OUTPUT->image_url($photos[count($items) % 4], 'theme')->out(false),
            'url' => (new moodle_url('/course/index.php', ['categoryid' => $category->id]))->out(false),
        ];
        if (count($items) >= 6) {
            break;
        }
    }
    return $items;
}

/**
 * Teachers shown as course contacts, up to four unique people.
 *
 * @return array
 */
function theme_nextgen_landing_instructors(): array {
    global $OUTPUT;

    $courses = core_course_category::top()->get_courses([
        'recursive' => true,
        'coursecontacts' => true,
        'limit' => 12,
        'sort' => ['sortorder' => 1],
    ]);

    $people = [];
    $seen = [];
    foreach ($courses as $course) {
        foreach ($course->get_course_contacts() as $contact) {
            $userid = (int) $contact['user']->id;
            if (isset($seen[$userid])) {
                continue;
            }
            $seen[$userid] = true;
            $user = core_user::get_user($userid);
            if (!$user || !empty($user->deleted)) {
                continue;
            }
            $people[] = [
                'name' => fullname($user),
                'role' => $contact['rolename'],
                'picture' => $OUTPUT->user_picture($user, [
                    'size' => 80,
                    'link' => false,
                    'class' => 'ng-instructor-photo',
                ]),
                'url' => (new moodle_url('/user/profile.php', ['id' => $user->id]))->out(false),
            ];
            if (count($people) >= 4) {
                return $people;
            }
        }
    }
    return $people;
}

/**
 * Whether this request should use the course sales layout.
 *
 * Activity pages are unchanged. The course editor stays available under the sales layout.
 *
 * @param moodle_page $page
 * @return bool
 */
function theme_nextgen_is_course_detail_page(moodle_page $page): bool {
    $course = $page->course;
    if (empty($course->id) || (int) $course->id === SITEID) {
        return false;
    }
    if ($page->pagelayout === 'course') {
        return true;
    }
    return (string) $page->pagetype === 'enrol-index';
}

/**
 * Template context for the course sales layout, or null on other pages.
 *
 * @return array|null
 */
function theme_nextgen_course_detail_context(): ?array {
    global $OUTPUT, $PAGE, $USER;

    if (!theme_nextgen_is_course_detail_page($PAGE)) {
        return null;
    }

    $course = new core_course_list_element($PAGE->course);
    $context = context_course::instance($course->id);
    $card = theme_nextgen_course_card_data($course);
    $extra = theme_nextgen_course_extra_fields($course->id);

    $summary = format_text($course->summary, $course->summaryformat, ['context' => $context]);
    $plain = trim(html_to_text($summary, 0, false));
    $excerpt = $plain === '' ? '' : shorten_text($plain, 220);

    $instructors = [];
    foreach ($course->get_course_contacts() as $contact) {
        $user = core_user::get_user($contact['user']->id);
        if (!$user || !empty($user->deleted)) {
            continue;
        }
        $instructors[] = [
            'name' => $contact['username'],
            'role' => $contact['rolename'],
            'picture' => $OUTPUT->user_picture($user, [
                'size' => 80,
                'link' => false,
                'class' => 'ng-course-teacher-photo',
            ]),
            'url' => (new moodle_url('/user/profile.php', ['id' => $user->id]))->out(false),
        ];
    }
    $teacher = $instructors[0] ?? null;

    $outcomes = theme_nextgen_text_lines($extra['ngoutcomes']);
    $subjects = [];
    $tones = ['primary', 'clay', 'ink', 'gold'];
    $tags = \core_tag_tag::get_item_tags_array('core', 'course', $course->id);
    foreach (array_values($tags) as $index => $tag) {
        $subjects[] = [
            'name' => $tag,
            'tone' => $tones[$index % 4],
        ];
    }

    $sectionnames = [];
    $summaryoutcomes = [];
    $activitycount = 0;
    $certificates = [];
    $modinfo = get_fast_modinfo($course->id);
    foreach ($modinfo->get_cms() as $cm) {
        if (!$cm->uservisible || !empty($cm->deletioninprogress)) {
            continue;
        }
        $activitycount++;
        if (in_array($cm->modname, ['customcert', 'certificate'], true)) {
            $certificates[] = [
                'name' => format_string($cm->name, true, ['context' => $context]),
                'url' => $cm->url ? $cm->url->out(false) : '',
            ];
        }
    }
    foreach ($modinfo->get_section_info_all() as $section) {
        if ((int) $section->sectionnum === 0 || empty($section->uservisible)) {
            continue;
        }
        $customname = trim((string) $section->name);
        if ($customname === '') {
            continue;
        }
        $name = format_string($customname, true, ['context' => $context]);
        $sectionnames[] = $name;
        $learned = trim(html_to_text(format_text(
            $section->summary,
            $section->summaryformat,
            ['context' => $context]
        ), 0, false));
        if ($learned !== '') {
            $summaryoutcomes[] = ['text' => shorten_text($learned, 180)];
        }
    }
    if (!$outcomes) {
        $outcomes = $summaryoutcomes;
    }
    if (!$outcomes) {
        foreach ($sectionnames as $name) {
            $outcomes[] = ['text' => $name];
        }
    } else if (!$subjects) {
        foreach ($sectionnames as $index => $name) {
            $subjects[] = [
                'name' => $name,
                'tone' => $tones[$index % 4],
            ];
        }
    }

    $language = $extra['nglanguage'];
    if ($language === '' && !empty($course->lang)) {
        $translations = get_string_manager()->get_list_of_translations();
        $language = $translations[$course->lang] ?? $course->lang;
    }

    $facts = [];
    $factmap = [
        'ngduration' => ['courselength', $extra['ngduration']],
        'nglevel' => ['courselevel', $extra['nglevel']],
        'ngeffort' => ['courseeffort', $extra['ngeffort']],
        'ngcertificate' => ['coursecertificate', $extra['ngcertificate']],
    ];
    foreach ($factmap as $spec) {
        if ($spec[1] !== '') {
            $facts[] = [
                'label' => get_string($spec[0], 'theme_nextgen'),
                'value' => $spec[1],
            ];
        }
    }
    if ($language !== '') {
        $facts[] = [
            'label' => get_string('courselanguage', 'theme_nextgen'),
            'value' => $language,
        ];
    }
    if (!empty($course->startdate)) {
        $facts[] = [
            'label' => get_string('coursestarts', 'theme_nextgen'),
            'value' => userdate($course->startdate, get_string('strftimedatefullshort')),
        ];
    }

    $includes = [];
    if ($activitycount > 0) {
        $includes[] = ['label' => get_string('courseactivities', 'theme_nextgen', $activitycount)];
    }
    if ($sectionnames) {
        $includes[] = ['label' => get_string('coursesections', 'theme_nextgen', count($sectionnames))];
    }
    if ($language !== '') {
        $includes[] = ['label' => $language];
    }
    if (!empty($course->enablecompletion)) {
        $includes[] = ['label' => get_string('completion', 'completion')];
    }

    $enrolled = is_enrolled($context, $USER, '', true);
    if ($enrolled) {
        $enrolurl = (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false) . '#ng-course-content';
        $enrollabel = get_string('coursecontinue', 'theme_nextgen');
    } else if ((string) $PAGE->pagetype === 'enrol-index') {
        $enrolurl = '#ng-enrolment';
        $enrollabel = get_string('courseenrol', 'theme_nextgen');
    } else {
        $enrolurl = (new moodle_url('/enrol/index.php', ['id' => $course->id]))->out(false);
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
    if (!empty($course->category)) {
        $category = core_course_category::get((int) $course->category, IGNORE_MISSING, true);
        if ($category) {
            $breadcrumbs[] = [
                'label' => $category->get_formatted_name(),
                'url' => (new moodle_url('/course/index.php', ['categoryid' => $category->id]))->out(false),
            ];
        }
    }
    $breadcrumbs[] = [
        'label' => $card['fullname'],
        'url' => '',
        'current' => true,
    ];

    $price = $card['price'];

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
        'hascertificate' => $extra['ngcertificate'] !== '' || !empty($certificates),
        'certificatenote' => $extra['ngcertificate'],
        'certificates' => $certificates,
        'hasreviews' => !empty($card['hasreviews']),
        'reviewcount' => $card['reviews'] ?? 0,
        'hasrating' => $card['hasrating'],
        'rating' => $card['rating'],
        'ratinglabel' => $card['ratinglabel'],
        'stars' => $card['stars'],
        'hasprice' => $price !== '',
        'price' => $price,
        'hasfacts' => !empty($facts),
        'facts' => $facts,
        'hasincludes' => !empty($includes),
        'includes' => $includes,
        'hasoutcomes' => !empty($outcomes),
        'outcomes' => $outcomes,
        'hassubjects' => !empty($subjects),
        'subjects' => $subjects,
        'hasrequirements' => $extra['ngrequirements'] !== '',
        'requirements' => theme_nextgen_text_lines($extra['ngrequirements']),
        'breadcrumbs' => $breadcrumbs,
        'enrolurl' => $enrolurl,
        'enrollabel' => $enrollabel,
        'showactivities' => $PAGE->pagelayout === 'course',
    ];
}

/**
 * Optional course custom fields. Missing fields stay empty.
 *
 * @param int $courseid
 * @return array
 */
function theme_nextgen_course_extra_fields(int $courseid): array {
    $result = [
        'ngduration' => '',
        'nglevel' => '',
        'ngeffort' => '',
        'ngcertificate' => '',
        'nglanguage' => '',
        'ngrequirements' => '',
        'ngoutcomes' => '',
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
 * Parse marketing quotes written as Quote|Attribution.
 *
 * @param string $raw
 * @return array
 */
function theme_nextgen_testimonials(string $raw): array {
    $quotes = [];
    foreach (preg_split("/\r\n|\n|\r/", $raw) as $line) {
        $line = trim($line);
        if ($line === '' || !str_contains($line, '|')) {
            continue;
        }
        [$quote, $attribution] = array_map('trim', explode('|', $line, 2));
        if ($quote === '' || $attribution === '') {
            continue;
        }
        $quotes[] = [
            'quote' => $quote,
            'attribution' => $attribution,
        ];
        if (count($quotes) >= 3) {
            break;
        }
    }
    return $quotes;
}
