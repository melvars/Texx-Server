<?php
require "vendor/autoload.php";

use MatthiasMullie\Minify;

$minifier = new Minify\JS('assets/js/jquery.js');
$minifier->add('assets/js/fontawesome.js');
$minifier->add('assets/js/modernizr.js');
$minifier->add('assets/js/language.js');
$minifier->add('assets/js/encryption.js');
$minifier->add('assets/js/chat.js');
$minifier->add('assets/js/slick.js');
$minifier->add('assets/js/main.js');
echo $minifier->minify();