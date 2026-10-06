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

use theme_nextgen\form\contact;

/**
 * Public information pages for the NextGen theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class public_page {
    /**
     * Script name for a public page.
     *
     * @param string $key about, contact, privacy, terms, or faq
     * @return \moodle_url
     */
    public static function url(string $key): \moodle_url {
        if (!in_array($key, self::keys(), true)) {
            throw new \coding_exception('Unknown public page ' . $key);
        }
        return new \moodle_url('/' . $key . '.php');
    }

    /**
     * Public page keys. Each one is a script in the site root, not the theme directory.
     *
     * @return string[]
     */
    public static function keys(): array {
        return ['about', 'contact', 'privacy', 'terms', 'faq'];
    }

    /**
     * Header links that every visitor needs: About and Contact.
     *
     * @param \navigation_node $primary
     */
    public static function extend_primary(\navigation_node $primary): void {
        global $PAGE;

        if (during_initial_install()) {
            return;
        }

        $present = [];
        foreach (theme_nextgen_custom_nav_items() as $item) {
            $present[$item['url']] = $item['key'];
        }

        foreach (self::header_pages() as $key => $label) {
            $url = self::url($key);
            $out = $url->out(false);
            $tab = $present[$out] ?? ('nextgen-' . $key);
            if (!isset($present[$out])) {
                $node = $primary->add($label, $url, \navigation_node::TYPE_CUSTOM, null, 'nextgen-' . $key);
                if ($node) {
                    $node->isactive = false;
                }
            }
            if ($PAGE->url instanceof \moodle_url && $PAGE->url->compare($url, URL_MATCH_BASE)) {
                $PAGE->set_primary_active_tab($tab);
            }
        }
    }

    /**
     * Render one public page inside the standard Moodle layout.
     *
     * @param string $key about, contact, privacy, terms, or faq
     */
    public static function render(string $key): void {
        global $OUTPUT, $PAGE;

        $definition = self::definition($key);
        $PAGE->set_context(\context_system::instance());
        $PAGE->set_url(self::url($key));
        $PAGE->set_pagelayout('standard');
        $PAGE->set_title($definition['title']);
        $PAGE->set_heading($definition['title']);
        $PAGE->add_body_class('nextgen-public');
        if ($key === 'contact') {
            $PAGE->add_body_class('nextgen-contact');
        } else if ($key === 'about') {
            $PAGE->add_body_class('nextgen-about');
        }
        $PAGE->navbar->add($definition['title']);

        $formhtml = '';
        if ($key === 'contact') {
            $formhtml = self::contact_form();
        }

        echo $OUTPUT->header();
        echo $OUTPUT->render_from_template('theme_nextgen/public_page', array_merge([
            'kicker' => $definition['kicker'],
            'title' => $definition['title'],
            'intro' => $definition['intro'],
            'sections' => $definition['sections'],
            'hasquestions' => !empty($definition['questions']),
            'questions' => $definition['questions'],
            'navbar' => $OUTPUT->navbar(),
            'hasform' => $formhtml !== '',
            'form' => $formhtml,
            'iscontact' => $key === 'contact',
            'isabout' => $key === 'about',
            'isdefault' => $key !== 'contact' && $key !== 'about',
        ], self::contact_context($key), self::about_context($key, $definition)));
        echo $OUTPUT->footer();
    }

    /**
     * Extra template data for the about page layout.
     *
     * @param string $key
     * @param array $definition
     * @return array
     */
    private static function about_context(string $key, array $definition): array {
        if ($key !== 'about') {
            return [];
        }

        $features = [];
        $cta = null;
        foreach ($definition['sections'] as $section) {
            if (!empty($section['url'])) {
                $cta = $section;
                continue;
            }
            $features[] = $section;
        }

        return [
            'features' => $features,
            'hasfeatures' => !empty($features),
            'hascta' => $cta !== null,
            'cta' => $cta ?? [],
        ];
    }

    /**
     * Extra template data for the contact page layout.
     *
     * @param string $key
     * @return array
     */
    private static function contact_context(string $key): array {
        if ($key !== 'contact') {
            return [];
        }

        $theme = \theme_config::load('nextgen');
        $email = trim(theme_nextgen_setting($theme, 'contactemail'));
        $phone = trim(theme_nextgen_setting($theme, 'contactphone'));
        if ($email === '') {
            $support = \core_user::get_support_user();
            $email = trim((string) ($support->email ?? ''));
        }
        $dial = preg_replace('/[^\d+]/', '', $phone);

        return [
            'formtitle' => get_string('contactformtitle', 'theme_nextgen'),
            'formlead' => get_string('contactformlead', 'theme_nextgen'),
            'tipstitle' => get_string('contacttipstitle', 'theme_nextgen'),
            'tips' => [
                ['text' => get_string('contacttip1', 'theme_nextgen')],
                ['text' => get_string('contacttip2', 'theme_nextgen')],
                ['text' => get_string('contacttip3', 'theme_nextgen')],
            ],
            'response' => get_string('contactresponse', 'theme_nextgen'),
            'haschannels' => ($email !== '' || $phone !== ''),
            'channelstitle' => get_string('contactchannels', 'theme_nextgen'),
            'channelslead' => get_string('contactchannelslead', 'theme_nextgen'),
            'hasemail' => $email !== '',
            'email' => $email,
            'emailurl' => $email !== '' ? 'mailto:' . $email : '',
            'hasphone' => $phone !== '',
            'phone' => $phone,
            'phoneurl' => $dial !== '' ? 'tel:' . $dial : '',
        ];
    }

    /**
     * About and Contact belong in the header.
     *
     * @return array<string, string>
     */
    private static function header_pages(): array {
        return [
            'about' => get_string('aboutnav', 'theme_nextgen'),
            'contact' => get_string('contactnav', 'theme_nextgen'),
        ];
    }

    /**
     * Copy for one page. Text comes from the language pack.
     *
     * @param string $key
     * @return array
     */
    private static function definition(string $key): array {
        $sections = [];
        $questions = [];
        $count = (int) get_string($key . 'sectioncount', 'theme_nextgen');
        for ($index = 1; $index <= $count; $index++) {
            $sections[] = [
                'title' => get_string($key . 'section' . $index . 'title', 'theme_nextgen'),
                'text' => get_string($key . 'section' . $index . 'text', 'theme_nextgen'),
            ];
        }
        if ($key === 'faq') {
            $questions = $sections;
            $sections = [];
            foreach ($questions as $index => $section) {
                $questions[$index] = [
                    'question' => $section['title'],
                    'answer' => $section['text'],
                ];
            }
        }
        if ($key === 'about' && $sections) {
            $last = count($sections) - 1;
            $sections[$last]['url'] = (new \moodle_url('/course/index.php'))->out(false);
            $sections[$last]['urllabel'] = get_string('aboutctalabel', 'theme_nextgen');
        }

        return [
            'kicker' => get_string($key . 'kicker', 'theme_nextgen'),
            'title' => get_string($key . 'title', 'theme_nextgen'),
            'intro' => get_string($key . 'intro', 'theme_nextgen'),
            'sections' => $sections,
            'questions' => $questions,
        ];
    }

    /**
     * Moodle's site-support form, posted to this theme page.
     *
     * The message is emailed to the site support user and is not stored by the theme.
     *
     * @return string
     */
    private static function contact_form(): string {
        global $CFG, $PAGE, $USER;

        require_once($CFG->dirroot . '/user/lib.php');

        $signedin = isloggedin() && !isguestuser();
        $user = $signedin ? $USER : null;
        $form = new contact($PAGE->url, $user);
        if ($form->is_cancelled()) {
            redirect(new \moodle_url('/'));
        }
        if ($form->is_submitted() && $form->is_validated() && confirm_sesskey()) {
            $data = $form->get_data();
            $data->notloggedinuser = !$signedin;
            if (self::send_contact($data, $user)) {
                redirect(
                    $PAGE->url,
                    get_string('supportmessagesent', 'user'),
                    null,
                    \core\output\notification::NOTIFY_SUCCESS
                );
            }
            \core\notification::error(get_string('contactsendfailed', 'theme_nextgen'));
        }

        return $form->render();
    }

    /**
     * Email the site support user using Moodle's mail API.
     *
     * @param \stdClass $data
     * @param \stdClass|null $user
     * @return bool
     */
    private static function send_contact(\stdClass $data, ?\stdClass $user): bool {
        global $OUTPUT, $SITE;

        $from = $user ?? \core_user::get_noreply_user();
        $subject = get_string('supportemailsubject', 'admin', format_string($SITE->fullname));
        $message = $OUTPUT->render_from_template('user/contact_site_support_email_body', $data);

        return (bool) email_to_user(
            user: \core_user::get_support_user(),
            from: $from,
            subject: $subject,
            messagetext: $message,
            usetrueaddress: true,
            replyto: $data->email,
            replytoname: $data->name
        );
    }
}
