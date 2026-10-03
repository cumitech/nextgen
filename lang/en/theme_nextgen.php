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
 * English strings for the NextGen LMS theme.
 *
 * @package   theme_nextgen
 * @copyright 2026 NextGen LMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'NextGen LMS';
$string['configtitle'] = 'NextGen LMS';
$string['privacy:metadata'] = 'The NextGen LMS theme does not store any personal data.';

$string['generalsettings'] = 'General';
$string['footersettings'] = 'Footer';
$string['loginsettings'] = 'Login';
$string['advancedsettings'] = 'Advanced';

$string['logo'] = 'Logo';
$string['logodesc'] = 'Optional upload. The header and login page use the included NextGen leaf mark.';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Optional upload. The browser icon uses the same NextGen leaf as the header.';

$string['primarycolor'] = 'Primary colour';
$string['primarycolordesc'] = 'Ink colour for buttons, links, and navigation. The leaf mark stays green on its own.';
$string['secondarycolor'] = 'Secondary colour';
$string['secondarycolordesc'] = 'Deep colour for the footer, the login panel, and large banners.';
$string['accentcolor'] = 'Accent colour';
$string['accentcolordesc'] = 'Gold used for highlights, kickers, and active underlines.';

$string['customnav'] = 'Header links';
$string['customnavdesc'] = 'One link per line, written as Label|/path or Label|https://example.com. These are added to Moodle\'s primary navigation. Home, Dashboard, and My courses stay under Moodle\'s control.';

$string['unaddableblocks'] = 'Unaddable blocks';
$string['unaddableblocks_desc'] = 'Comma-separated block names that should not be offered on this theme. The default hides the legacy navigation blocks that Boost already replaces.';

$string['footerabout'] = 'About text';
$string['footeraboutdesc'] = 'Short description shown in the footer brand column.';
$string['footeraboutdefault'] = 'Practical online courses for people who want to learn, practise, and move their work forward.';

$string['quicklinks'] = 'Quick links';
$string['learninglinks'] = 'Learning';
$string['supportlinks'] = 'Support';
$string['linklistdesc'] = 'One link per line, written as Label|/path or Label|https://example.com.';

$string['contactemail'] = 'Contact email';
$string['contactemaildesc'] = 'Shown in the footer. Leave empty to hide it.';
$string['contactphone'] = 'Contact phone';
$string['contactphonedesc'] = 'Shown in the footer. Leave empty to hide it.';

$string['facebook'] = 'Facebook';
$string['instagram'] = 'Instagram';
$string['linkedin'] = 'LinkedIn';
$string['youtube'] = 'YouTube';
$string['x'] = 'X';
$string['socialdesc'] = 'Full https URL. Leave empty to hide this network.';

$string['copyright'] = 'Copyright line';
$string['copyrightdesc'] = 'Leave empty to use the year and site name.';
$string['copyrightdefault'] = '© {$a->year} {$a->name}';
$string['privacyurl'] = 'Privacy policy URL';
$string['termsurl'] = 'Terms and conditions URL';
$string['policyurldesc'] = 'Site path such as /privacy or a full https URL. Leave empty to hide the link.';

$string['loginbackgroundimage'] = 'Login background image';
$string['loginbackgroundimagedesc'] = 'Optional photograph for the login panel. When empty, the panel uses the secondary brand colour.';

$string['customcss'] = 'Custom CSS';
$string['customcssdesc'] = 'CSS added after the theme styles. Changes apply after caches are purged.';

$string['frontpagesettings'] = 'Front page';
$string['herotitle'] = 'Hero headline';
$string['herotitle_desc'] = 'Main headline on the site home page.';
$string['herotitledefault'] = 'Learn skills. Build your future.';
$string['herotext'] = 'Hero description';
$string['herotext_desc'] = 'Supporting sentence under the headline.';
$string['herotextdefault'] = 'Practical online courses designed to help you learn, practise, and reach the next step in your work.';
$string['heroprimary'] = 'Primary button';
$string['herosecondary'] = 'Secondary button';
$string['herobuttondesc'] = 'One line, written as Label|/path. Use a #anchor such as #why-nextgen to jump down the page.';
$string['heroprimarydefault'] = 'Explore courses|/course/index.php';
$string['herosecondarydefault'] = 'Learn more|#why-nextgen';
$string['testimonials'] = 'Testimonials';
$string['testimonialsdesc'] = 'One quote per line, written as Quote|Attribution. Leave empty to hide the section.';
$string['testimonialsdefault'] = "The practice work was specific enough to use in my job the same week.|Programme participant\nI could see what I had finished and what was still ahead.|Learner\nThe course structure made it easy to teach a cohort without losing people.|Course lead";

$string['viewcourse'] = 'View course';
$string['platformstats'] = 'Platform figures';
$string['coursesavailable'] = 'Courses';
$string['categoriesavailable'] = 'Categories';
$string['whyheading'] = 'Why learn with NextGen';
$string['reason1title'] = 'Take time to learn new skills';
$string['reason1text'] = 'Finish with something you can apply.';
$string['reason2title'] = 'Enjoy Online and distant learning';
$string['reason2text'] = 'See what comes next.';
$string['reason3title'] = 'Get professional certificates';
$string['reason3text'] = 'A cohort stays in one course.';
$string['reason4title'] = 'Learn from the best teachers';
$string['reason4text'] = 'Your place in the course is saved.';
$string['forwardtext'] = 'A course can run from the first activity to the last in one place. Enrolment, progress, and the people teaching it stay with the course.';
$string['instructorintro'] = 'People listed as teachers on a published course appear here.';
$string['instructorsempty'] = 'No teachers are listed on a published course yet.';
$string['allcourses'] = 'All courses';
$string['featuredheading'] = 'Featured courses';
$string['bestseller'] = 'Bestseller';
$string['commercialfields'] = 'Commercial';
$string['fieldprice'] = 'Price';
$string['fieldpricedesc'] = 'Fallback amount only. When the course has a fee enrolment, that cost and currency are shown instead.';
$string['fieldrating'] = 'Rating';
$string['fieldratingdesc'] = 'Average rating from 0 to 5, such as 4.5.';
$string['fieldreviews'] = 'Reviews';
$string['fieldreviewsdesc'] = 'Number of ratings shown next to the stars.';
$string['fieldbestseller'] = 'Bestseller';
$string['fieldbestsellerdesc'] = 'Show the bestseller badge on the course card.';
$string['ratinglabel'] = 'Rated {$a->rating} out of 5';
$string['ratinglabelreviews'] = 'Rated {$a->rating} out of 5 from {$a->reviews} reviews';
$string['browseall'] = 'Browse all courses';
$string['nocoursesyet'] = 'Published courses will appear here.';
$string['categoriesheading'] = 'Browse by category';
$string['partnersheading'] = 'Proudly collaborate with';
$string['howheading'] = 'How it works';
$string['step1title'] = 'Choose a course';
$string['step1text'] = 'Look through the catalogue and open the course that matches what you need to learn.';
$string['step2title'] = 'Enrol and start';
$string['step2text'] = 'Join with the enrolment method the course uses, then open the first activity.';
$string['step3title'] = 'Track your progress';
$string['step3text'] = 'Completion, grades, and the course index show what is done and what is next.';
$string['instructorsheading'] = 'People teaching on NextGen';
$string['benefitsheading'] = 'What you can expect';
$string['benefit1title'] = 'Progress that stays put';
$string['benefit1text'] = 'Come back later and the course still knows which activities you have finished.';
$string['benefit2title'] = 'Activities in one place';
$string['benefit2text'] = 'Lessons, quizzes, assignments, and discussions live in the same course.';
$string['benefit3title'] = 'Room for a cohort';
$string['benefit3text'] = 'Teachers can enrol a group, follow completion, and answer questions in the course.';
$string['benefit4title'] = 'A calmer workspace';
$string['benefit4text'] = 'Navigation, search, and your courses stay in the same header on every page.';
$string['quotesheading'] = 'From the learning community';
$string['ctaheading'] = 'Start with a course that fits';
$string['ctatext'] = 'Browse what is available, or sign in to continue learning.';
$string['ctaguest'] = 'Start learning today';
$string['ctaimagealt'] = 'Students learning together';

$string['loginkicker'] = 'NextGen LMS';
$string['logintitle'] = 'Learn skills. Build your future.';
$string['logindescription'] = 'Sign in to continue your courses, track progress, and pick up where you left off.';
$string['authbrowse'] = 'Browse the course catalogue';
$string['cataloguekicker'] = 'Course catalogue';
$string['cataloguetitle'] = 'Find a course';
$string['cataloguetext'] = 'Search what is published, or open a category and start from there.';
$string['catalogueempty'] = 'No courses are published yet. When one is added, it will appear in this catalogue.';
$string['footercontact'] = 'Contact';
$string['footersocial'] = 'Social';
$string['courseabout'] = 'About the course';
$string['courseoutcomes'] = 'What you\'ll learn';
$string['coursesubjects'] = 'Subjects covered in this course';
$string['courserequirements'] = 'Requirements';
$string['courseincludes'] = 'This course includes';
$string['courseenrol'] = 'Enrol';
$string['coursecontinue'] = 'Continue';
$string['courseactivities'] = '{$a} activities';
$string['coursesections'] = '{$a} sections';
$string['coursestarts'] = 'Starts';
$string['courselanguage'] = 'Language';
$string['courselength'] = 'Length';
$string['courselevel'] = 'Level';
$string['courseeffort'] = 'Effort';
$string['coursecertificate'] = 'Certificate';
$string['coursecontent'] = 'Course content';
$string['taboverview'] = 'Overview';
$string['tabcertificate'] = 'Certificate';
$string['tabinstructors'] = 'Instructors';
$string['tabreviews'] = 'Reviews';
$string['certificateempty'] = 'This course does not list a certificate.';
$string['reviewempty'] = 'No reviews have been added for this course.';
$string['reviewcount'] = '{$a} reviews';
$string['courseinstructorsempty'] = 'No teachers are listed on this course.';
