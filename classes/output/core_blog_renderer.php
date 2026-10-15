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

/**
 * Blog entry layout for the site blog and a single post.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_blog_renderer extends \core_blog_renderer {
    /**
     * One blog entry, as a card on the index or the full post.
     *
     * @param \blog_entry $entry
     * @return string
     */
    #[\Override]
    public function render_blog_entry(\blog_entry $entry) {
        $single = (int) $this->page->url->param('entryid') === (int) $entry->id;
        return $this->render_from_template('theme_nextgen/blog_entry', $this->entry_context($entry, $single));
    }

    /**
     * Template data for one entry.
     *
     * @param \blog_entry $entry
     * @param bool $single
     * @return array
     */
    private function entry_context(\blog_entry $entry, bool $single): array {
        $syscontext = \context_system::instance();
        $user = $entry->renderable->user;
        $fullname = fullname($user, has_capability('moodle/site:viewfullnames', $syscontext));
        $dateformat = get_string('strftimedate', 'langconfig');
        $url = new \moodle_url('/blog/index.php', ['entryid' => $entry->id]);
        // Profiles send signed-out visitors to the login page. The byline stays text.
        $authorurl = '';
        if (isloggedin() && !isguestuser()) {
            $authorurl = (new \moodle_url('/user/view.php', ['id' => $user->id]))->out(false);
        }

        $plain = trim(content_to_text($entry->summary, $entry->summaryformat));
        $plain = trim(preg_replace('#https?://\S+#', '', $plain));
        $excerpt = $plain === '' ? '' : shorten_text($plain, 160);

        $modified = '';
        if ((int) $entry->created !== (int) $entry->lastmodified) {
            $modified = get_string('blogedited', 'theme_nextgen', userdate($entry->lastmodified, $dateformat));
        }

        $comments = '';
        $signinurl = '';
        if ($single && !empty($entry->renderable->comment)) {
            $comments = $entry->renderable->comment->output(true);
            if (!isloggedin() || isguestuser()) {
                $login = new \moodle_url('/login/index.php', [
                    'wantsurl' => $url->out_as_local_url(false),
                ]);
                $signinurl = $login->out(false);
            }
        }

        $media = $this->entry_media($entry);
        $tags = $single ? $this->entry_tags($entry) : [];
        $assocs = $single ? $this->entry_associations($entry) : [];
        $actions = $single ? $this->entry_actions($entry) : [];
        $image = is_array($media['image']) ? $media['image'] : null;
        $parts = $this->present_summary($entry->summary, (int) $entry->summaryformat, $image);
        $image = $parts['image'];
        $video = $this->entry_video($entry);
        $shareurl = $url->out(false);
        $sharetext = rawurlencode(format_string($entry->subject));
        $encodedurl = rawurlencode($shareurl);

        $picture = new \user_picture($user);
        $picture->size = 64;
        $picture->link = false;

        return [
            'id' => $entry->id,
            'single' => $single,
            'subject' => format_string($entry->subject),
            'url' => $shareurl,
            'date' => userdate($entry->created, $dateformat),
            'datetime' => date('c', (int) $entry->created),
            'datetimefull' => userdate($entry->created, get_string('strftimedatetime', 'langconfig')),
            'author' => $fullname,
            'authorurl' => $authorurl,
            'avatarurl' => $picture->get_url($this->page)->out(false),
            'audience' => $this->audience_label($entry->publishstate),
            'excerpt' => $excerpt,
            'lead' => $parts['lead'],
            'body' => $parts['body'],
            'hasimage' => $image !== null,
            'imageurl' => $image['url'] ?? '',
            'imagealt' => $image['alt'] ?? '',
            'hasvideo' => $video,
            'hasfiles' => !empty($media['files']),
            'files' => $media['files'],
            'external' => $single ? ($entry->renderable->externalblogtext ?? '') : '',
            'hastags' => !empty($tags),
            'tags' => $tags,
            'hasassocs' => !empty($assocs),
            'assocs' => $assocs,
            'notice' => ($single && !empty($entry->renderable->unassociatedentry))
                ? get_string('associationunviewable', 'blog') : '',
            'hasactions' => !empty($actions),
            'actions' => $actions,
            'modified' => $single ? $modified : '',
            'comments' => $comments,
            'signinurl' => $signinurl,
            'sharex' => 'https://twitter.com/intent/tweet?url=' . $encodedurl . '&text=' . $sharetext,
            'sharefacebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $encodedurl,
            'sharelinkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $encodedurl,
            'shareemail' => 'mailto:?subject=' . $sharetext . '&body=' . $encodedurl,
        ];
    }

    /**
     * Opening paragraph, picture, and the rest of the post.
     *
     * A picture written into the text is lifted out so the detail page can show
     * it between the opening paragraph and the author, as on the index card.
     *
     * @param string $summary
     * @param int $format
     * @param array|null $image Attachment picture, when the post has one
     * @return array{image: array|null, lead: string, body: string}
     */
    private function present_summary(string $summary, int $format, ?array $image): array {
        $html = trim(format_text($summary, $format, ['overflowdiv' => false]));
        if ($image === null && preg_match('/<img\b[^>]*>/i', $html, $match)) {
            $tag = $match[0];
            $url = '';
            $alt = '';
            if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $tag, $src)) {
                $url = $src[1];
            }
            if (preg_match('/\balt=["\']([^"\']*)["\']/i', $tag, $altmatch)) {
                $alt = html_entity_decode($altmatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
            if ($url !== '') {
                $image = [
                    'url' => $url,
                    'alt' => $alt,
                ];
                $html = trim(str_replace($tag, '', $html));
                $html = trim(preg_replace('/<figure\b[^>]*>\s*(?:<figcaption\b[^>]*>.*?<\/figcaption>\s*)?<\/figure>/is', '', $html));
                $html = trim(preg_replace('/<p>\s*<\/p>/i', '', $html));
            }
        }
        if (preg_match('/^(<p\b[^>]*>.*?<\/p>)/is', $html, $match)) {
            return [
                'image' => $image,
                'lead' => $match[1],
                'body' => trim(substr($html, strlen($match[0]))),
            ];
        }
        return [
            'image' => $image,
            'lead' => '',
            'body' => $html,
        ];
    }

    /**
     * Whether the post links to a YouTube video.
     *
     * @param \blog_entry $entry
     * @return bool
     */
    private function entry_video(\blog_entry $entry): bool {
        $text = $entry->summary . ' ' . ($entry->uniquehash ?? '');
        return (bool) preg_match(
            '#https?://(?:www\.)?(?:youtube\.com/(?:watch\?v=|embed/|shorts/)|youtu\.be/)[\w-]{6,}#i',
            $text
        );
    }

    /**
     * Who can read this entry, when that is narrower than the public site.
     *
     * @param string $state
     * @return string
     */
    private function audience_label(string $state): string {
        if ($state === 'draft') {
            return get_string('publishtonoone', 'blog');
        }
        return '';
    }

    /**
     * First image attachment, and any other files.
     *
     * @param \blog_entry $entry
     * @return array
     */
    private function entry_media(\blog_entry $entry): array {
        $image = false;
        $files = [];
        if (empty($entry->renderable->attachments)) {
            return ['image' => false, 'files' => []];
        }
        foreach ($entry->renderable->attachments as $attachment) {
            $isimage = file_mimetype_in_typegroup($attachment->file->get_mimetype(), 'web_image');
            if ($isimage && $image === false) {
                $image = [
                    'url' => $attachment->url,
                    'alt' => format_string($entry->subject),
                ];
                continue;
            }
            $files[] = [
                'url' => $attachment->url,
                'name' => $attachment->filename,
            ];
        }
        return ['image' => $image, 'files' => $files];
    }

    /**
     * Tags on this entry.
     *
     * @param \blog_entry $entry
     * @return array
     */
    private function entry_tags(\blog_entry $entry): array {
        $tags = [];
        foreach (\core_tag_tag::get_item_tags('core', 'post', $entry->id) as $tag) {
            $tags[] = [
                'name' => $tag->get_display_name(false),
                'url' => (new \moodle_url('/tag/index.php', ['id' => $tag->id]))->out(false),
            ];
        }
        return $tags;
    }

    /**
     * Course and activity links stored on the entry.
     *
     * @param \blog_entry $entry
     * @return array
     */
    private function entry_associations(\blog_entry $entry): array {
        $links = [];
        if (empty($entry->renderable->blogassociations)) {
            return [];
        }
        foreach ($entry->renderable->blogassociations as $assoc) {
            if (empty($assoc->url) || empty($assoc->text)) {
                continue;
            }
            $links[] = [
                'url' => $assoc->url->out(false),
                'name' => format_string($assoc->text),
            ];
        }
        return $links;
    }

    /**
     * Edit and delete for someone who can change this entry.
     *
     * @param \blog_entry $entry
     * @return array
     */
    private function entry_actions(\blog_entry $entry): array {
        if (empty($entry->renderable->usercanedit)) {
            return [];
        }
        $actions = [];
        if (empty($entry->uniquehash)) {
            $actions[] = [
                'url' => (new \moodle_url('/blog/edit.php', ['action' => 'edit', 'entryid' => $entry->id]))->out(false),
                'label' => get_string('edit'),
            ];
        }
        $actions[] = [
            'url' => (new \moodle_url('/blog/edit.php', ['action' => 'delete', 'entryid' => $entry->id]))->out(false),
            'label' => get_string('delete'),
        ];
        return $actions;
    }
}
