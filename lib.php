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
    if ((string) $page->pagetype === 'theme-nextgen-course') {
        $page->add_body_class('nextgen-sales');
    }
    $page->requires->js_call_amd('theme_nextgen/loadline', 'init');
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
    if ($privacy === null) {
        $privacy = \theme_nextgen\local\public_page::url('privacy')->out(false);
    }
    $terms = theme_nextgen_safe_url(theme_nextgen_setting($theme, 'termsurl'));
    if ($terms === null) {
        $terms = \theme_nextgen\local\public_page::url('terms')->out(false);
    }
    $columns = theme_nextgen_with_faq_link($columns);

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
    if (get_config('theme_nextgen', 'publicpathrev') === '2026100114') {
        return;
    }

    $ready = theme_nextgen_ensure_public_scripts();
    theme_nextgen_rewrite_public_urls();
    if ($ready) {
        set_config('publicpathrev', '2026100114', 'theme_nextgen');
    }
}

/**
 * Write the site-root scripts for About, Contact, Privacy, Terms, and FAQ.
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
 * Replace theme-directory addresses already saved in theme settings.
 */
function theme_nextgen_rewrite_public_urls(): void {
    $map = [
        '/theme/nextgen/about.php' => '/about.php',
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
        'instructorcount' => count($instructors),
        'facultypoints' => $facultypoints,
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
    global $CFG, $DB, $OUTPUT, $USER;

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

    $price = '';
    if (!empty($CFG->enrol_plugins_enabled) && str_contains($CFG->enrol_plugins_enabled, 'fee')) {
        $price = theme_nextgen_enrol_fee_price((int) $course->id);
    }
    if ($price === '') {
        $price = $commercial['price'];
    }

    $excerpt = '';
    if ($course->has_summary()) {
        $formatted = format_text($course->summary, (int) $course->summaryformat, [
            'context' => $context,
            'filter' => false,
        ]);
        $plain = trim((string) preg_replace('/\s+/', ' ', html_to_text($formatted, 0, false)));
        if ($plain !== '') {
            $excerpt = shorten_text($plain, 160, false);
        }
    }

    $enrolled = isloggedin() && !isguestuser() && is_enrolled($context, $USER, '', true);
    if ($enrolled) {
        $entry = new moodle_url('/course/view.php', ['id' => $course->id]);
        $actionurl = $entry->out(false) . '#ng-course-content';
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
        'hasprice' => $price !== '',
        'price' => $price,
        'actionurl' => $actionurl,
        'actionlabel' => $actionlabel,
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
    return number_format($number, 2);
}

/**
 * Localised cost of the first enabled fee enrolment, including XAF.
 *
 * Uses Moodle's payment formatter so the currency's own fraction digits are kept.
 * An empty string means this course has no fee to display.
 *
 * @param int $courseid
 * @return string
 */
function theme_nextgen_enrol_fee_price(int $courseid): string {
    foreach (enrol_get_instances($courseid, true) as $instance) {
        if ($instance->enrol !== 'fee' || $instance->cost === null || $instance->cost === '') {
            continue;
        }
        $currency = (string) $instance->currency;
        if ($currency === '') {
            continue;
        }
        return \core_payment\helper::get_cost_as_string((float) $instance->cost, $currency);
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
 * Context for the course catalogue intro.
 *
 * @return array
 */
function theme_nextgen_catalogue_context(): array {
    $categoryid = optional_param('categoryid', 0, PARAM_INT);
    $filters = theme_nextgen_catalogue_request_filters();
    $kept = [];
    if ($filters['search'] !== '') {
        $kept['search'] = $filters['search'];
    }
    if ($filters['level'] !== '') {
        $kept['level'] = $filters['level'];
    }
    if ($filters['language'] !== '') {
        $kept['language'] = $filters['language'];
    }
    if ($filters['certificate'] !== '') {
        $kept['certificate'] = $filters['certificate'];
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

    $loaded = theme_nextgen_catalogue_courses($categoryid, $kept === []);
    $levels = theme_nextgen_catalogue_filter_options($loaded, 'filterlevel', $filters['level']);
    $languages = theme_nextgen_catalogue_filter_options($loaded, 'filterlanguage', $filters['language']);
    $certificates = theme_nextgen_catalogue_filter_options($loaded, 'filtercertificate', $filters['certificate']);
    $courses = theme_nextgen_catalogue_apply_filters($loaded, $filters);

    if ($courses) {
        $empty = '';
    } else if ($categoryid > 0 && !$loaded) {
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
        'levels' => $levels,
        'languages' => $languages,
        'certificates' => $certificates,
        'advancedopen' => ($filters['level'] !== '' || $filters['language'] !== '' || $filters['certificate'] !== ''),
        'clearurl' => (new moodle_url('/course/index.php', $clear))->out(false),
        'emptytext' => $empty,
    ];
}

/**
 * Search and advanced-filter values from the catalogue request.
 *
 * @return array{search: string, level: string, language: string, certificate: string}
 */
function theme_nextgen_catalogue_request_filters(): array {
    return [
        'search' => trim(optional_param('search', '', PARAM_TEXT)),
        'level' => trim(optional_param('level', '', PARAM_TEXT)),
        'language' => trim(optional_param('language', '', PARAM_TEXT)),
        'certificate' => trim(optional_param('certificate', '', PARAM_TEXT)),
    ];
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
        $found[$value] = $value;
    }
    natcasesort($found);
    $options = [];
    foreach ($found as $value) {
        $options[] = [
            'value' => $value,
            'label' => $value,
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
        unset($course['filtersearch'], $course['filterlevel'], $course['filterlanguage'], $course['filtercertificate']);
        $kept[] = $course;
    }
    return $kept;
}

/**
 * Courses for the catalogue. With no category and no filters, the order is shuffled.
 *
 * @param int $categoryid
 * @param bool $shuffle
 * @return array
 */
function theme_nextgen_catalogue_courses(int $categoryid, bool $shuffle = true): array {
    if ($categoryid > 0) {
        $source = core_course_category::get($categoryid, IGNORE_MISSING, true);
        if (!$source || !$source->is_uservisible()) {
            return [];
        }
    } else {
        $source = core_course_category::top();
    }

    $courses = array_values($source->get_courses([
        'recursive' => true,
        'summary' => true,
    ]));
    if ($categoryid === 0 && $shuffle) {
        shuffle($courses);
    }

    $cards = [];
    foreach ($courses as $course) {
        if ((int) $course->id === SITEID) {
            continue;
        }
        $card = theme_nextgen_course_card_data($course);
        $extra = theme_nextgen_course_extra_fields((int) $course->id);
        $language = $extra['nglanguage'];
        if ($language === '' && !empty($course->lang)) {
            $language = (string) $course->lang;
        }
        $card['filterlevel'] = $extra['nglevel'];
        $card['filterlanguage'] = $language;
        $card['filtercertificate'] = $extra['ngcertificate'];
        $plain = trim(html_entity_decode(strip_tags(
            $course->fullname . ' ' . $course->summary
        ), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $card['filtersearch'] = \core_text::strtolower($plain);
        $cards[] = $card;
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
 * Visible top-level categories.
 *
 * @param int $limit Maximum categories to return. Zero returns every visible category.
 * @return array
 */
function theme_nextgen_landing_categories(int $limit = 6): array {
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
        if ($limit > 0 && count($items) >= $limit) {
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
                'picture' => theme_nextgen_instructor_picture($user),
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
 * Portrait for a teacher. People without an uploaded photo get the theme default.
 *
 * @param stdClass $user
 * @return string
 */
function theme_nextgen_instructor_picture(stdClass $user): string {
    global $OUTPUT;

    if (!empty($user->picture)) {
        return $OUTPUT->user_picture($user, [
            'size' => 320,
            'link' => false,
            'class' => 'ng-instructor-photo',
        ]);
    }

    return html_writer::empty_tag('img', [
        'src' => $OUTPUT->image_url('faculty-default', 'theme')->out(false),
        'alt' => '',
        'class' => 'ng-instructor-photo',
        'width' => 320,
        'height' => 320,
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
 * Serve the public course page before course/view.php requires a login.
 */
function theme_nextgen_after_config(): void {
    global $CFG;

    if (during_initial_install() || !empty($CFG->upgraderunning)) {
        return;
    }
    if (($CFG->theme ?? '') === 'nextgen') {
        theme_nextgen_publish_public_paths();
    }
    \theme_nextgen\local\course_page::serve_public_course();
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
    $pagetype = (string) $page->pagetype;
    return $pagetype === 'enrol-index' || $pagetype === 'theme-nextgen-course';
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

    $outline = theme_nextgen_course_outline($course, $context);
    $sectionnames = $outline['sectionnames'];
    $summaryoutcomes = $outline['summaryoutcomes'];
    $activitycount = $outline['activities'];
    $certificates = $outline['certificates'];
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
    if ($language === '') {
        $translations = get_string_manager()->get_list_of_translations();
        $current = current_language();
        $language = $translations[$current] ?? $current;
    }
    $language = str_replace("\u{200E}", '', $language);
    $language = trim((string) preg_replace('/\s*\([a-z]{2}(?:_[A-Za-z]{2})?\)\s*$/', '', $language));

    $certificatevalue = $extra['ngcertificate'];
    if ($certificatevalue === '' && $certificates) {
        $certificatevalue = get_string('yes');
    }

    $facts = [];
    $pushfact = static function (array &$facts, string $icon, string $stringkey, string $value): void {
        if ($value === '') {
            return;
        }
        $facts[] = [
            'label' => get_string($stringkey, 'theme_nextgen'),
            'value' => $value,
            'level' => $icon === 'level',
            'length' => $icon === 'length',
            'effort' => $icon === 'effort',
            'certificate' => $icon === 'certificate',
            'language' => $icon === 'language',
            'starts' => $icon === 'starts',
        ];
    };
    $pushfact($facts, 'level', 'courselevel', $extra['nglevel']);
    $pushfact($facts, 'length', 'courselength', $extra['ngduration']);
    $pushfact($facts, 'effort', 'courseeffort', $extra['ngeffort']);
    $pushfact($facts, 'certificate', 'coursecertificate', $certificatevalue);
    $pushfact($facts, 'language', 'courselanguage', $language);
    if (!empty($course->startdate)) {
        $starts = preg_replace('/^0/', '', userdate((int) $course->startdate, '%d %B %Y'));
        $pushfact($facts, 'starts', 'coursestarts', $starts);
    }

    $includes = [];
    if ($activitycount === 1) {
        $includes[] = ['label' => get_string('courseactivity', 'theme_nextgen')];
    } else if ($activitycount > 1) {
        $includes[] = ['label' => get_string('courseactivities', 'theme_nextgen', $activitycount)];
    }
    $sectioncount = count($outline['sections']);
    if ($sectioncount === 1) {
        $includes[] = ['label' => get_string('coursesection', 'theme_nextgen')];
    } else if ($sectioncount > 1) {
        $includes[] = ['label' => get_string('coursesections', 'theme_nextgen', $sectioncount)];
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
    } else if ((string) $PAGE->pagetype === 'theme-nextgen-course') {
        $enrolurl = theme_nextgen_course_enrol_url((int) $course->id)->out(false);
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
    $distribution = theme_nextgen_rating_distribution($extra['ngratingdist']);
    $reviewlist = theme_nextgen_review_lines($extra['ngreviewlist']);

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
        'ratingslabel' => get_string('ratingscount', 'theme_nextgen', (int) ($card['reviews'] ?? 0)),
        'hasrating' => $card['hasrating'],
        'rating' => $card['rating'],
        'ratinglabel' => $card['ratinglabel'],
        'stars' => $card['stars'],
        'hasdistribution' => !empty($distribution),
        'distribution' => $distribution,
        'hasreviewlist' => !empty($reviewlist),
        'reviewlist' => $reviewlist,
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
        'quoteurl' => (new moodle_url('/user/contactsitesupport.php'))->out(false),
        'shareurl' => (new moodle_url('/course/view.php', ['id' => $course->id]))->out(false),
        'showactivities' => $PAGE->pagelayout === 'course',
        'hasoutline' => !empty($outline['sections']),
        'outlineempty' => empty($outline['sections']),
        'outlinecounts' => $outline['counts'],
        'sections' => $outline['sections'],
        'bestseller' => !empty($card['bestseller']),
        'hasstudents' => (int) ($card['enrolments'] ?? 0) > 0,
        'students' => get_string('studentcount', 'theme_nextgen', (int) ($card['enrolments'] ?? 0)),
        'hasupdated' => !empty($card['date']),
        'updated' => get_string('lastupdated', 'theme_nextgen', $card['date'] ?? ''),
        'hasmeta' => !empty($card['bestseller']) || $card['hasrating'] || (int) ($card['enrolments'] ?? 0) > 0 || !empty($card['date']),
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
    $summaryoutcomes = [];
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
            $items[] = [
                'name' => $name,
                'iconurl' => $icon ? $icon->out(false) : '',
                'url' => ($cm->uservisible && $cm->url) ? $cm->url->out(false) : '',
            ];
            if (in_array($cm->modname, ['customcert', 'certificate'], true)) {
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
            $learned = trim(html_to_text(format_text(
                (string) $section->summary,
                (int) $section->summaryformat,
                ['context' => $context]
            ), 0, false));
            if ($learned !== '') {
                $summaryoutcomes[] = ['text' => shorten_text($learned, 180)];
            }
        }
    }

    $counts = get_string('contentcounts', 'theme_nextgen', (object) [
        'sections' => count($sections),
        'activities' => $activities,
        'resources' => $resources,
    ]);

    return [
        'sections' => $sections,
        'sectionnames' => $sectionnames,
        'summaryoutcomes' => $summaryoutcomes,
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
