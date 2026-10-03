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
 * Load Moodle for a public theme page.
 *
 * A normal install keeps this theme in public/theme/nextgen, two levels
 * below config.php. A directory junction can make this file's real path
 * sit outside that tree, so the URL path is checked as well.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (defined('MOODLE_INTERNAL')) {
    return;
}

$candidates = [__DIR__ . '/../../config.php'];

$docroot = $_SERVER['DOCUMENT_ROOT'] ?? '';
$scriptname = $_SERVER['SCRIPT_NAME'] ?? '';
if ($docroot !== '' && $scriptname !== '') {
    $docroot = rtrim(str_replace('\\', '/', $docroot), '/');
    $base = str_replace('\\', '/', dirname(str_replace('\\', '/', $scriptname), 3));
    if ($base === '/' || $base === '.' || $base === '') {
        $base = '';
    }
    $candidates[] = $docroot . $base . '/config.php';
    $candidates[] = $docroot . $base . '/public/config.php';
}

foreach ($candidates as $candidate) {
    if (is_file($candidate)) {
        require($candidate);
        return;
    }
}

header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
echo 'Moodle config.php was not found.';
exit(1);
