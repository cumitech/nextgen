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
 * Template data for the About page. All copy comes from the language pack.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class about {
    /** @var array<string, string> Section photographs, keyed by section, from the theme pix folder. */
    private const IMAGES = [
        'who' => 'hero-home',
        'teach' => 'category-teaching',
        'gain' => 'hero-slide-3',
        'vision' => 'hero-slide-2',
        'contact' => 'hero-slide-1',
    ];

    /**
     * Everything the about_page template needs.
     *
     * @return array
     */
    public static function context(): array {
        global $CFG, $OUTPUT;
        require_once($CFG->libdir . '/authlib.php');

        $offices = organisation::offices();
        $phone = organisation::phone();
        $email = organisation::email();

        $images = [];
        foreach (self::IMAGES as $section => $file) {
            $images[$section] = $OUTPUT->image_url($file, 'theme_nextgen')->out(false);
        }

        $outcomes = [];
        for ($index = 1; $index <= 3; $index++) {
            $outcomes[] = ['text' => self::str('aboutoutcome' . $index)];
        }

        $commitments = [];
        for ($index = 1; $index <= 8; $index++) {
            $commitments[] = [
                'title' => self::str('aboutcommitment' . $index . 'title'),
                'text' => self::str('aboutcommitment' . $index . 'text'),
            ];
        }

        $founder = self::str('aboutfoundername');
        $participants = [];
        foreach (self::lines('aboutparticipants') as $line) {
            $participants[] = [
                'name' => $line['text'],
                'isfounder' => $line['text'] === $founder,
            ];
        }

        $guest = !isloggedin() || isguestuser();
        $signup = $guest && \signup_is_enabled();

        return [
            'images' => $images,
            'areas' => self::lines('aboutareas'),
            'outcomes' => $outcomes,
            'visionpoints' => self::lines('aboutvisionpoints'),
            'commitments' => $commitments,
            'impact' => self::impact_rows(),
            'founder' => [
                'name' => $founder,
                'image' => $OUTPUT->image_url('team/prof-vukenkeng-andrew', 'theme_nextgen')->out(false),
                'highlights' => self::lines('aboutfounderhighlights'),
                'positions' => self::lines('aboutfounderpositions'),
            ],
            'participants' => $participants,
            'hascontact' => $offices || $phone || $email !== '',
            'offices' => $offices,
            'hasphone' => $phone !== null,
            'phone' => $phone ?? [],
            'hasemail' => $email !== '',
            'email' => $email,
            'emailurl' => $email !== '' ? 'mailto:' . $email : '',
            'contacturl' => public_page::url('contact')->out(false),
            'teamurl' => public_page::url('team')->out(false),
            'coursesurl' => (new \moodle_url('/course/index.php'))->out(false),
            'whotext2' => theme_nextgen_about_who_text(),
            'teachtext' => theme_nextgen_catalogue_lead('aboutteachtext'),
            'cansignup' => $signup,
            'signupurl' => (new \moodle_url('/login/signup.php'))->out(false),
            'partnersblock' => organisation::partners_block('about', true),
        ];
    }

    /**
     * Challenge rows, each answered by the vision, the mission, or both.
     *
     * @return array
     */
    private static function impact_rows(): array {
        $manager = get_string_manager();
        $rows = [];
        for ($index = 1; $index <= 7; $index++) {
            $responses = [];
            foreach (['vision', 'mission'] as $part) {
                $key = 'aboutimpact' . $index . $part;
                if ($manager->string_exists($key, 'theme_nextgen')) {
                    $responses[] = [
                        'tag' => self::str('aboutimpacttag' . $part),
                        'text' => self::str($key),
                    ];
                }
            }
            $rows[] = [
                'challenge' => self::str('aboutimpact' . $index . 'challenge'),
                'responses' => $responses,
            ];
        }
        return $rows;
    }

    /**
     * One list item per non-empty line of a language string.
     *
     * @param string $key
     * @return array<int, array{text: string}>
     */
    private static function lines(string $key): array {
        $items = [];
        foreach (preg_split('/\R/', self::str($key)) as $line) {
            $line = trim($line);
            if ($line !== '') {
                $items[] = ['text' => $line];
            }
        }
        return $items;
    }

    /**
     * Theme string.
     *
     * @param string $key
     * @return string
     */
    private static function str(string $key): string {
        return get_string($key, 'theme_nextgen');
    }
}
