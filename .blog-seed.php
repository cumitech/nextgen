<?php
define('CLI_SCRIPT', true);
require 'C:/xampp/htdocs/moodle/config.php';
require_once $CFG->dirroot . '/blog/locallib.php';

$admin = get_admin();
\core\session\manager::set_user($admin);

$logo = $CFG->wwwroot . '/theme/nextgen/pix/logo.webp';
$posts = [
    [
        'subject' => 'NGCHECK Curabitur iaculis lorem libero',
        'summary' => '<p>Sed blandit fermentum est, a molestie mi accumsan sed. Ut eget eros sodales, mollis purus non, porttitor nulla.</p>'
            . '<p><img src="' . $logo . '" alt=""></p>'
            . '<p>Praesent ultricies fermentum mollis. Cras at orci vitae quam ultricies consectetur sit amet id tellus.</p>',
        'tags' => ['University', 'Moodle'],
    ],
    [
        'subject' => 'NGCHECK Aenean a nisi massa in bibendum',
        'summary' => '<p>Duis pellentesque semper risus. Interdum rhoncus ligula efficitur vel.</p>'
            . '<p>https://www.youtube.com/watch?v=dQw4w9WgXcQ</p>',
        'tags' => [],
    ],
    [
        'subject' => 'NGCHECK Primum quid tu dicis breve',
        'summary' => '<p>Recte, inquit, intellego. Ergo, inquit, tibi. Quamquam in quidem video minime esse deteriorem.</p>'
            . '<p>Immo vero, inquit, ad beatissime vivendum parum est.</p>',
        'tags' => [],
    ],
];

foreach ($posts as $post) {
    $entry = new blog_entry();
    $entry->subject = $post['subject'];
    $entry->summary = $post['summary'];
    $entry->summaryformat = FORMAT_HTML;
    $entry->publishstate = 'public';
    $entry->userid = $admin->id;
    $entry->tags = $post['tags'];
    $entry->add();
    echo $entry->id . PHP_EOL;
}
