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
 * Social network links shown in the footer, login page, and public pages.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class social {
    /** @var array<string, string> Setting name to network key, in display order. */
    public const NETWORKS = [
        'facebookurl' => 'facebook',
        'instagramurl' => 'instagram',
        'tiktokurl' => 'tiktok',
        'xurl' => 'x',
        'linkedinurl' => 'linkedin',
        'youtubeurl' => 'youtube',
    ];

    /** @var array<string, string> Official NextGen Entrepreneurship & Development profiles. */
    public const DEFAULTS = [
        'facebookurl' => 'https://www.facebook.com/share/19i9JkkeL5/',
        'instagramurl' => 'https://www.instagram.com/nextgenentrepdevltd/',
        'tiktokurl' => 'https://www.tiktok.com/@nextgen.entrep.de',
        'xurl' => 'https://x.com/defave237',
        'linkedinurl' => '',
        'youtubeurl' => '',
    ];

    /**
     * Configured networks with a valid https URL.
     *
     * @return array
     */
    public static function links(): array {
        $links = [];
        foreach (self::NETWORKS as $setting => $network) {
            $url = self::clean_url((string) get_config('theme_nextgen', $setting));
            if ($url === null) {
                continue;
            }
            $label = get_string($network, 'theme_nextgen');
            $links[] = [
                'network' => $network,
                'label' => $label,
                'url' => $url,
                'handle' => self::handle($network, $url),
                'arialabel' => get_string('socialfollowon', 'theme_nextgen', $label),
                'is' . $network => true,
            ];
        }
        return $links;
    }

    /**
     * Fill empty network settings with the official profiles.
     */
    public static function install_defaults(): void {
        foreach (self::DEFAULTS as $setting => $url) {
            if ($url === '') {
                continue;
            }
            $current = get_config('theme_nextgen', $setting);
            if ($current === false || trim((string) $current) === '') {
                set_config($setting, $url, 'theme_nextgen');
            }
        }
    }

    /**
     * Accept absolute http or https URLs only.
     *
     * @param string $url
     * @return string|null
     */
    private static function clean_url(string $url): ?string {
        $url = trim($url);
        if ($url === '') {
            return null;
        }
        $clean = clean_param($url, PARAM_URL);
        if (empty($clean) || !preg_match('#^https?://#i', $clean)) {
            return null;
        }
        return $clean;
    }

    /**
     * Profile handle such as @name, when the URL path shows one.
     *
     * @param string $network
     * @param string $url
     * @return string
     */
    private static function handle(string $network, string $url): string {
        $path = trim((string) parse_url($url, PHP_URL_PATH), '/');
        $first = explode('/', $path)[0] ?? '';
        if ($first === '') {
            return '';
        }
        if (str_starts_with($first, '@')) {
            return $first;
        }
        if (in_array($network, ['instagram', 'x'], true)) {
            return '@' . $first;
        }
        return '';
    }
}
