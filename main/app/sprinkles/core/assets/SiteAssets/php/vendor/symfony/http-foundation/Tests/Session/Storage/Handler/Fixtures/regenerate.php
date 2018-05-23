<?php

require __DIR__ . '/common.inc';

session_set_save_handler(new TestSessionHandler('abc|i:123;'), FALSE);
session_start();

session_regenerate_id(TRUE);

ob_start(function ($buffer) {
    return str_replace(session_id(), 'random_session_id', $buffer);
});
