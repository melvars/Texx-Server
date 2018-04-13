<?php
require "vendor/autoload.php";
use MatthiasMullie\Minify;
$minifier = new Minify\CSS('assets/css/slick.css');
$minifier->add('assets/css/main.css');
echo $minifier->minify();