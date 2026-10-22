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
 * Template data for the team page.
 *
 * Copy comes from the language pack. Photographs live in pix/team.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class team {
    /**
     * People shown on the team page, in display order.
     *
     * The founder is first, then Favour, Anembom, Tracey, Akumbom, Peter,
     * and Forbe. Remaining doctors follow, then the other members.
     * Ayeah is last.
     * image is a theme pix path without an extension. founder reuses the About biography.
     * collapse keeps the first paragraph visible and tucks the rest behind Show more.
     * offset frames a photograph whose subject is not in the centre.
     *
     * @var array<int, array{id: string, image: string, founder?: bool, collapse?: bool, offset?: bool}>
     */
    private const MEMBERS = [
        [
            'id' => 'founder',
            'image' => 'team/prof-vukenkeng-andrew',
            'founder' => true,
            'offset' => true,
        ],
        [
            'id' => 'favour',
            'image' => 'team/buemembom-favour-wujung',
        ],
        [
            'id' => 'francine',
            'image' => 'team/anembom-francine',
            'collapse' => true,
        ],
        [
            'id' => 'kiambom',
            'image' => 'team/kiambom-tracey',
            'collapse' => true,
        ],
        [
            'id' => 'akumbom',
            'image' => 'team/akumbom-paul',
        ],
        [
            'id' => 'peter',
            'image' => 'team/dr-peter-abuengmoh',
            'collapse' => true,
        ],
        [
            'id' => 'forbe',
            'image' => 'team/dr-forbe-hodu',
        ],
        [
            'id' => 'derick',
            'image' => 'team/derick-yong',
        ],
        [
            'id' => 'nayah',
            'image' => 'team/dr-nayah-gazelle',
        ],
        [
            'id' => 'ndzembanteh',
            'image' => 'team/dr-ndzembanteh-aboubakary-nulambeh',
            'collapse' => true,
        ],
        [
            'id' => 'sigimi',
            'image' => 'team/dr-sigimi-fidelis',
        ],
        [
            'id' => 'awemu',
            'image' => 'team/dr-awemu-jonah',
            'collapse' => true,
        ],
        [
            'id' => 'ponmuma',
            'image' => 'team/ponmuma-gabin-merveille',
        ],
        [
            'id' => 'mbala',
            'image' => 'team/mbala-axel',
        ],
        [
            'id' => 'etame',
            'image' => 'team/etame-vivian',
        ],
        [
            'id' => 'emmanuel',
            'image' => 'team/emmanuel-mbunwe',
            'collapse' => true,
        ],
        [
            'id' => 'ayeah',
            'image' => 'team/ayeah-godlove',
            'collapse' => true,
        ],
    ];

    /**
     * Everything the team_page template needs.
     *
     * @return array
     */
    public static function context(): array {
        global $OUTPUT;

        $members = [];
        foreach (self::MEMBERS as $spec) {
            $members[] = self::member($spec, $OUTPUT);
        }

        return [
            'members' => $members,
        ];
    }

    /**
     * One profile.
     *
     * @param array $spec
     * @param \renderer_base $output
     * @return array
     */
    private static function member(array $spec, \renderer_base $output): array {
        $id = $spec['id'];
        $founder = !empty($spec['founder']);
        $lead = [];
        $more = [];
        $highlights = [];
        if ($founder) {
            $name = self::str('aboutfoundername');
            $role = self::str('aboutfounderlabel');
            $title = self::str('aboutfoundertitle');
            $highlights = self::lines('aboutfounderhighlights');
        } else {
            $name = self::str('teammember' . $id . 'name');
            $role = self::str('teammember' . $id . 'role');
            $title = '';
            $paragraphs = self::paragraphs('teammember' . $id . 'bio');
            if (!empty($spec['collapse']) && count($paragraphs) > 1) {
                $lead = [array_shift($paragraphs)];
                $more = $paragraphs;
            } else {
                $lead = $paragraphs;
            }
        }

        $facts = [];
        $factkey = 'teammember' . $id . 'facts';
        if (get_string_manager()->string_exists($factkey, 'theme_nextgen')) {
            $facts = self::facts($factkey);
        }

        return [
            'id' => $id,
            'name' => $name,
            'role' => $role,
            'title' => $title,
            'hastitle' => $title !== '',
            'image' => $output->image_url($spec['image'], 'theme_nextgen')->out(false),
            'offset' => !empty($spec['offset']),
            'isfounder' => $founder,
            'highlights' => $highlights,
            'hashighlights' => $highlights !== [],
            'positions' => $founder ? self::lines('aboutfounderpositions') : [],
            'lead' => $lead,
            'more' => $more,
            'hasmore' => $founder || $more !== [],
            'facts' => $facts,
            'hasfacts' => $facts !== [],
        ];
    }

    /**
     * One list item per non-empty line.
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
     * One paragraph per blank-line break.
     *
     * @param string $key
     * @return array<int, array{text: string}>
     */
    private static function paragraphs(string $key): array {
        $items = [];
        $text = trim(self::str($key));
        if ($text === '') {
            return [];
        }
        foreach (preg_split("/\R\R+/", $text) as $paragraph) {
            $paragraph = trim($paragraph);
            if ($paragraph !== '') {
                $items[] = ['text' => $paragraph];
            }
        }
        return $items;
    }

    /**
     * Label|value lines. An email or phone number becomes a link.
     *
     * @param string $key
     * @return array<int, array{label: string, value: string, url: string}>
     */
    private static function facts(string $key): array {
        $items = [];
        foreach (preg_split('/\R/', self::str($key)) as $line) {
            $line = trim($line);
            if ($line === '' || !str_contains($line, '|')) {
                continue;
            }
            [$label, $value] = explode('|', $line, 2);
            $label = trim($label);
            $value = trim($value);
            if ($label === '' || $value === '') {
                continue;
            }
            $url = '';
            if (validate_email($value)) {
                $url = 'mailto:' . $value;
            } else if (preg_match('/^\+?[0-9][0-9\s().-]{6,}$/', $value)) {
                $url = 'tel:' . preg_replace('/[^0-9+]/', '', $value);
            }
            $items[] = [
                'label' => $label,
                'value' => $value,
                'url' => $url,
            ];
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
