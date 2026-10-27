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

namespace theme_nextgen\output;

use moodle_url;

/**
 * Renderer overrides for the NextGen LMS theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_renderer extends \theme_boost\output\core_renderer {
    /** @var bool The blog index heading is replaced once per page. */
    private static $blogheadingreplaced = false;

    /**
     * Site logo. The header and login page both use the shipped NextGen leaf.
     *
     * @param int|null $maxwidth
     * @param int $maxheight
     * @return moodle_url|false
     */
    #[\Override]
    public function get_logo_url($maxwidth = null, $maxheight = 200) {
        return $this->nextgen_logo_url();
    }

    /**
     * Compact logo for the header.
     *
     * @param int|null $maxwidth
     * @param int $maxheight
     * @return moodle_url|false
     */
    #[\Override]
    public function get_compact_logo_url($maxwidth = 300, $maxheight = 300) {
        return $this->nextgen_logo_url();
    }

    /**
     * URL of the included leaf mark.
     *
     * @return moodle_url
     */
    protected function nextgen_logo_url(): moodle_url {
        $url = new moodle_url('/theme/nextgen/pix/logo.webp');
        $url->param('rev', theme_get_revision());
        return $url;
    }

    /**
     * Browser icon. Same leaf mark as the header logo.
     *
     * @return moodle_url
     */
    #[\Override]
    public function favicon() {
        return $this->nextgen_logo_url();
    }

    /**
     * Page head without the Moodle app banners.
     *
     * @return string
     */
    #[\Override]
    public function standard_head_html() {
        $html = parent::standard_head_html();
        $html = preg_replace('/<meta\b[^>]*name=["\']apple-itunes-app["\'][^>]*>/i', '', $html);
        $html = preg_replace('/<link\b[^>]*rel=["\']manifest["\'][^>]*mobile\.webmanifest\.php[^>]*>/i', '', $html);
        if (!is_string($html)) {
            return '';
        }
        return $html . $this->seo_head_html();
    }

    /**
     * SEO metadata for public pages and noindex directives for LMS pages.
     *
     * @return string
     */
    private function seo_head_html(): string {
        global $PAGE;

        $pagetype = (string) $PAGE->pagetype;
        $path = $PAGE->url->get_path();
        $canonical = null;
        $description = '';
        $publicpaths = [
            '/about.php',
            '/team.php',
            '/contact.php',
            '/privacy.php',
            '/terms.php',
            '/refund.php',
            '/accessibility.php',
            '/faq.php',
            '/blog/index.php',
        ];
        $ispublic = false;

        if ($PAGE->pagelayout === 'frontpage' && !$PAGE->user_is_editing()) {
            $theme = \theme_config::load('nextgen');
            $description = \theme_nextgen_localised_setting($theme, 'herotext', 'herotextdefault');
            $canonical = new moodle_url('/');
            $ispublic = true;
        } else if (str_starts_with($pagetype, 'course-index')) {
            $categoryid = optional_param('categoryid', 0, PARAM_INT);
            if ($categoryid > 0) {
                $category = \core_course_category::get($categoryid);
                $formatted = format_text($category->description, $category->descriptionformat, [
                    'context' => $category->get_context(),
                    'filter' => false,
                ]);
                $description = theme_nextgen_text_excerpt(html_to_text($formatted, 0, false), 160);
                if ($description === '') {
                    $description = get_string(
                        'seo_category_description',
                        'theme_nextgen',
                        $category->get_formatted_name()
                    );
                }
                $canonical = new moodle_url('/course/index.php', ['categoryid' => $categoryid]);
            } else if (optional_param('browse', '', PARAM_ALPHA) === 'categories') {
                $description = get_string('seo_categories_description', 'theme_nextgen');
                $canonical = new moodle_url('/course/index.php', ['browse' => 'categories']);
            } else {
                $description = get_string('seo_catalogue_description', 'theme_nextgen');
                $canonical = new moodle_url('/course/index.php');
            }
            $description = theme_nextgen_text_excerpt($description, 160);
            $ispublic = true;
        } else if ($pagetype === 'theme-nextgen-course' && !empty($PAGE->course->id)) {
            $course = $PAGE->course;
            $context = \context_course::instance((int) $course->id);
            $formatted = format_text($course->summary, $course->summaryformat, [
                'context' => $context,
                'filter' => false,
            ]);
            $description = theme_nextgen_text_excerpt(html_to_text($formatted, 0, false), 160);
            if ($description === '') {
                $description = get_string('seo_course_description', 'theme_nextgen', $course->fullname);
            }
            $canonical = new moodle_url('/course/view.php', ['id' => (int) $course->id]);
            $ispublic = true;
        } else if (in_array($path, $publicpaths, true)) {
            $ispublic = true;
        }

        if ($PAGE->user_is_editing()) {
            $ispublic = false;
        }
        $html = \html_writer::empty_tag('meta', [
            'name' => 'robots',
            'content' => $ispublic ? 'index, follow' : 'noindex, follow',
        ]);
        if ($description !== '') {
            $html .= \html_writer::empty_tag('meta', [
                'name' => 'description',
                'content' => theme_nextgen_text_excerpt($description, 160),
            ]);
        }
        if ($canonical && $ispublic) {
            $html .= \html_writer::empty_tag('link', [
                'rel' => 'canonical',
                'href' => $canonical->out(false),
            ]);
        }
        return $html;
    }

    /**
     * Footer fragment without the mobile app, device theme, and retention links.
     *
     * @return string
     */
    #[\Override]
    public function standard_footer_html() {
        $html = parent::standard_footer_html();
        $html = preg_replace(
            '/<div>\s*<a\b[^>]*class="[^"]*\bmobilelink\b[^"]*"[^>]*>.*?<\/a>\s*<\/div>/s',
            '',
            $html,
        );
        $html = preg_replace(
            '/<a\b[^>]*(?:download\.moodle\.org\/mobile|\/tool\/mobile\/)[^>]*>.*?<\/a>/s',
            '',
            $html,
        );
        $html = preg_replace('/<div class="tool_dataprivacy">.*?<\/div>/s', '', $html);
        return is_string($html) ? $html : '';
    }

    /**
     * This theme does not offer a switch back to the standard device theme.
     *
     * @return string
     */
    #[\Override]
    protected function theme_switch_links() {
        return '';
    }

    /**
     * Marketing columns for the footer.
     *
     * Called from the footer template so layout files can stay on Boost.
     *
     * @return string
     */
    public function nextgen_footer_columns(): string {
        $context = theme_nextgen_footer_context($this->page->theme);
        $context['logourl'] = $this->nextgen_logo_url()->out(false);
        return $this->render_from_template('theme_nextgen/footer_columns', $context);
    }

    /**
     * Blog index and entry pages use their own heading in place of the core one.
     *
     * @param string $text
     * @param int $level
     * @param string|null $classes
     * @param string|null $id
     * @return string
     */
    #[\Override]
    public function heading($text, $level = 2, $classes = null, $id = null) {
        if ($this->replace_blog_heading($level, $classes, $id)) {
            self::$blogheadingreplaced = true;
            return $this->render_from_template('theme_nextgen/blog_masthead', $this->blog_masthead_context($text));
        }
        return parent::heading($text, $level, $classes, $id);
    }

    /**
     * True for the first plain heading on the blog index.
     *
     * @param int $level
     * @param string|null $classes
     * @param string|null $id
     * @return bool
     */
    private function replace_blog_heading($level, $classes, $id): bool {
        if (self::$blogheadingreplaced || (string) $this->page->pagetype !== 'blog-index') {
            return false;
        }
        if ((int) $level !== 2 || $id !== null) {
            return false;
        }
        return $classes === null || $classes === '';
    }

    /**
     * The unfiltered site blog, rather than one author, course, tag, or search.
     *
     * @return bool
     */
    private function blog_is_site_index(): bool {
        if ((string) $this->page->pagetype !== 'blog-index' || $this->page->url->param('entryid')) {
            return false;
        }
        foreach (['userid', 'courseid', 'modid', 'groupid', 'tagid', 'tag', 'search'] as $name) {
            if ($this->page->url->param($name)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Banner and breadcrumb for the public blog index and a single post.
     *
     * The banner keeps the site blog name on a post. The trail ends on the
     * article title.
     *
     * @param string $text Heading Moodle was about to print
     * @return array
     */
    private function blog_masthead_context(string $text): array {
        global $DB;

        $entryid = (int) $this->page->url->param('entryid');
        $blogurl = (new moodle_url('/blog/index.php'))->out(false);
        $title = $entryid ? get_string('siteblogheading', 'blog') : $text;
        $crumbs = [
            [
                'label' => get_string('home'),
                'url' => (new moodle_url('/'))->out(false),
                'home' => true,
            ],
        ];
        if ($entryid) {
            $crumbs[] = [
                'label' => $title,
                'url' => $blogurl,
                'home' => false,
            ];
            $subject = $DB->get_field('post', 'subject', ['id' => $entryid]);
            $crumbs[] = [
                'label' => $subject ? format_string($subject) : $text,
                'url' => '',
                'home' => false,
            ];
        } else {
            $crumbs[] = [
                'label' => $title,
                'url' => '',
                'home' => false,
            ];
        }

        return [
            'title' => $title,
            'narrow' => $entryid > 0,
            'crumbs' => $crumbs,
            'empty' => !$entryid && $this->blog_is_site_index() && $this->blog_site_is_empty(),
            'emptytext' => get_string('noentriesyet', 'blog'),
        ];
    }

    /**
     * Whether the current user can see any site blog entries.
     *
     * @return bool
     */
    private function blog_site_is_empty(): bool {
        global $CFG;
        require_once($CFG->dirroot . '/blog/locallib.php');
        $listing = new \blog_listing([]);
        return $listing->count_entries() === 0;
    }

    /**
     * Compact social icons for the login layout, which has no marketing footer.
     *
     * @return string
     */
    public function nextgen_social_links(): string {
        $social = \theme_nextgen\local\social::links();
        if (!$social) {
            return '';
        }
        return $this->render_from_template('theme_nextgen/social_links', [
            'variant' => 'auth',
            'showlabels' => false,
            'social' => $social,
        ]);
    }
}
