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
 * Shared organisation details: contact, offices, and partner logos.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class organisation {
    /** @var string Phone number for Next Gen Entrepreneurship Development Ltd. */
    public const DEFAULT_PHONE = '+237 652 704 736';

    /** @var string Offices, one per line, written as Label|Address. */
    public const DEFAULT_OFFICES = "Main office|Opposite Tradex Nomayous, Yaoundé\nBranch|Carrefour Ekonou, Bertoua";

    /** @var string Home page hero text shipped before the project copy existed. */
    private const LEGACY_HEROTEXT = 'Practical online courses designed to help you learn, practise, and reach the next step in your work.';

    /**
     * Configured phone number with a dialable link.
     *
     * @return array{display: string, url: string}|null
     */
    public static function phone(): ?array {
        $display = trim((string) get_config('theme_nextgen', 'contactphone'));
        if ($display === '') {
            return null;
        }
        $dial = preg_replace('/[^0-9+]/', '', $display);
        return [
            'display' => $display,
            'url' => $dial !== '' ? 'tel:' . $dial : '',
        ];
    }

    /**
     * Contact email: the theme setting, or the site support email when that is empty.
     *
     * The no-reply address is never returned, because nobody reads replies to it.
     *
     * @return string
     */
    public static function email(): string {
        global $CFG;

        $email = trim((string) get_config('theme_nextgen', 'contactemail'));
        if ($email === '') {
            $email = trim((string) ($CFG->supportemail ?? ''));
        }
        if ($email === '' || $email === trim((string) ($CFG->noreplyaddress ?? ''))) {
            return '';
        }
        return $email;
    }

    /**
     * Configured offices, each with a map search link.
     *
     * @return array<int, array{label: string, address: string, mapurl: string}>
     */
    public static function offices(): array {
        $raw = (string) get_config('theme_nextgen', 'contactaddress');
        $offices = [];
        foreach (preg_split('/\R/', $raw) as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $parts = array_map('trim', explode('|', $line, 2));
            $label = count($parts) === 2 ? $parts[0] : '';
            $address = clean_param(count($parts) === 2 ? $parts[1] : $parts[0], PARAM_TEXT);
            if ($address === '') {
                continue;
            }
            $map = new \moodle_url('https://www.google.com/maps/search/', [
                'api' => 1,
                'query' => $address,
            ]);
            $offices[] = [
                'label' => clean_param($label, PARAM_TEXT),
                'address' => $address,
                'mapurl' => $map->out(false),
            ];
        }
        return $offices;
    }

    /**
     * Partner organisations shown on the landing page, About, Contact, and footer.
     *
     * @return array<int, array{
     *     key: string,
     *     name: string,
     *     shortname: string,
     *     url: string,
     *     image: string,
     *     hasimage: bool,
     *     iswordmark: bool
     * }>
     */
    public static function partners(): array {
        global $OUTPUT;

        $items = [
            [
                'key' => 'minefop',
                'name' => get_string('partnerminefop', 'theme_nextgen'),
                'shortname' => get_string('partnerminefop', 'theme_nextgen'),
                'url' => 'https://minefop.gov.cm/en/',
                'pix' => 'partner-minefop',
            ],
            [
                'key' => 'minpmeesa',
                'name' => get_string('partnerminpmeesa', 'theme_nextgen'),
                'shortname' => get_string('partnerminpmeesa', 'theme_nextgen'),
                'url' => 'https://www.minpmeesa.cm/',
                'pix' => 'partner-minpmeesa',
            ],
            [
                'key' => 'apme',
                'name' => get_string('partnerapme', 'theme_nextgen'),
                'shortname' => get_string('partnerapme', 'theme_nextgen'),
                'url' => 'https://apme.cm/accueil/',
                'pix' => 'partner-apme',
            ],
            [
                'key' => 'esrc',
                'name' => get_string('partneresrc', 'theme_nextgen'),
                'shortname' => get_string('partneresrc', 'theme_nextgen'),
                'url' => 'https://esrccameroon.org/',
                'pix' => 'partner-esrc',
            ],
            [
                'key' => 'cga',
                'name' => get_string('partnercga', 'theme_nextgen'),
                'shortname' => get_string('partnercga', 'theme_nextgen'),
                'url' => 'https://ccima.cm/',
                'pix' => 'partner-cga',
            ],
            [
                'key' => 'istp',
                'name' => get_string('partneristp', 'theme_nextgen'),
                'shortname' => get_string('partneristpshort', 'theme_nextgen'),
                'url' => 'https://istpcam.wordpress.com/',
                'pix' => '',
            ],
        ];

        $partners = [];
        foreach ($items as $item) {
            $hasimage = $item['pix'] !== '';
            $partners[] = [
                'key' => $item['key'],
                'name' => $item['name'],
                'shortname' => $item['shortname'],
                'url' => $item['url'],
                'image' => $hasimage ? $OUTPUT->image_url($item['pix'], 'theme_nextgen')->out(false) : '',
                'hasimage' => $hasimage,
                'iswordmark' => !$hasimage,
            ];
        }
        return $partners;
    }

    /**
     * Template data for the partners strip.
     *
     * @param string $variant landing, about, contact, or footer
     * @param bool $showlead
     * @return array
     */
    public static function partners_block(string $variant = 'landing', bool $showlead = false): array {
        return [
            'variant' => $variant,
            'showlead' => $showlead,
            'partners' => self::partners(),
        ];
    }

    /**
     * Fill an empty phone or address setting, and move the home hero onto the project copy.
     */
    public static function install_defaults(): void {
        $defaults = [
            'contactphone' => self::DEFAULT_PHONE,
            'contactaddress' => self::DEFAULT_OFFICES,
        ];
        foreach ($defaults as $setting => $value) {
            $current = get_config('theme_nextgen', $setting);
            if ($current === false || trim((string) $current) === '') {
                set_config($setting, $value, 'theme_nextgen');
            }
        }

        $herotext = get_config('theme_nextgen', 'herotext');
        if ($herotext === false || trim((string) $herotext) === self::LEGACY_HEROTEXT) {
            set_config('herotext', get_string('herotextdefault', 'theme_nextgen'), 'theme_nextgen');
        }
    }
}
