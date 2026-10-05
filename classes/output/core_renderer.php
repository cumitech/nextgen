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
     * Footer fragment without the mobile app, device theme, and retention links.
     *
     * @return string
     */
    #[\Override]
    public function standard_footer_html() {
        $html = parent::standard_footer_html();
        $html = preg_replace('/<div>\s*<a\b[^>]*class="mobilelink"[^>]*>.*?<\/a>\s*<\/div>/s', '', $html);
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
}
