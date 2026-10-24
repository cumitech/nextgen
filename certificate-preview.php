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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Public design preview for a visible course-certificate template.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$configpaths = [
    __DIR__ . '/../../config.php',
    __DIR__ . '/../../../config.php',
];
if (!empty($_SERVER['DOCUMENT_ROOT'])) {
    $configpaths[] = rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'config.php';
    $configpaths[] = dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'config.php';
}
$configfile = false;
foreach ($configpaths as $path) {
    $resolvedpath = realpath($path);
    if ($resolvedpath !== false && is_file($resolvedpath)) {
        $configfile = $resolvedpath;
        break;
    }
}
if ($configfile === false) {
    throw new RuntimeException('Unable to locate Moodle config.php for the certificate preview.');
}
require_once($configfile);

$courseid = required_param('courseid', PARAM_INT);
$templateid = required_param('templateid', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid], '*', MUST_EXIST);

if (empty($course->visible) || $courseid === SITEID
        || !$DB->get_manager()->table_exists('coursecertificate')
        || !class_exists('\tool_certificate\template')) {
    throw new moodle_exception('notfound');
}

$modinfo = get_fast_modinfo($course);
$hasvisiblecertificate = false;
foreach ($modinfo->get_cms() as $cm) {
    if ($cm->modname !== 'coursecertificate' || empty($cm->visible) || !empty($cm->deletioninprogress)) {
        continue;
    }
    $section = $modinfo->get_section_info((int) $cm->sectionnum);
    if (!$section || empty($section->visible)) {
        continue;
    }
    $activity = $DB->get_record('coursecertificate', [
        'id' => (int) $cm->instance,
        'course' => $courseid,
        'template' => $templateid,
    ], 'id', IGNORE_MISSING);
    if ($activity) {
        $hasvisiblecertificate = true;
        break;
    }
}

if (!$hasvisiblecertificate) {
    throw new moodle_exception('notfound');
}

$PAGE->set_url(new moodle_url('/theme/nextgen/certificate-preview.php', [
    'courseid' => $courseid,
    'templateid' => $templateid,
]));
$PAGE->set_context(context_course::instance($courseid));
header('Content-Type: image/svg+xml; charset=utf-8');
header('Cache-Control: public, max-age=300');

$USER = (object) [
    'id' => 0,
    'firstname' => get_string('certificatesamplefirstname', 'theme_nextgen'),
    'lastname' => get_string('certificatesamplelastname', 'theme_nextgen'),
    'firstnamephonetic' => '',
    'lastnamephonetic' => '',
    'middlename' => '',
    'alternatename' => '',
    'email' => '',
    'idnumber' => '',
    'institution' => '',
    'department' => '',
    'city' => '',
    'country' => '',
    'address' => '',
    'phone1' => '',
    'phone2' => '',
    'url' => '',
    'lang' => $CFG->lang,
];

$template = \tool_certificate\template::instance($templateid);
$pages = $template->get_pages();
$pagewidth = 0;
$pageheight = 0;
foreach ($pages as $page) {
    $record = $page->to_record();
    $pagewidth = max($pagewidth, (float) $record->width);
    $pageheight += (float) $record->height;
}
if (!$pages || $pagewidth <= 0 || $pageheight <= 0) {
    throw new moodle_exception('notfound');
}

$xml = static function (string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_XML1, 'UTF-8');
};
$svg = [
    '<?xml version="1.0" encoding="UTF-8"?>',
    '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" role="img" aria-label="' .
        $xml(get_string('certificatepreviewlabel', 'theme_nextgen')) . '" viewBox="0 0 ' .
        $pagewidth . ' ' . $pageheight . '" width="' . $pagewidth . 'mm" height="' . $pageheight . 'mm">',
    '<title>' . $xml(get_string(
        'certificatepreviewtitle',
        'theme_nextgen',
        format_string($course->fullname)
    )) . '</title>',
];

$offsety = 0;
foreach ($pages as $page) {
    $record = $page->to_record();
    $svg[] = '<g transform="translate(0 ' . $offsety . ')">';
    $svg[] = '<rect width="' . $record->width . '" height="' . $record->height . '" fill="#fffdf8"/>';
    foreach ($page->get_elements() as $element) {
        if ($element->get_element() === 'image' && method_exists($element, 'get_file')) {
            $file = $element->get_file();
            if (!$file) {
                continue;
            }
            $imageinfo = json_decode((string) $element->get_data(), true) ?? [];
            if (method_exists($element, 'is_background') && $element->is_background()) {
                $imagewidth = (float) $record->width;
                $imageheight = (float) $record->height;
                $imagex = 0;
                $imagey = 0;
            } else {
                [$imagewidth, $imageheight] = \tool_certificate\element_helper::calculate_image_size(
                    $file,
                    [],
                    (float) ($imageinfo['width'] ?? 0),
                    (float) ($imageinfo['height'] ?? 0)
                );
                $imagex = (float) $element->get_posx();
                $imagey = (float) $element->get_posy();
            }
            $mimetype = $file->get_mimetype();
            if (str_starts_with($mimetype, 'image/')) {
                $svg[] = '<image x="' . $imagex . '" y="' . $imagey . '" width="' . $imagewidth .
                    '" height="' . $imageheight . '" preserveAspectRatio="none" xlink:href="data:' .
                    $xml($mimetype) . ';base64,' . base64_encode($file->get_content()) . '"/>';
            }
            continue;
        }

        if ($element->get_element() === 'code') {
            $value = get_string('certificatesamplecode', 'theme_nextgen');
        } else {
            $value = html_entity_decode(strip_tags($element->render_html()), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        if ($element->get_element() === 'program') {
            $programdata = json_decode((string) $element->get_data(), true);
            if (($programdata['display'] ?? '') === 'coursefullname') {
                $value = format_string($course->fullname);
            }
        }
        $value = trim(preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $value) ?? '');
        if ($value === '') {
            continue;
        }

        $x = (float) $element->get_posx();
        $anchor = 'start';
        if ((int) $element->get_refpoint() === \tool_certificate\element_helper::CUSTOMCERT_REF_POINT_TOPCENTER) {
            $anchor = 'middle';
        } else if ((int) $element->get_refpoint() === \tool_certificate\element_helper::CUSTOMCERT_REF_POINT_TOPRIGHT) {
            $anchor = 'end';
        }
        $fontsize = max(8, min(100, (int) $element->get_fontsize()));
        $readablesize = $fontsize * 0.3528 * ($fontsize <= 12 ? 1.65 : 1.35);
        $baseline = (float) $element->get_posy() + $readablesize * 0.8;
        $fontname = strtolower((string) $element->get_font());
        $fontfamily = str_contains($fontname, 'times') ? 'serif'
            : (str_contains($fontname, 'courier') ? 'monospace' : 'sans-serif');
        $fontweight = str_ends_with($fontname, 'b') || str_ends_with($fontname, 'bi') ? 'bold' : 'normal';
        $fontstyle = str_ends_with($fontname, 'i') ? 'italic' : 'normal';
        $colour = \tool_certificate\element_helper::validate_colour($element->get_colour())
            ? $element->get_colour()
            : '#163a4a';
        $svg[] = '<text x="' . $x . '" y="' . $baseline . '" text-anchor="' . $anchor .
            '" font-family="' . $fontfamily . '" font-size="' . $readablesize . '" font-weight="' .
            $fontweight . '" font-style="' . $fontstyle . '" fill="' . $xml($colour) . '">' .
            $xml($value) . '</text>';
    }
    $svg[] = '</g>';
    $offsety += (float) $record->height;
}
$svg[] = '</svg>';
echo implode('', $svg);
