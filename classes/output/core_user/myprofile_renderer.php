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

namespace theme_nextgen\output\core_user;

use core\output\html_writer;
use core_user\output\myprofile\renderer;
use core_user\output\myprofile\tree;

/**
 * Profile page without the Moodle app category, its QR code, or its download link.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class myprofile_renderer extends renderer {
    /**
     * Render the profile tree, leaving out the mobile app category.
     *
     * @param tree $tree
     * @return string
     */
    #[\Override]
    public function render_tree(tree $tree) {
        $return = html_writer::start_tag('div', ['class' => 'profile_tree']);
        foreach ($tree->categories as $category) {
            if ($category->name === 'mobile') {
                continue;
            }
            $return .= $this->render($category);
        }
        $return .= html_writer::end_tag('div');
        return $return;
    }
}
