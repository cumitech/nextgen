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
 * NextGen LMS theme settings.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/theme/boost/classes/admin_settingspage_tabs.php');

if ($ADMIN->fulltree) {
    $settings = new theme_boost_admin_settingspage_tabs(
        'themesettingnextgen',
        get_string('configtitle', 'theme_nextgen')
    );

    $page = new admin_settingpage('theme_nextgen_general', get_string('generalsettings', 'theme_nextgen'));

    $setting = new admin_setting_configstoredfile(
        'theme_nextgen/logo',
        get_string('logo', 'theme_nextgen'),
        get_string('logodesc', 'theme_nextgen'),
        'logo',
        0,
        ['accepted_types' => ['.png', '.jpg', '.jpeg', '.gif', '.svg', '.webp'], 'maxfiles' => 1]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_configstoredfile(
        'theme_nextgen/favicon',
        get_string('favicon', 'theme_nextgen'),
        get_string('favicondesc', 'theme_nextgen'),
        'favicon',
        0,
        ['accepted_types' => ['.ico', '.png', '.svg'], 'maxfiles' => 1]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_nextgen/primarycolor';
    $setting = new admin_setting_configcolourpicker(
        $name,
        get_string('primarycolor', 'theme_nextgen'),
        get_string('primarycolordesc', 'theme_nextgen'),
        '#163A4A'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_configcolourpicker(
        'theme_nextgen/secondarycolor',
        get_string('secondarycolor', 'theme_nextgen'),
        get_string('secondarycolordesc', 'theme_nextgen'),
        '#102833'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_configcolourpicker(
        'theme_nextgen/accentcolor',
        get_string('accentcolor', 'theme_nextgen'),
        get_string('accentcolordesc', 'theme_nextgen'),
        '#C4842A'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/customnav',
        get_string('customnav', 'theme_nextgen'),
        get_string('customnavdesc', 'theme_nextgen'),
        "Courses|/course/index.php\nCategories|/course/index.php?browse=categories\nContact|/user/contactsitesupport.php",
        PARAM_RAW,
        60,
        6
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/unaddableblocks',
        get_string('unaddableblocks', 'theme_nextgen'),
        get_string('unaddableblocks_desc', 'theme_nextgen'),
        'navigation,settings,course_list',
        PARAM_TEXT
    );
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_nextgen_footer', get_string('footersettings', 'theme_nextgen'));

    $setting = new admin_setting_confightmleditor(
        'theme_nextgen/footerabout',
        get_string('footerabout', 'theme_nextgen'),
        get_string('footeraboutdesc', 'theme_nextgen'),
        get_string('footeraboutdefault', 'theme_nextgen')
    );
    $page->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/quicklinks',
        get_string('quicklinks', 'theme_nextgen'),
        get_string('linklistdesc', 'theme_nextgen'),
        "Home|/\nCourses|/course/index.php",
        PARAM_RAW,
        60,
        5
    );
    $page->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/learninglinks',
        get_string('learninglinks', 'theme_nextgen'),
        get_string('linklistdesc', 'theme_nextgen'),
        "My learning|/my/courses.php\nDashboard|/my/",
        PARAM_RAW,
        60,
        5
    );
    $page->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/supportlinks',
        get_string('supportlinks', 'theme_nextgen'),
        get_string('linklistdesc', 'theme_nextgen'),
        "Contact support|/user/contactsitesupport.php",
        PARAM_RAW,
        60,
        5
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/contactemail',
        get_string('contactemail', 'theme_nextgen'),
        get_string('contactemaildesc', 'theme_nextgen'),
        '',
        PARAM_EMAIL
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/contactphone',
        get_string('contactphone', 'theme_nextgen'),
        get_string('contactphonedesc', 'theme_nextgen'),
        '',
        PARAM_TEXT
    );
    $page->add($setting);

    foreach ([
        'facebookurl' => 'facebook',
        'instagramurl' => 'instagram',
        'linkedinurl' => 'linkedin',
        'youtubeurl' => 'youtube',
        'xurl' => 'x',
    ] as $settingname => $stringid) {
        $setting = new admin_setting_configtext(
            'theme_nextgen/' . $settingname,
            get_string($stringid, 'theme_nextgen'),
            get_string('socialdesc', 'theme_nextgen'),
            '',
            PARAM_URL
        );
        $page->add($setting);
    }

    $setting = new admin_setting_configtext(
        'theme_nextgen/copyright',
        get_string('copyright', 'theme_nextgen'),
        get_string('copyrightdesc', 'theme_nextgen'),
        '',
        PARAM_TEXT
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/privacyurl',
        get_string('privacyurl', 'theme_nextgen'),
        get_string('policyurldesc', 'theme_nextgen'),
        '',
        PARAM_RAW
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/termsurl',
        get_string('termsurl', 'theme_nextgen'),
        get_string('policyurldesc', 'theme_nextgen'),
        '',
        PARAM_RAW
    );
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_nextgen_frontpage', get_string('frontpagesettings', 'theme_nextgen'));

    $setting = new admin_setting_configtext(
        'theme_nextgen/herotitle',
        get_string('herotitle', 'theme_nextgen'),
        get_string('herotitle_desc', 'theme_nextgen'),
        get_string('herotitledefault', 'theme_nextgen'),
        PARAM_TEXT
    );
    $page->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/herotext',
        get_string('herotext', 'theme_nextgen'),
        get_string('herotext_desc', 'theme_nextgen'),
        get_string('herotextdefault', 'theme_nextgen'),
        PARAM_TEXT,
        60,
        4
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/heroprimary',
        get_string('heroprimary', 'theme_nextgen'),
        get_string('herobuttondesc', 'theme_nextgen'),
        get_string('heroprimarydefault', 'theme_nextgen'),
        PARAM_RAW
    );
    $page->add($setting);

    $setting = new admin_setting_configtext(
        'theme_nextgen/herosecondary',
        get_string('herosecondary', 'theme_nextgen'),
        get_string('herobuttondesc', 'theme_nextgen'),
        get_string('herosecondarydefault', 'theme_nextgen'),
        PARAM_RAW
    );
    $page->add($setting);

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/testimonials',
        get_string('testimonials', 'theme_nextgen'),
        get_string('testimonialsdesc', 'theme_nextgen'),
        get_string('testimonialsdefault', 'theme_nextgen'),
        PARAM_RAW,
        60,
        6
    );
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_nextgen_login', get_string('loginsettings', 'theme_nextgen'));

    $setting = new admin_setting_configstoredfile(
        'theme_nextgen/loginbackgroundimage',
        get_string('loginbackgroundimage', 'theme_nextgen'),
        get_string('loginbackgroundimagedesc', 'theme_nextgen'),
        'loginbackgroundimage'
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);

    $page = new admin_settingpage('theme_nextgen_advanced', get_string('advancedsettings', 'theme_nextgen'));

    $setting = new admin_setting_configtextarea(
        'theme_nextgen/customcss',
        get_string('customcss', 'theme_nextgen'),
        get_string('customcssdesc', 'theme_nextgen'),
        '',
        PARAM_RAW,
        60,
        10
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $settings->add($page);
}
