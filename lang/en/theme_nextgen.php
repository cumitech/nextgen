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
$string['choosereadme'] = 'NextGen LMS is a Boost child theme. It adds a public landing page, course catalogue, and course sales page. Course materials stay behind Moodle enrolment.';
$string['poweredby'] = 'Powered by';
$string['poweredbylink'] = 'CumiSolutions';
$string['privacy:metadata'] = 'The NextGen LMS theme stores course reviews written by enrolled students.';
$string['privacy:metadata:theme_nextgen_review'] = 'Reviews a student has written about a course.';
$string['privacy:metadata:theme_nextgen_review:userid'] = 'The student who wrote the review.';
$string['privacy:metadata:theme_nextgen_review:courseid'] = 'The course the review is about.';
$string['privacy:metadata:theme_nextgen_review:rating'] = 'The star rating given with the review.';
$string['privacy:metadata:theme_nextgen_review:reviewtext'] = 'The review text.';
$string['privacy:metadata:theme_nextgen_review:timecreated'] = 'When the review was first saved.';
$string['privacy:metadata:theme_nextgen_review:timemodified'] = 'When the review was last changed.';

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
$string['customnavdesc'] = 'One link per line, written as Label|/path or Label|https://example.com. These are added to Moodle\'s primary navigation. Home, Dashboard, My courses, About, and Contact stay under the theme.';

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
$string['policyurldesc'] = 'Site path or a full https URL. Leave empty to use the built-in page.';

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
$string['instructorintro'] = 'Every course is led by a teacher you can look up before you enrol. They set the activities, stay with the class, and answer questions in the course.';
$string['instructorpoint1'] = 'See who leads a course before you join';
$string['instructorpoint2'] = 'The same teacher stays with that course';
$string['instructorpoint3'] = 'Questions go to the person who set the work';
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
$string['fieldlevel'] = 'Level';
$string['fieldleveldesc'] = 'Shown on the course page, for example Beginner.';
$string['fieldlength'] = 'Length';
$string['fieldlengthdesc'] = 'Shown on the course page, for example 4 weeks.';
$string['fieldeffort'] = 'Effort';
$string['fieldeffortdesc'] = 'Shown on the course page, for example 8 hours per week.';
$string['fieldcertificate'] = 'Certificate';
$string['fieldcertificatedesc'] = 'Shown on the course page, for example Yes.';
$string['fieldlanguage'] = 'Language';
$string['fieldlanguagedesc'] = 'Shown on the course page. Leave this empty to use the course or site language.';
$string['fieldbestseller'] = 'Bestseller';
$string['fieldbestsellerdesc'] = 'Show the bestseller badge on the course card.';
$string['ratinglabel'] = 'Rated {$a->rating} out of 5';
$string['ratinglabelreview'] = 'Rated {$a->rating} out of 5 from {$a->reviews} review';
$string['ratinglabelreviews'] = 'Rated {$a->rating} out of 5 from {$a->reviews} reviews';
$string['browseall'] = 'Browse all courses';
$string['nocoursesyet'] = 'Published courses will appear here.';
$string['categoriesheading'] = 'Browse by category';
$string['categorycoursecount'] = '{$a} course';
$string['categorycoursecounts'] = '{$a} courses';
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
$string['cataloguetext'] = 'Search the catalogue, choose a category, or look through the courses below.';
$string['catalogueempty'] = 'No courses are published yet. When one is added, it will appear in this catalogue.';
$string['catalogueemptycategory'] = 'No courses are published in this category yet.';
$string['catalogueemptysearch'] = 'No courses match these filters.';
$string['cataloguesearch'] = 'Search courses';
$string['cataloguesearchbutton'] = 'Search';
$string['catalogueadvanced'] = 'Advanced search';
$string['cataloguefilterany'] = 'Any';
$string['catalogueapply'] = 'Apply filters';
$string['catalogueclear'] = 'Clear filters';
$string['footercontact'] = 'Contact';
$string['footersocial'] = 'Social';
$string['courseabout'] = 'About the course';
$string['courseoutcomes'] = 'What you\'ll learn';
$string['coursesubjects'] = 'Subjects covered in this course';
$string['courserequirements'] = 'Requirements';
$string['courseincludes'] = 'This course includes';
$string['courseenrol'] = 'Enrol now';
$string['coursecontinue'] = 'Continue';
$string['courseprice'] = 'Price';
$string['checkouttitle'] = 'Complete enrolment';
$string['checkoutback'] = 'Back to course';
$string['checkoutpaytitle'] = 'Payment';
$string['checkoutaccount'] = 'Signed in as';
$string['checkoutprofiletitle'] = 'Confirm your details';
$string['checkoutprofiletext'] = 'Before you pay, confirm the name and email on your account. Payment uses these details.';
$string['checkoutprofilecustomtext'] = 'Your account still needs a few required profile fields before payment. Complete them, then you will return here.';
$string['checkoutprofilecta'] = 'Continue';
$string['checkoutemaillocked'] = 'This site does not allow email changes here. Use your current account email.';
$string['checkoutemailunchanged'] = 'Your name was saved. Email changes on this site need confirmation from your profile page.';
$string['checkoutfeenote'] = 'Choose how you want to pay to join this course.';
$string['checkoutpaymethods'] = 'Mobile Money and card payments are handled securely through Campay.';
$string['checkouttrust'] = 'Secure payment. You get course access as soon as payment is confirmed.';
$string['checkoutstepcourse'] = 'Course';
$string['checkoutstepaccount'] = 'Account';
$string['checkoutsteppayment'] = 'Payment';
$string['checkoutguesttext'] = 'Sign in to continue enrolment and payment for this course.';
$string['checkoutguestcta'] = 'Sign in to enrol';
$string['checkoutclosedtext'] = 'Enrolment is not available for this course right now.';
$string['checkoutemptytitle'] = 'Next step';
$string['enrolsuccesstitle'] = 'You are enrolled';
$string['enrolsuccesstext'] = 'Payment is complete. Open the course content to begin.';
$string['enrolsuccesscta'] = 'Start learning';

$string['courseactivities'] = '{$a} activities';
$string['courseactivity'] = '1 activity';
$string['coursesection'] = '1 section';
$string['coursesections'] = '{$a} sections';
$string['coursestarts'] = 'Starts';
$string['courselanguage'] = 'Language';
$string['courselength'] = 'Length';
$string['courselevel'] = 'Level';
$string['courseeffort'] = 'Effort';
$string['coursecertificate'] = 'Certificate';
$string['coursecontent'] = 'Course content';
$string['expandall'] = 'Expand all';
$string['collapseall'] = 'Collapse all';
$string['contentsearch'] = 'Search';
$string['contentcounts'] = 'Sections: {$a->sections} • Activities: {$a->activities} • Resources: {$a->resources}';
$string['contentempty'] = 'This course does not list any sections yet.';
$string['requestquote'] = 'Request a quote';
$string['sharecourse'] = 'Share this course';
$string['copylink'] = 'Copy link';
$string['linkcopied'] = 'Link copied';
$string['studentcount'] = '{$a} students';
$string['lastupdated'] = 'Last updated {$a}';
$string['taboverview'] = 'Overview';
$string['tabcertificate'] = 'Certificate';
$string['tabinstructors'] = 'Instructors';
$string['tabreviews'] = 'Reviews';
$string['certificateempty'] = 'This course does not list a certificate.';
$string['certificatesample'] = 'Sample certificate';
$string['certificatetitle'] = 'Certificate';
$string['certificatesubtitle'] = 'of course completion';
$string['certificatecertifies'] = 'This certifies that';
$string['certificatelearner'] = 'Learner\'s name';
$string['certificatecompleted'] = 'has successfully completed the training programme requirement for';
$string['certificatesampledate'] = 'Completion date';
$string['certificatedatelabel'] = 'Date';
$string['certificateinstructorname'] = 'Instructor\'s name';
$string['certificateinstructorlabel'] = 'Instructor';
$string['reviewempty'] = 'No reviews have been added for this course.';
$string['reviewwrite'] = 'Write a review';
$string['reviewrating'] = 'Your rating';
$string['reviewstars'] = '{$a} stars';
$string['reviewtext'] = 'Your review';
$string['reviewsubmit'] = 'Submit review';
$string['reviewupdate'] = 'Update review';
$string['reviewlogin'] = 'Log in to write a review';
$string['reviewsubmitted'] = 'Your review has been saved.';
$string['reviewupdated'] = 'Your review has been updated.';
$string['reviewenrol'] = 'Enrol in this course before leaving a review.';
$string['reviewinvalid'] = 'Choose a rating from 1 to 5 and write a short review.';
$string['reviewcountone'] = '{$a} review';
$string['reviewcount'] = '{$a} reviews';
$string['courserating'] = 'Course rating';
$string['ratingscount'] = '{$a} ratings';
$string['instructorcourses'] = '{$a} courses';
$string['instructorstudents'] = '{$a} students';
$string['fieldratingdist'] = 'Star split';
$string['fieldratingdistdesc'] = 'Percentages for 5, 4, 3, 2 and 1 stars, separated by commas. For example: 67, 22, 0, 11, 0.';
$string['fieldreviewlist'] = 'Written reviews';
$string['fieldreviewlistdesc'] = 'One review per line, written as Name|date|stars|text. Stars is a number from 1 to 5.';
$string['courseinstructorsempty'] = 'No teachers are listed on this course.';

$string['aboutnav'] = 'About';
$string['contactnav'] = 'Contact';
$string['faqnav'] = 'Frequently asked questions';
$string['contactsendfailed'] = 'The message could not be sent. Try again later, or use the contact details in the footer.';

$string['aboutkicker'] = 'NextGen LMS';
$string['abouttitle'] = 'About us';
$string['aboutintro'] = 'A focused place to find a course, learn at your pace, and pick up exactly where you left off.';
$string['aboutsectioncount'] = '3';
$string['aboutsection1title'] = 'One clear catalogue';
$string['aboutsection1text'] = 'Published courses live in one place. Each course keeps its activities, teachers, and your completion record together.';
$string['aboutsection2title'] = 'Learn, then return';
$string['aboutsection2text'] = 'Enrol the way the course allows, open the first activity, and come back later from the same point. Grades and progress stay with the course.';
$string['aboutsection3title'] = 'Ready to begin?';
$string['aboutsection3text'] = 'Browse what is published today, or sign in if you already have a place on a course.';
$string['aboutctalabel'] = 'Browse courses';

$string['contactkicker'] = 'NextGen LMS';
$string['contacttitle'] = 'Contact site support';
$string['contactintro'] = 'Tell us what you need help with. We read every message and reply by email when a response is needed.';
$string['contactsectioncount'] = '1';
$string['contactsection1title'] = 'What to include';
$string['contactsection1text'] = 'Name the course if the question is about one, and say what you already tried. Guests should use an email address they can open.';
$string['contactformtitle'] = 'Send a message';
$string['contactformlead'] = 'Fill in the details below. Required fields are marked.';
$string['contactsubmit'] = 'Send message';
$string['contactfieldname'] = 'Name';
$string['contactfieldemail'] = 'Email address';
$string['contactfieldsubject'] = 'Subject';
$string['contactfieldmessage'] = 'Message';
$string['contactnameplaceholder'] = 'Your full name';
$string['contactemailplaceholder'] = 'you@example.com';
$string['contactsubjectplaceholder'] = 'How can we help?';
$string['contactmessageplaceholder'] = 'Share the course name, what you tried, and what you need next.';
$string['contactchannels'] = 'Reach us directly';
$string['contactchannelslead'] = 'Prefer email or phone? Use the details below.';
$string['contacttipstitle'] = 'A few tips';
$string['contacttip1'] = 'Mention the course title when the question is about one course.';
$string['contacttip2'] = 'Say what you already tried so we can skip the obvious steps.';
$string['contacttip3'] = 'Use an email address you can open — replies go there.';
$string['contactresponse'] = 'We aim to reply within one to two working days.';

$string['privacykicker'] = 'Policies';
$string['privacytitle'] = 'Privacy policy';
$string['privacyintro'] = 'This page explains what this site keeps about you so that courses, sign-in, and support can work.';
$string['privacysectioncount'] = '4';
$string['privacysection1title'] = 'Account and learning records';
$string['privacysection1text'] = 'When you create an account, the site stores the name, email address, and sign-in details Moodle needs. Course enrolment, activity completion, grades, and forum posts stay attached to that account.';
$string['privacysection2title'] = 'Contact messages';
$string['privacysection2text'] = 'The contact form sends your name, email address, and message to the site support user. The theme does not keep its own copy of that message.';
$string['privacysection3title'] = 'Cookies and the session';
$string['privacysection3text'] = 'A session cookie keeps you signed in while you move between pages. Moodle may also store language and editor preferences in your account.';
$string['privacysection4title'] = 'Asking about your information';
$string['privacysection4text'] = 'Signed-in users can review Moodle\'s privacy tools from their profile. Everyone else can use the contact page and ask what the site holds for their email address.';

$string['termskicker'] = 'Policies';
$string['termstitle'] = 'Terms and conditions';
$string['termsintro'] = 'These terms cover use of this learning site, its courses, and the accounts people use to join them.';
$string['termssectioncount'] = '4';
$string['termssection1title'] = 'Using the site';
$string['termssection1text'] = 'You may browse the public catalogue and use the courses you are enrolled in. Do not attempt to open another person\'s account, or to interfere with the site.';
$string['termssection2title'] = 'Accounts';
$string['termssection2text'] = 'Keep your password private. Activity completed while signed in is recorded against your account. Tell the site support user if you lose access.';
$string['termssection3title'] = 'Course materials';
$string['termssection3text'] = 'Lessons, files, and assignments belong to the people who published the course, unless a course page says otherwise. Do not copy them out for another site without permission.';
$string['termssection4title'] = 'Changes';
$string['termssection4text'] = 'Courses can be updated, hidden, or closed by the people teaching them. These terms can be replaced by a newer version of this page.';

$string['faqkicker'] = 'Help';
$string['faqtitle'] = 'Frequently asked questions';
$string['faqintro'] = 'Short answers for finding a course, joining it, and coming back to it later.';
$string['faqsectioncount'] = '5';
$string['faqsection1title'] = 'How do I find a course?';
$string['faqsection1text'] = 'Open Courses in the header, or browse by category. Only published courses are listed.';
$string['faqsection2title'] = 'How do I join a course?';
$string['faqsection2text'] = 'Open the course and use the enrolment method shown there. Some courses are open. Others need a key or a teacher to enrol you.';
$string['faqsection3title'] = 'Where is my progress saved?';
$string['faqsection3text'] = 'Sign in, then open My courses or the course itself. Completion stays with your account, so a later visit shows what is already done.';
$string['faqsection4title'] = 'I forgot my password.';
$string['faqsection4text'] = 'Use Forgot password on the sign-in page and enter the email address on the account. The reset link is sent by the site, not by this theme.';
$string['faqsection5title'] = 'Who do I write to for help?';
$string['faqsection5text'] = 'Use Contact in the header. Include the course name if the question is about one.';

$string['dashboardhello'] = 'Hi, {$a}';
$string['dashboardtoday'] = 'Today is {$a}.';
$string['dashboardlastlogin'] = 'Your last login was {$a} ago.';
$string['dashboardrecent'] = 'Recently accessed course:';
$string['dashboardcontinue'] = 'Continue learning';
$string['dashboardmycourses'] = 'My courses';
$string['dashboardall'] = 'All courses';
$string['dashboardinprogress'] = 'In progress';
$string['dashboardpast'] = 'Past';
$string['dashboardnoprogress'] = 'No completion criteria';
