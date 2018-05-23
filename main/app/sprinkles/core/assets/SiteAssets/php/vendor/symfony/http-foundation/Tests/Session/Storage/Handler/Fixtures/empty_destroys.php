<?php

require __DIR__ . '/common.inc';

session_set_save_handler(new TestSessionHandler('abc|i:123;'), FALSE);
session_start();

unset($_SESSION['abc']);
