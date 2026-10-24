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

$string['pluginname'] = 'NextGen Academy';
$string['appname'] = 'NextGen Academy';
$string['configtitle'] = 'NextGen Academy';
$string['choosereadme'] = 'NextGen Academy is a Boost child theme. It adds a public landing page, course catalogue, and course sales page. Course materials stay behind Moodle enrolment.';
$string['poweredby'] = 'Powered by';
$string['poweredbylink'] = 'CumiSolutions';
$string['privacy:metadata'] = 'The NextGen Academy theme stores course reviews written by enrolled students.';
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
$string['logodesc'] = 'Optional upload. The header and login page use the included NextGen Academy leaf mark.';
$string['favicon'] = 'Favicon';
$string['favicondesc'] = 'Optional upload. The browser icon uses the same NextGen Academy leaf as the header.';

$string['primarycolor'] = 'Primary colour';
$string['primarycolordesc'] = 'Ink colour for buttons, links, and navigation. The leaf mark stays green on its own.';
$string['secondarycolor'] = 'Secondary colour';
$string['secondarycolordesc'] = 'Deep colour for the footer, the login panel, and large banners.';
$string['accentcolor'] = 'Accent colour';
$string['accentcolordesc'] = 'Highlight colour for step markers, the current checkout step, and dashboard charts. Text on it is dark green, so keep it light.';

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
$string['contactemaildesc'] = 'Shown in the footer and on the About and Contact pages. When empty, the footer hides it and the pages use the site support email.';
$string['contactphone'] = 'Contact phone';
$string['contactphonedesc'] = 'Shown in the footer and on the Contact and About pages. Leave empty to hide it.';
$string['contactaddress'] = 'Office addresses';
$string['contactaddressdesc'] = 'One office per line, written as Label|Address, for example Main office|Opposite Tradex Nomayous, Yaoundé. Shown in the footer and on the Contact and About pages.';

$string['facebook'] = 'Facebook';
$string['instagram'] = 'Instagram';
$string['linkedin'] = 'LinkedIn';
$string['youtube'] = 'YouTube';
$string['x'] = 'X';
$string['tiktok'] = 'TikTok';
$string['socialdesc'] = 'Full https URL. Leave empty to hide this network.';
$string['socialfollowon'] = 'Follow NextGen Academy on {$a} (opens in a new tab)';
$string['socialfooterlead'] = 'Courses, events, and tips for entrepreneurs.';
$string['socialfollowtitle'] = 'Follow us';
$string['socialfollowlead'] = 'Course news and updates from Next Gen Entrepreneurship Development Ltd.';
$string['aboutsocialtitle'] = 'Follow us';
$string['aboutsociallead'] = 'Course news and updates from Next Gen Entrepreneurship Development Ltd.';

$string['copyright'] = 'Copyright line';
$string['copyrightdesc'] = 'Leave empty to use the year and site name.';
$string['copyrightdefault'] = '© {$a->year} {$a->name}';
$string['privacyurl'] = 'Privacy policy URL';
$string['termsurl'] = 'Terms and conditions URL';
$string['policyurldesc'] = 'Site path or a full https URL. Leave empty to use the built-in page.';

$string['loginbackgroundimage'] = 'Login background image';
$string['loginbackgroundimagedesc'] = 'Photograph for the card beside the login and signup forms. Use a landscape or portrait image at least 1200 pixels tall. When empty, the theme\'s learners photograph is used.';

$string['customcss'] = 'Custom CSS';
$string['customcssdesc'] = 'CSS added after the theme styles. Changes apply after caches are purged.';

$string['frontpagesettings'] = 'Front page';
$string['herotitle'] = 'Hero headline';
$string['herotitle_desc'] = 'Main headline on the site home page.';
$string['herotitledefault'] = 'Turn your business ideas into real opportunities';
$string['herotext'] = 'Hero description';
$string['herotext_desc'] = 'Supporting sentence under the headline.';
$string['herotextdefault'] = 'Practical entrepreneurship courses, expert guidance, and learning resources to help African and international students, aspiring entrepreneurs, and business owners start, manage, and grow successful ventures.';
$string['heroprimary'] = 'Primary button';
$string['herosecondary'] = 'Secondary button';
$string['herosecondarydesc'] = 'Guests see Learn now, which opens the login form. After sign-in the same button reads Continue learning and opens the dashboard.';
$string['herobuttondesc'] = 'One line, written as Label|/path. Use a #anchor such as #why-nextgen to jump down the page.';
$string['heroprimarydefault'] = 'Explore courses|/course/index.php';
$string['herosecondarydefault'] = 'Learn now|/login/index.php';
$string['herostartnow'] = 'Learn now';
$string['testimonials'] = 'Testimonials';
$string['testimonialsdesc'] = 'One quote per line, written as Quote|Attribution. Leave empty to hide the section.';
$string['testimonialsdefault'] = "The practice work was specific enough to use in my job the same week.|Programme participant\nI could see what I had finished and what was still ahead.|Learner\nThe course structure made it easy to teach a cohort without losing people.|Course lead";

$string['viewcourse'] = 'View course';
$string['platformstats'] = 'Platform figures';
$string['statcourses'] = 'Online courses';
$string['statinstructors'] = 'Instructors';
$string['statcertification'] = 'Certification';
$string['statmembership'] = 'Membership';
$string['statcountplus'] = '{$a}+';
$string['statpercent'] = '{$a}%';
$string['coursesavailable'] = 'Courses';
$string['categoriesavailable'] = 'Categories';
$string['whyheading'] = 'Why learn with NextGen Academy';
$string['reason1title'] = 'Take time to learn new skills';
$string['reason1text'] = 'Finish with something you can apply.';
$string['reason2title'] = 'Enjoy Online and distant learning';
$string['reason2text'] = 'See what comes next.';
$string['reason3title'] = 'Get professional certificates';
$string['reason3text'] = 'A cohort stays in one course.';
$string['reason4title'] = 'Learn from the best teachers';
$string['reason4text'] = 'Your place in the course is saved.';
$string['forwardtext'] = '{$a}, from agripreneurship and techpreneurship to green, cultural, and sports entrepreneurship, with guidance and mentorship to help you start or grow a business.';
$string['cataloguesize'] = '{$a} practical courses';
$string['cataloguesizeone'] = 'one practical course';
$string['cataloguesizenone'] = 'practical courses';
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
$string['fieldpricedesc'] = 'Fallback amount only. A number is shown in the site fee currency. When the course has a fee enrolment, that cost and currency are shown instead.';
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
$string['partnersheading'] = 'We proudly collaborate with';
$string['partnerslead'] = 'Government, training, and enterprise partners who support this work in Cameroon.';
$string['partnerminefop'] = 'Ministry of Employment and Vocational Training';
$string['partnerminpmeesa'] = 'Ministry of Small and Medium-Sized Enterprises';
$string['partnerapme'] = 'Agence de Promotion des PME';
$string['partneresrc'] = 'Entrepreneurship and Social Research Centre-Cameroon';
$string['partnercga'] = 'Centre de Gestion Agréé (CGA)';
$string['partneristp'] = 'In-Service Training Programme (ISTP) of the CBC-PCC-EZE- Cameroon';
$string['partneristpshort'] = 'ISTP';
$string['partneristpsub'] = 'CBC-PCC-EZE Cameroon';
$string['howheading'] = 'How it works';
$string['step1title'] = 'Choose a course';
$string['step1text'] = 'Look through the catalogue and open the course that matches what you need to learn.';
$string['step2title'] = 'Enrol and start';
$string['step2text'] = 'Join with the enrolment method the course uses, then open the first activity.';
$string['step3title'] = 'Track your progress';
$string['step3text'] = 'Completion, grades, and the course index show what is done and what is next.';
$string['instructorsheading'] = 'People teaching on NextGen Academy';
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

$string['loginkicker'] = 'NextGen Academy';
$string['logintitle'] = '';
$string['logindescription'] = 'Practical entrepreneurship training for youths, students, and business practitioners.';
$string['authbrowse'] = 'Browse the course catalogue';
$string['authwelcome'] = 'Welcome to {$a}';
$string['authswitchlabel'] = 'Log in or create an account';
$string['authswitchlogin'] = 'Log in';
$string['authswitchregister'] = 'Register';
$string['authloginheading'] = 'Log in to your account';
$string['authloginlead'] = 'Continue your courses, track your progress, and pick up where you left off.';
$string['authsignupheading'] = 'Create your account';
$string['authsignuplead'] = 'Join NextGen Academy to enrol in practical entrepreneurship courses and track your progress.';
$string['cataloguekicker'] = 'Course catalogue';
$string['cataloguetitle'] = 'Find a course';
$string['cataloguetext'] = 'Search the catalogue, choose a category, or look through the courses below.';
$string['catalogueempty'] = 'No courses are published yet. When one is added, it will appear in this catalogue.';
$string['catalogueemptycategory'] = 'No courses are published in this category yet.';
$string['catalogueemptysearch'] = 'No courses match these filters.';
$string['cataloguesearch'] = 'Search courses';
$string['cataloguesearchbutton'] = 'Search';
$string['catalogueadvanced'] = 'Advanced search';
$string['catalogueadvancedcount'] = 'Advanced search ({$a})';
$string['cataloguefilterany'] = 'Any';
$string['catalogueprice'] = 'Price';
$string['cataloguefee'] = 'Fee';
$string['cataloguefree'] = 'Free';
$string['cataloguepaid'] = 'Paid';
$string['catalogueapply'] = 'Apply filters';
$string['catalogueclear'] = 'Clear filters';
$string['catalogueclearone'] = 'Remove filter';
$string['catalogueresult'] = '1 course';
$string['catalogueresults'] = '{$a} courses';
$string['footercontact'] = 'Contact';
$string['footersocial'] = 'Follow us';
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
$string['checkoutxafcharge'] = 'Amount charged in CFA francs (rounded up):';
$string['checkoutpaymethods'] = 'Pay securely with Mobile Money (MTN or Orange) or a card (Visa or Mastercard).';
$string['checkouttrust'] = 'Secure payment. You get course access as soon as payment is confirmed.';
$string['securepayment'] = 'Secure payment';
$string['paymobile'] = 'Mobile Money (MTN and Orange)';
$string['paycards'] = 'Cards (Visa and Mastercard)';
$string['paymobiletitle'] = 'Mobile Money';
$string['paymobilebrands'] = 'MTN and Orange';
$string['paycardstitle'] = 'Cards';
$string['paycardsbrands'] = 'Visa and Mastercard';
$string['securepaymentnote'] = 'You can open the course as soon as the payment is confirmed.';
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
$string['courseends'] = 'Ends';
$string['courselanguage'] = 'Language';
$string['courselength'] = 'Length';
$string['courselevel'] = 'Level';
$string['courseeffort'] = 'Effort';
$string['coursecertificate'] = 'Certificate';
$string['coursecategory'] = 'Category';
$string['coursestructure'] = 'Structure';
$string['courseformat_topics'] = 'Topics';
$string['courseformat_weeks'] = 'Weekly';
$string['courseformat_singleactivity'] = 'Single activity';
$string['courseformat_social'] = 'Social';
$string['coursesectionslabel'] = 'Sections';
$string['courseactivitieslabel'] = 'Activities';
$string['courseresourceslabel'] = 'Resources';
$string['coursecompletion'] = 'Completion';
$string['coursecompletiontracked'] = 'Tracked';
$string['coursedetails'] = 'Course details';
$string['certificateincluded'] = 'Certificate of completion';
$string['coursecontent'] = 'Course content';
$string['expandall'] = 'Expand all';
$string['collapseall'] = 'Collapse all';
$string['showless'] = 'Show less';
$string['showmore'] = 'Show more';
$string['contentsearch'] = 'Search';
$string['contentcounts'] = 'Sections: {$a->sections} • Activities: {$a->activities} • Resources: {$a->resources}';
$string['contentempty'] = 'This course does not list any sections yet.';
$string['requestquote'] = 'Request a quote';
$string['sharecourse'] = 'Share this course';
$string['copylink'] = 'Copy link';
$string['linkcopied'] = 'Link copied';
$string['studentcount'] = '{$a} students';
$string['studentcountone'] = '1 student';
$string['lastupdated'] = 'Last updated {$a}';
$string['taboverview'] = 'Overview';
$string['tabcertificate'] = 'Certificate';
$string['tabinstructors'] = 'Instructors';
$string['tabreviews'] = 'Reviews';
$string['certificateempty'] = 'This course does not include a certificate.';
$string['certificatenone'] = 'You can still finish the activities in this course. A certificate is not awarded when you complete it.';
$string['certificatelead'] = 'NextGen Academy awards a certificate of completion for this course. It is included with your enrolment, and you receive it after you complete the required activities.';
$string['certificatefactname'] = 'It shows your name, this course, and the date you finish.';
$string['certificatefactfee'] = 'There is no separate fee for the certificate.';
$string['certificatefactsample'] = 'The preview is a sample. It is not the certificate issued to you.';
$string['certificatenonefinish'] = 'Completing the activities does not create a certificate.';
$string['certificatenonefee'] = 'A course fee does not add a certificate to this course.';
$string['certificatesample'] = 'Sample certificate';
$string['certificatesamplemark'] = 'Sample';
$string['certificatesamplenote'] = 'This is a sample of the certificate awarded when you complete this course.';
$string['certificatetitle'] = 'Certificate of completion';
$string['certificatesubtitle'] = 'Awarded by';
$string['certificatecertifies'] = 'This is to certify that';
$string['certificatelearner'] = 'Learner\'s name';
$string['certificatecompleted'] = 'has successfully completed';
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
$string['reviewincomplete'] = 'Finish every module in this course before leaving a review.';
$string['reviewfinished'] = 'You have finished this course. Tell other learners what you thought.';
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

$string['aboutkicker'] = 'NextGen Academy';
$string['abouttitle'] = 'About us';
$string['aboutintro'] = 'Practical online entrepreneurship training for youths, students, and business practitioners in Cameroon, across Africa, and internationally.';
$string['aboutsectioncount'] = '0';
$string['aboutcontactlabel'] = 'Contact us';
$string['aboutwhotitle'] = 'Who we are';
$string['aboutwhotext1'] = 'Next Gen Entrepreneurship Development Ltd is a vocational training centre dedicated to promoting entrepreneurship knowledge, skills, and opportunities among youths, students, and business practitioners across Cameroon, Africa, and internationally. This online professional and vocational entrepreneurship training is one of its projects.';
$string['aboutwhotext2'] = 'The training makes entrepreneurship knowledge and skills accessible and practical. Alongside {$a} practical courses, learners receive guidance, mentorship, and technical skills to help them start or grow their own businesses.';
$string['aboutwhotext2one'] = 'The training makes entrepreneurship knowledge and skills accessible and practical. Alongside one practical course, learners receive guidance, mentorship, and technical skills to help them start or grow their own businesses.';
$string['aboutwhotext2none'] = 'The training makes entrepreneurship knowledge and skills accessible and practical. Learners receive guidance, mentorship, and technical skills to help them start or grow their own businesses.';
$string['aboutteachtitle'] = 'What we teach';
$string['aboutteachtext'] = '{$a}, covering:';
$string['aboutareas'] = "Agripreneurship\nTechpreneurship\nGreen entrepreneurship\nCultural entrepreneurship\nSports entrepreneurship\nTeacherpreneurship\nKingdom entrepreneurship\nIslamic entrepreneurship\nWar entrepreneurship";
$string['aboutareasmore'] = 'And many more associated courses.';
$string['aboutgaintitle'] = 'What you will gain';
$string['aboutoutcomestitle'] = 'By the end of the training, you should:';
$string['aboutoutcome1'] = 'Acquire knowledge and skills in different types of entrepreneurship.';
$string['aboutoutcome2'] = 'Be motivated to exploit business opportunities in different sectors and domains.';
$string['aboutoutcome3'] = 'Acquire an entrepreneurship culture in different sectors and domains.';
$string['aboutvisiontitle'] = 'Our vision';
$string['aboutvisionquote'] = 'To become Africa\'s leading digital hub for entrepreneurial transformation: a continent where every young person, regardless of location or background, possesses the skills, confidence, and opportunity to create enterprises that drive sustainable economic growth, reduce unemployment, and unlock the full potential of Africa\'s youth for the benefit of Cameroon, Central Africa, and the world.';
$string['aboutvisionlisttitle'] = 'The future we are working toward';
$string['aboutvisionpoints'] = "Every young Cameroonian who dreams of starting a business has access to the training, mentorship, and support to turn that dream into reality.\nOnline learning is recognised as a powerful engine of economic empowerment, reaching young people in the most remote villages and the busiest cities alike.\nEntrepreneurship is a practical, lived capability, not just an academic subject: a generation that sees challenges as opportunities and creates solutions where others see only problems.\nCameroon becomes a model for Africa, turning its demographic challenge into a demographic dividend through entrepreneurial energy.\nAfrica's youth are seen not as a problem to be solved, but as the solution to the continent's most pressing challenges: job creation, innovation, and sustainable development.\nA new generation of African entrepreneurs builds enterprises that compete globally, create jobs locally, and help solve some of humanity's most complex challenges.";
$string['aboutvisionclose'] = 'This vision is ambitious because the problem is severe. We do not aspire to incremental change. We aspire to transformation.';
$string['aboutmissiontitle'] = 'Our mission';
$string['aboutmissionquote'] = 'To equip young Cameroonians and Africans with world-class, practical, and market-relevant entrepreneurship and digital skills through an accessible, bilingual, and affordable online platform that combines structured training, mentorship, community support, and connections to opportunities, so that they can create their own economic futures, generate employment, and drive sustainable development across the continent.';
$string['aboutcommitmentstitle'] = 'Our commitments';
$string['aboutcommitment1title'] = 'Accessibility';
$string['aboutcommitment1text'] = 'Training that reaches young people wherever they are: in cities and villages, in English and French, on smartphones and computers, online and offline.';
$string['aboutcommitment2title'] = 'Relevance';
$string['aboutcommitment2text'] = 'A curriculum built with employers, entrepreneurs, and industry experts, and updated as the digital economy changes.';
$string['aboutcommitment3title'] = 'Practicality';
$string['aboutcommitment3text'] = 'Skills you can apply immediately: business planning, financial literacy, digital marketing, operations management, and technology adoption.';
$string['aboutcommitment4title'] = 'Support';
$string['aboutcommitment4text'] = 'Mentorship, community, and ongoing guidance after training, through the challenges of starting and growing a business.';
$string['aboutcommitment5title'] = 'Affordability';
$string['aboutcommitment5text'] = 'Programmes at low or no cost, supported by partnerships, sponsorship, and a sustainable business model.';
$string['aboutcommitment6title'] = 'Impact';
$string['aboutcommitment6text'] = 'Success measured by businesses launched, jobs created, and lives transformed, not by certificates issued.';
$string['aboutcommitment7title'] = 'Excellence';
$string['aboutcommitment7text'] = 'The highest standards of content quality, learner experience, and institutional integrity.';
$string['aboutcommitment8title'] = 'Collaboration';
$string['aboutcommitment8text'] = 'Working with government, the private sector, civil society, and international partners to amplify our impact.';
$string['aboutimpacttitle'] = 'Why this work matters';
$string['aboutimpactlead'] = 'Each part of our vision and mission responds to a challenge young people in Cameroon face today.';
$string['aboutimpactchallenge'] = 'The challenge';
$string['aboutimpactresponse'] = 'Our response';
$string['aboutimpacttagvision'] = 'Vision';
$string['aboutimpacttagmission'] = 'Mission';
$string['aboutimpact1challenge'] = '36% of young Cameroonians are unemployed.';
$string['aboutimpact1vision'] = 'A continent where every young person can create their own economic future.';
$string['aboutimpact1mission'] = 'Equip youth with entrepreneurship skills to create self-employment and become business owners and investors.';
$string['aboutimpact2challenge'] = 'The top barriers are gaps in skills and training.';
$string['aboutimpact2mission'] = 'Deliver practical, market-relevant training that closes these specific gaps.';
$string['aboutimpact3challenge'] = '60% want to start a business but lack the capabilities.';
$string['aboutimpact3mission'] = 'Provide the skills, mentorship, and support to turn aspiration into action.';
$string['aboutimpact4challenge'] = '61% are considering emigration.';
$string['aboutimpact4vision'] = 'Make staying and building at home a viable, attractive, and rewarding option.';
$string['aboutimpact5challenge'] = 'The education system produces graduates, but not job creators.';
$string['aboutimpact5mission'] = 'Build the entrepreneurial mindset and capabilities that formal education does not provide.';
$string['aboutimpact6challenge'] = 'The digital economy creates new opportunities, but few have the skills to use them.';
$string['aboutimpact6mission'] = 'Build digital literacy and technology adoption skills for the modern economy.';
$string['aboutimpact7challenge'] = 'Physical training is limited by geography and cost.';
$string['aboutimpact7mission'] = 'Online, accessible, and affordable training that reaches learners wherever they are.';
$string['aboutteamtitle'] = 'Our team';
$string['aboutteamlink'] = 'Meet the team';
$string['aboutfounderlabel'] = 'Founder';
$string['aboutfoundername'] = 'Professor Vukenkeng Andrew Wujung';
$string['aboutfoundertitle'] = 'Professor of Economics and Entrepreneurship';
$string['aboutfounderhighlights'] = "Ph.D. in Economic Sciences, University of Yaoundé II, Soa\n30+ years in secondary and higher education\n60+ scholarly publications, books, and chapters";
$string['aboutfounderbio1'] = 'Professor Wujung is a Cameroonian Professor of Economics and Entrepreneurship with extensive experience in higher education, teaching, research, academic administration, and entrepreneurship development. His interests centre on development economics, entrepreneurship, economic growth and development, and the environmental concerns of development.';
$string['aboutfounderbio2'] = 'His research covers entrepreneurship, economic growth, development, environmental issues, poverty, and governance. Recent studies address entrepreneurship education, financial sustainability in state universities, the digital transformation of SMEs, and entrepreneurship orientation and SME performance.';
$string['aboutfounderbeyondtitle'] = 'Beyond academia';
$string['aboutfounderbeyond'] = 'He contributes to entrepreneurship training, capacity building, research collaboration, community development, and professional service. He is economic adviser to the Kedjom Agricultural Development Cooperative and Chairman of the Board of WAMVA Ventures Ltd.';
$string['aboutfounderpositionstitle'] = 'Positions held';
$string['aboutfounderpositions'] = "Technical Adviser to the Rector, University of Bertoua (since August 2025)\nInspector of Services, Ministry of Higher Education (MINESUP)\nDeputy Director, Higher Institute of Commerce and Management\nHead of the Department of Economics and Head of the Accounting Division, University of Bamenda\nLecturer and Researcher, University of Buea";
$string['aboutparticipantstitle'] = 'Key participants';
$string['aboutparticipants'] = "Professor Vukenkeng Andrew Wujung\nDr. Akumbom Paul\nDr. Kiambom Tracey\nDr. Forbe Hodu\nApostle Nfor Kilian\nNtsinu Raheal Viwin\nAnembom Francine\nBuemembom Favour Wujung\nAyeah Godlove Akoni\nWulneghewimbom Anguombom Cedrick";
$string['aboutparticipantsmore'] = 'And other contributors who support our learners.';
$string['teamnav'] = 'Team';
$string['teamkicker'] = 'NextGen Academy';
$string['teamtitle'] = 'Our team';
$string['teamintro'] = 'The people who lead, teach, and build Next Gen Entrepreneurship Development Ltd.';
$string['teamsectioncount'] = '0';
$string['teammemberfavourname'] = 'Buemembom Favour Wujung';
$string['teammemberfavourrole'] = 'Marketing and communications expert, digital marketing strategist, and entrepreneur';
$string['teammemberfavourbio'] = 'Buemembom Favour Wujung is a passionate marketing and communications professional and entrepreneur, driven by a strong desire to inspire growth, create opportunities, and contribute to Africa\'s entrepreneurial development. With a keen interest in marketing, digital innovation, and business development, he embodies a forward-thinking mindset and a commitment to turning ideas into meaningful impact.' . "\n\n" .
    'At NextGen Entrepreneurship Development Ltd, Favour plays a vital role in shaping the company\'s marketing direction, strengthening its brand identity, and expanding its digital presence. He oversees the company\'s social media campaigns, contributes to strategic marketing initiatives, and helps communicate NextGen Academy\'s vision to audiences in Cameroon, across Africa, and around the world. Alongside his work at NextGen Academy, he is also a member of the Young African Leaders Initiative (YALI) Network.';
$string['teammemberponmumaname'] = 'Ponmuma Gabin Merveille Petit';
$string['teammemberponmumarole'] = 'Accounting professional, educator, and entrepreneur';
$string['teammemberponmumabio'] = 'Ponmuma Gabin Merveille Petit is an accounting professional, educator, project writer, and young entrepreneur. He serves as Programme Coordinator at ESRC-Cameroon, Head of the School of Business & Finance at PHIPT, and Director of the School of Business and Management Sciences at CIUIC. He is passionate about education, research, entrepreneurship, and helping students develop practical skills. He is hardworking, active, well-organized, and able to work under pressure while managing multiple responsibilities effectively.';
$string['teammembermbalaname'] = 'Mbala Axel';
$string['teammembermbalarole'] = 'Juriste et archiviste';
$string['teammembermbalabio'] = 'Juriste (Master II droit privé) et archiviste cadre contractuel dans l\'administration publique depuis plus de douze ans. Formé en rédaction administrative, techniques archivistiques et leadership. Français-anglais.';
$string['teammemberayeahname'] = 'Ayeah Godlove Akoni';
$string['teammemberayeahrole'] = 'IT systems and software engineer';
$string['teammemberayeahbio'] = 'Mr Ayeah Godlove Akoni is a Cameroonian IT professional with a background in Computer Network and System Maintenance and practical experience spanning software engineering, IT systems support, infrastructure management, web technologies, database administration and technical problem-solving.' . "\n\n" .
    'He combines software development capabilities with an understanding of the computer systems, networks, servers and databases on which digital applications depend. His technical background enables him to contribute to the development and maintenance of software solutions while also supporting system configuration, deployment, troubleshooting and the reliable operation of IT environments.' . "\n\n" .
    'His software engineering experience includes the development of web applications, backend services, REST APIs, database-driven platforms and business information systems. He has worked with technologies including JavaScript, TypeScript, Node.js, Express, PHP, Laravel, Python, C# and .NET, alongside frontend frameworks such as React and Next.js. His database experience includes MySQL, PostgreSQL and MongoDB.' . "\n\n" .
    'His infrastructure-related competencies include Linux and Windows environments, server deployment and maintenance, web server configuration, DNS, SSL/TLS certificates, remote server administration, application hosting, access control, backups and troubleshooting. His academic background in computer networking and systems maintenance further supports his work in connectivity troubleshooting, computer hardware and software maintenance, operating systems, network configuration and user technical support.' . "\n\n" .
    'In addition to his technical implementation experience, Mr Akoni has worked in tutoring and mentoring, helping learners understand web development, backend programming, databases, APIs and software development practices. This experience strengthens his ability to communicate technical concepts, support users, explain solutions and work collaboratively.' . "\n\n" .
    'He is positioned to contribute to organisations that require a versatile IT professional capable of working across software development, IT operations, systems support, networking and digital infrastructure. His combination of academic training and practical software and systems experience is particularly relevant to IT departments, technology service providers, enterprise IT environments and telecommunications organisations.';
$string['teammemberetamename'] = 'Etame Vivien Millan';
$string['teammemberetamerole'] = 'Technico-commercial';
$string['teammemberetamebio'] = 'Technico-commercial spécialisé en assistance commerciale digitale, fort de plus de huit ans d\'expérience dans la vente/closing en ligne, le suivi client (online et offline) et en campagnes publicitaires digitales.' . "\n\n" .
    'Français-Anglais.';
$string['teammemberderickname'] = 'Dr. Derick Yong';
$string['teammemberderickrole'] = 'Communication, storytelling, and creative arts';
$string['teammemberderickbio'] = 'Dr. Derick Yong holds a Ph.D. in English Literature, specialising in novel-to-film adaptation. His work covers professional English, communication, creative writing, screenwriting, film production, drama, and performance. He combines academic scholarship with practical filmmaking, and trains people in communication, storytelling, screenwriting, and the creative arts.';
$string['teammembernayahname'] = 'Dr. Nayah Gazelle Abang';
$string['teammembernayahrole'] = 'Senior lecturer, trainer, and social entrepreneur';
$string['teammembernayahbio'] = 'Dr. Nayah Gazelle Abang is a Senior Lecturer in Public Administration and Public Policy at the University of Buea, Cameroon, and a visiting lecturer at Assam Don Bosco University, India. She is the founder of the Voice of Hope Foundation (VOHOF) and Cameroon Ambassador for World Speech Day.' . "\n\n" .
    'She equips people with knowledge and practical skills in entrepreneurship, public policy, public speaking, and community leadership.';
$string['teammemberpetername'] = 'Dr. Peter Abuengmoh';
$string['teammemberpeterrole'] = 'Entrepreneur, food technologist, and grant writer';
$string['teammemberpeterbio'] = 'Dr. Peter Abuengmoh is an entrepreneur, food technologist, and grant writer. He is the lead founder of LEPEGDEMT FOODS Ltd, a common initiative group in Bamenda, where he combines food processing with business growth and community impact.' . "\n\n" .
    'He has won more than four grants for himself and his enterprise. He has also guided and facilitated nine young women to secure the Tony Elumelu Foundation entrepreneurship grant of $5,000, opening doors for women-led businesses.' . "\n\n" .
    'He is Head of the School of Home Economics, Bakery and Food Processing Technology at Prestige Higher Institute of Professional Training, Bamenda, where he trains and mentors the next generation of food professionals.';
$string['teammemberforbename'] = 'Dr. Forbe Hodu';
$string['teammemberforberole'] = 'Organisational sciences';
$string['teammemberforbebio'] = 'Dr. Forbe Hodu is an expert in organisational sciences.';
$string['teammemberfrancinename'] = 'Anembom Francine';
$string['teammemberfrancinerole'] = 'Fashion business entrepreneur and project manager';
$string['teammemberfrancinebio'] = 'Anembom Francine is a fashion business entrepreneur with an MBA in project management. She brings an entrepreneurial approach to business development, with responsibility, integrity, teamwork, and a commitment to continuous growth. Her motto is to stay committed to excellence, driven by growth, and dedicated to results.' . "\n\n" .
    'Her work covers business development and entrepreneurship, project planning and management, communication and teamwork, organisation and administration, leadership, and problem-solving. She is interested in organisational development, effective management, and the achievement of an organisation\'s objectives.';
$string['teammemberkiambomname'] = 'Dr. Kiambom Tracey';
$string['teammemberkiambomrole'] = 'Senior agricultural consultant and researcher';
$string['teammemberkiambombio'] = 'Dr. Kiambom Tracey is a senior agricultural consultant and researcher in Yaoundé, specialising in animal production and biotechnology. She is an award-winning agricultural scientist with more than five years of experience in agriculture, livelihoods, and food security, working to reduce food insecurity among vulnerable people.' . "\n\n" .
    'She keeps a high standard of work, analyses operational challenges, and presents sustainable solutions.';
$string['teammemberemmanuelname'] = 'Emmanuel Mbunwe Wepngong';
$string['teammemberemmanuelrole'] = 'Teacher trainer';
$string['teammemberemmanuelbio'] = 'Emmanuel Mbunwe Wepngong is an evidence-based teacher trainer with 15 years of developing and delivering professional development, from early childhood through primary, secondary, and high school. He holds a master\'s degree in Educational Quality for developing countries from Otto Friedrich Universität Bamberg, Germany.' . "\n\n" .
    'He taught History and Economics from 1998 to 2009 and has trained teachers since 2009. His work includes workshops on teaching, learning, and teaching quality criteria, training materials for teachers across school levels, classroom follow-ups, and lesson observations with feedback aimed at improving teacher effectiveness. He served as Principal Investigator 2 on the International Development Research Centre STEM Teacher and Student Education for Primary Schools (IDRC-STEM/STEPS) project in Cameroon, and as a panelist and facilitator at IMPEQ conferences in Cameroon and Rwanda.' . "\n\n" .
    'He works to help educators reach their potential and create strong learning experiences. His working strengths include organisation, adaptability, communication, problem-solving, and leadership.';
$string['teammemberndzembantehname'] = 'Dr. Ndzembanteh Aboubakary Nulambeh';
$string['teammemberndzembantehrole'] = 'Researcher and senior lecturer';
$string['teammemberndzembantehbio'] = 'Dr. Ndzembanteh Aboubakary Nulambeh is a researcher and Senior Lecturer at the University of Bamenda, with more than eight years in teaching, applied research, and academic work. He holds a Ph.D. in International Economics from Bursa Uludag University, Türkiye.' . "\n\n" .
    'His work covers environmental economics, Islamic economics and finance, applied econometrics, supply chain management, and logistics. He has authored more than 20 publications, with more than 140 Google Scholar citations, on sustainability, economic development, and evidence-based policy. Alongside teaching and postgraduate supervision, he takes part in interdisciplinary research, international academic collaboration, and sustainability initiatives. His interest is practical, research-driven solutions for sustainable development, effective public policy, and inclusive economic growth in sub-Saharan Africa.';
$string['teammemberakumbomname'] = 'Dr. Akumbom Paul';
$string['teammemberakumbomrole'] = 'Development economist and agro-entrepreneur';
$string['teammemberakumbombio'] = 'Dr. Akumbom Paul is a Cameroonian development economist and agro-entrepreneur with a Ph.D. in Agribusiness Project Management from the University of Bamenda. He is a lecturer at the Higher Institute of Commerce and Management (HICM), University of Bamenda, with expertise in entrepreneurship, agribusiness development, project management, and quantitative and qualitative research methodologies.';
$string['teammembersigiminame'] = 'Dr. Sigimi Fidelis';
$string['teammembersigimirole'] = 'Lecturer in marketing';
$string['teammembersigimibio'] = 'Dr. Sigimi Fidelis is a lecturer in the Department of Marketing at the Higher Institute of Commerce and Management. His research and specialty are consumer behaviour and marketing strategy.';
$string['teammemberawemuname'] = 'Dr. Awemu Jonah';
$string['teammemberawemurole'] = 'Economist, educator, and researcher';
$string['teammemberawemubio'] = 'Awemu Jonah Wayih (PhD) is an economist, educator and researcher, with a strong academic background and diverse professional competencies spanning teaching, research, creative writing, and information and communication technology.' . "\n\n" .
    'Academically, he holds a Ph.D. in Economics (2025) and a Master of Science (M.Sc.) in Economics (2017), both from the University of Bamenda, Cameroon. He also obtained a Bachelor of Science (B.Sc.) in Economics (2009) from the University of Dschang and a DIPES I in Economics (2005) from the Higher Teachers\' Training College Annex, Bambili.' . "\n\n" .
    'His secondary education culminated in the award of the GCE Advanced Level Certificate from Government Bilingual High School Bamenda in 2001 and the GCE Ordinary Level Certificate from Cameroon College of Arts, Science and Technology (CCAST), Bambili, in 1999.' . "\n\n" .
    'His research interests centre on economic growth, public policy, renewable energy, economic complexity, and sustainable development. He has contributed to scholarly publications, including Government Spending and Economic Growth, Keynes or Wagner for Cameroon? ARDL/T-Y-VAR Approach (2022), which examined the relationship between public expenditure and economic growth in Cameroon, and Implications of Economic Complexity for Renewable Energy Consumption and Sustainable Development: Evidence from Sub-Saharan Africa (2024). The latter established the significant contribution of renewable energy consumption to sustainable development and highlighted the mediating role of economic complexity.' . "\n\n" .
    'Beyond academic research, he possesses strong competencies in teaching and lecturing, creative writing, ICT, logical analysis, and quantitative management. His multidisciplinary abilities reflect a commitment to intellectual development, creativity, and effective knowledge transmission.' . "\n\n" .
    'He is passionate about knowledge acquisition, sharing and practical application, educational games, humour, learning, and sightseeing. He demonstrates advanced proficiency in English and good working knowledge of French.' . "\n\n" .
    'His academic journey, research contributions, and diverse professional skills reflect a sustained commitment to educational excellence, intellectual advancement, creativity, and the application of knowledge to societal development in a sustainable manner.';
$string['teammemberabetyname'] = 'Dr. Abety Paul';
$string['teammemberabetyrole'] = 'Development economist and national pedagogic inspector';
$string['teammemberabetybio'] = 'Dr. Abety Paul is a PhD holder in economics (development economics) obtained from the University of Bamenda (UBa), Cameroon. He holds a Bachelor\'s degree in economics from the University of Dschang, Cameroon, and a Master\'s degree in economics (Labour economics) from UBa. He is a trained teacher of Economic Science and Techniques (EST) with a technical secondary school teacher diploma (DIPET 2). He is presently a national pedagogic inspector in Cameroon\'s Ministry of Secondary Education after serving as North West regional pedagogic inspector for 6 years. He supervises teachers of entrepreneurship, edits and records their lessons for presentation on the Ministry\'s AI Distance Learning Centre (AIDLC). He is also a GCE advanced level examiner in economics since 2009.';
$string['aboutvisittitle'] = 'Get in touch';
$string['aboutcalllabel'] = 'Phone';
$string['aboutemaillabel'] = 'Email';
$string['aboutctatitle'] = 'Ready to get started?';
$string['aboutctatext'] = 'Choose a course and start learning, or contact us if you have a question.';
$string['aboutctalabel'] = 'Browse courses';
$string['aboutcreateaccount'] = 'Create account';
$string['officelabel'] = 'Office';
$string['officedirections'] = 'Get directions';

$string['contactkicker'] = 'NextGen Academy';
$string['contacttitle'] = 'Contact us';
$string['contactintro'] = 'Questions about a course or your account? Send us a message, call, or visit one of our offices.';
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
$string['contactchannels'] = 'Contact details';
$string['contactphonelabel'] = 'Phone';
$string['contactemaillabel'] = 'Email';
$string['contactresponse'] = 'We aim to reply within one to two working days.';

$string['privacykicker'] = 'Policies';
$string['privacytitle'] = 'Privacy policy';
$string['privacyintro'] = 'How Next Gen Entrepreneurship Development Ltd handles account, learning, support, and payment-related information when you use NextGen Academy.';
$string['privacysectioncount'] = '7';
$string['privacysection1title'] = 'Who is responsible and what we collect';
$string['privacysection1text'] = 'Next Gen Entrepreneurship Development Ltd operates NextGen Academy. We collect information you provide to create or manage an account, enrol in a course, contact support, or make a purchase. This can include your name, email address, account details, messages, and information needed to provide the service.';
$string['privacysection2title'] = 'NextGen Academy account and learning records';
$string['privacysection2text'] = 'NextGen Academy records account and course information needed to provide learning activities. Depending on the features used in a course, records can include course enrolments and access, activity and page-view logs, the IP address and time associated with requests, course completion, grades, quiz attempts and responses, assignment submissions, and forum posts. Teachers and authorised administrators can access records needed to teach, assess, and support learners.';
$string['privacysection3title'] = 'Payments through Campay';
$string['privacysection3text'] = 'Course checkout uses Campay for Mobile Money and card payments. Campay processes payment credentials. NextGen Academy does not intentionally store full payment-card numbers, card security codes, or Mobile Money PINs. The service may retain a transaction reference, amount, payment status, and course-enrolment record to reconcile a purchase and provide access. Campay may process your information under its own terms and privacy practices.';
$string['privacysection4title'] = 'Cookies, theme resources, and analytics';
$string['privacysection4text'] = 'NextGen Academy uses essential cookies to keep you signed in and support security and preferences. The site serves its Manrope fonts and theme images locally; it does not load Google Fonts or include its own advertising or behaviour-tracking scripts. Site administrators or additional integrations may configure analytics or other services; those tools are governed by their own notices and site settings.';
$string['privacysection5title'] = 'How information is used and shared';
$string['privacysection5text'] = 'We use information to operate accounts and courses, record progress, process and reconcile payments, respond to requests, maintain security, and meet legal obligations. Information is shared only with authorised staff and teachers, the hosting or technical providers needed to run the site, payment providers involved in a transaction, or where disclosure is required by law.';
$string['privacysection6title'] = 'Retention and security';
$string['privacysection6text'] = 'Records are kept for as long as needed to provide the service, meet applicable record-keeping requirements, resolve disputes, and protect the platform. NextGen Academy administrators control retention and deletion settings. We use access controls and other reasonable safeguards, but no online service can guarantee absolute security.';
$string['privacysection7title'] = 'Your choices and privacy requests';
$string['privacysection7text'] = 'Subject to applicable law, you may ask to access, correct, export, or delete personal information, or raise an objection or complaint. Some learning, payment, or audit records may need to be retained where the law permits or requires it. If you are under 13, or below the minimum age that applies where you live, do not create an account without the consent required by law.';
$string['campayprivacytext'] = 'We could not verify a standalone Campay privacy-policy URL on the provider’s public website. Contact Campay through its official site to request its current privacy notice:';
$string['campayprivacylabel'] = 'Campay official website';
$string['privacycontacttitle'] = 'Contact about privacy';
$string['privacycontacttext'] = 'For privacy requests and questions, contact us:';

$string['termskicker'] = 'Policies';
$string['termstitle'] = 'Terms and conditions';
$string['termsintro'] = 'These terms apply when you access NextGen Academy, create an account, or enrol in a course.';
$string['termssectioncount'] = '7';
$string['termssection1title'] = 'Acceptable use';
$string['termssection1text'] = 'Use the site lawfully and only for its intended learning and support purposes. Do not disrupt the platform, bypass access controls, misuse another person’s account, upload unlawful or harmful material, or interfere with other learners’ use of the service.';
$string['termssection2title'] = 'Accounts and learner conduct';
$string['termssection2text'] = 'Provide accurate account details, keep your sign-in credentials confidential, and promptly tell us if you suspect unauthorised access. Accounts are for one person and must not be shared. Cheating, impersonation, abusive or discriminatory posts, harassment, and other serious misuse may lead to content removal, suspension, or account termination after review where practicable.';
$string['termssection3title'] = 'Course materials and intellectual property';
$string['termssection3text'] = 'Course creators and other rights holders retain ownership of the course content, videos, illustrations, files, and teaching materials they provide, unless a course or separate licence says otherwise. Next Gen Entrepreneurship Development Ltd retains rights in its name, branding, and original site materials. Your enrolment gives you a limited, personal, non-transferable right to access course materials for learning. Do not download, copy, distribute, share, publish, or resell course assets except where the rights holder has expressly permitted it or the law allows it.';
$string['termssection4title'] = 'Fees and payments';
$string['termssection4text'] = 'The price and available payment methods are shown at checkout. Payments are processed through Campay; its payment terms also apply to that transaction. Keep your transaction confirmation. Refund eligibility and payment-provisioning issues are covered by the Refund and Cancellation Policy.';
$string['termssection5title'] = 'Availability and custom software';
$string['termssection5text'] = 'NextGen Academy, including its learning platform, custom design, and payment integration, is provided “as is” and “as available” to the extent permitted by law. We do not promise uninterrupted or error-free operation. We are not responsible for loss caused by an outage, software defect, data loss, or transaction error except where liability cannot lawfully be excluded or limited. Nothing in these terms removes consumer rights that cannot be waived.';
$string['termssection6title'] = 'Changes and termination';
$string['termssection6text'] = 'We may update courses, features, or these terms. Material changes will be published on this page. We may restrict or terminate access for serious or repeated breaches, account sharing, cheating, abusive content, security risks, or legal requirements. Where appropriate, we will give notice and an opportunity to respond. Any outstanding payment or refund rights remain subject to applicable law and the Refund and Cancellation Policy.';
$string['termssection7title'] = 'Applicable rights';
$string['termssection7text'] = 'These terms operate subject to the mandatory laws and consumer protections that apply to you. If a provision cannot be enforced, the rest of the terms remain in effect to the extent allowed by law.';
$string['termscontacttitle'] = 'Questions about these terms';
$string['termscontacttext'] = 'For questions about these terms, contact us:';

$string['refundkicker'] = 'Policies';
$string['refundtitle'] = 'Refund and cancellation policy';
$string['refundintro'] = 'This policy explains when a course fee may be refunded and what to do if payment succeeds but course access does not appear.';
$string['refundsectioncount'] = '6';
$string['refundsection1title'] = 'When a refund may be requested';
$string['refundsection1text'] = 'For an individual paid course, request a refund before starting any course activities. Once you have started a course activity, the fee is not refundable under this policy. This policy does not limit any refund or cancellation rights that cannot lawfully be excluded.';
$string['refundsection2title'] = 'Sponsored and custom programmes';
$string['refundsection2text'] = 'A sponsored place, group enrolment, or custom programme follows the written agreement made for that programme. Contact the organisation named in that agreement if you need to cancel.';
$string['refundsection3title'] = 'How to request a refund';
$string['refundsection3text'] = 'Contact us from the email address registered to your NextGen Academy account and include the course name, purchase date, and Campay transaction reference or receipt. Do not send a full card number, security code, or Mobile Money PIN. We may ask for reasonable information to identify and verify the transaction.';
$string['refundsection4title'] = 'Payment succeeded but course access is missing';
$string['refundsection4text'] = 'Do not submit another payment while a charge is being checked. Contact NextGen Academy support with your course name, account email, and Campay transaction reference or receipt. We will check the payment status and enrolment record, then arrange access if payment is confirmed or explain the next step if the payment was not completed. If a confirmed payment cannot be matched or access cannot be provided, we will investigate and handle any refund due under this policy and applicable law.';
$string['refundsection5title'] = 'How approved refunds are returned';
$string['refundsection5text'] = 'An approved refund is normally sent through the original payment method. The time for funds to appear depends on Campay, the Mobile Money operator, card issuer, and financial institution. We will provide the available transaction or refund reference when one is issued.';
$string['refundsection6title'] = 'Chargebacks and payment questions';
$string['refundsection6text'] = 'Please contact us first if you do not recognise a charge or believe a transaction failed. We will review the payment record and respond with the information available. You may also contact your payment provider; this policy does not remove your rights to dispute a transaction.';
$string['refundcontacttitle'] = 'Request help with a refund';
$string['refundcontacttext'] = 'For a refund request or payment-access issue, contact us:';

$string['accessibilitykicker'] = 'Policies';
$string['accessibilitytitle'] = 'Accessibility statement';
$string['accessibilityintro'] = 'NextGen Academy aims to make online learning usable and inclusive for learners with disabilities.';
$string['accessibilitysectioncount'] = '4';
$string['accessibilitysection1title'] = 'Our accessibility target';
$string['accessibilitysection1text'] = 'We aim to conform to the Web Content Accessibility Guidelines (WCAG) 2.1 Level AA. This is our target, not a claim that every page or course has been independently audited or certified.';
$string['accessibilitysection2title'] = 'Platform and theme';
$string['accessibilitysection2text'] = 'NextGen Academy includes accessibility features, but the experience can also depend on our custom design, course materials, third-party content, and the devices or assistive technologies in use. We use a locally hosted Manrope font and theme imagery; we continue to review navigation, keyboard access, text alternatives, and visual presentation as the site changes.';
$string['accessibilitysection3title'] = 'Report a barrier or request help';
$string['accessibilitysection3text'] = 'If you cannot access a page, course activity, document, or feature, contact us and include the page address, what you were trying to do, and any assistive technology or browser details you are comfortable sharing. Please do not include sensitive health information. We will review the issue and discuss an accessible alternative where possible.';
$string['accessibilitysection4title'] = 'Ongoing improvement';
$string['accessibilitysection4text'] = 'We welcome feedback and use reports to guide improvements. Response time may depend on the nature of the issue and the course content involved.';
$string['accessibilitycontacttitle'] = 'Accessibility contact';
$string['accessibilitycontacttext'] = 'To report an accessibility barrier or request an alternative format, contact us:';
$string['legalcontactfallback'] = 'the Contact page';
$string['legalcontentslabel'] = 'On this page';

$string['faqkicker'] = 'Help';
$string['faqtitle'] = 'Frequently asked questions';
$string['faqintro'] = 'Short answers about NextGen Academy, our courses, and how to join and come back to them.';
$string['faqsectioncount'] = '9';
$string['faqsection1title'] = 'What is NextGen Academy?';
$string['faqsection1text'] = 'NextGen Academy is the online professional and vocational entrepreneurship training of Next Gen Entrepreneurship Development Ltd, a vocational training centre that promotes entrepreneurship knowledge, skills, and opportunities in Cameroon, across Africa, and internationally.';
$string['faqsection2title'] = 'Who are the courses for?';
$string['faqsection2text'] = 'Youths, students, and business practitioners who want to start or grow a business, wherever they are.';
$string['faqsection3title'] = 'What do the courses cover?';
$string['faqsection3text'] = '{$a}, including agripreneurship, techpreneurship, green, cultural, and sports entrepreneurship, teacherpreneurship, and kingdom, Islamic, and war entrepreneurship.';
$string['faqsection4title'] = 'Where are your offices?';
$string['faqsection4text'] = 'Our main office is in Yaoundé and our branch is in Bertoua. Addresses, directions, and our phone number are on the About and Contact pages.';
$string['faqsection5title'] = 'How do I find a course?';
$string['faqsection5text'] = 'Open Courses in the header, or browse by category. Only published courses are listed.';
$string['faqsection6title'] = 'How do I join a course?';
$string['faqsection6text'] = 'Open the course and use the enrolment method shown there. Some courses are open. Others need a key or a teacher to enrol you.';
$string['faqsection7title'] = 'Where is my progress saved?';
$string['faqsection7text'] = 'Sign in, then open My courses or the course itself. Completion stays with your account, so a later visit shows what is already done.';
$string['faqsection8title'] = 'I forgot my password.';
$string['faqsection8text'] = 'Use Forgot password on the sign-in page and enter the email address on the account. We will send you a link to reset it.';
$string['faqsection9title'] = 'Who do I contact for help?';
$string['faqsection9text'] = 'Use Contact in the header, or call us on the number shown in the footer. Include the course name if the question is about one.';

$string['institutionheading'] = 'Institutional programmes';
$string['institutionintro'] = 'Courses for a school, a company, or a sponsored group. The courses, the group size, and the fee are confirmed before anyone is enrolled. A single course still has Request a quote on its own page.';
$string['institution1title'] = 'Institutional packages';
$string['institution1text'] = 'Choose several published courses for the staff or students of one organisation.';
$string['institution2title'] = 'Sponsored cohorts';
$string['institution2text'] = 'A sponsor can cover the fee for a named group. Those learners join together rather than paying one by one.';
$string['institution3title'] = 'Custom programmes';
$string['institution3text'] = 'A programme can follow the published courses, or be arranged around the work of one organisation.';
$string['institutioncta'] = 'Request a quote';
$string['facultyprev'] = 'Previous instructor';
$string['facultynext'] = 'Next instructor';

$string['dashboardhello'] = 'Hi, {$a}';
$string['dashboardtoday'] = 'Today is {$a}.';
$string['dashboardlastlogin'] = 'Your last login was {$a} ago.';
$string['dashboardrecent'] = 'Recently accessed course:';
$string['dashboardcontinue'] = 'Continue learning';
$string['dashboardlessonin'] = 'In {$a}';
$string['dashboardready'] = 'Open a course when you are ready to continue.';
$string['dashboardmycourses'] = 'My courses';
$string['dashboardall'] = 'All courses';
$string['dashboardinprogress'] = 'In progress';
$string['dashboardpast'] = 'Past';
$string['dashboardnoprogress'] = 'No completion criteria';

$string['blognav'] = 'Blog';
$string['coursesnav'] = 'Courses';
$string['categoriesnav'] = 'Categories';
$string['explorecourses'] = 'Explore courses';
$string['learnmore'] = 'Learn more';
$string['mylearning'] = 'My learning';
$string['officemain'] = 'Main office';
$string['officebranch'] = 'Branch';
$string['blogintro'] = 'Notes and updates from NextGen Academy.';
$string['blogback'] = 'All posts';
$string['blogedited'] = 'Updated {$a}';
$string['blogsignin'] = 'Sign in to comment';
$string['blogcontinue'] = 'Continue reading';
$string['blogwatchyoutube'] = 'Watch on YouTube';
$string['blogsharex'] = 'Share on X';
$string['blogsharefacebook'] = 'Share on Facebook';
$string['blogsharelinkedin'] = 'Share on LinkedIn';
$string['blogshareemail'] = 'Share by email';
$string['blogcopylink'] = 'Copy link';
$string['blogcopied'] = 'Link copied';
$string['emailfooter'] = 'This message was sent by';
