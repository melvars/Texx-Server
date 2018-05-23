<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('ERR_SELECT_FAILED', 1);
define('ERR_TIMEOUT', 2);
define('ERR_READ_FAILED', 3);
define('ERR_WRITE_FAILED', 4);

$read = array(STDIN);
$write = array(STDOUT, STDERR);

stream_set_blocking(STDIN, 0);
stream_set_blocking(STDOUT, 0);
stream_set_blocking(STDERR, 0);

$out = $err = '';
while ($read || $write) {
    $r = $read;
    $w = $write;
    $e = NULL;
    $n = stream_select($r, $w, $e, 5);

    if (FALSE === $n) {
        die(ERR_SELECT_FAILED);
    } else if ($n < 1) {
        die(ERR_TIMEOUT);
    }

    if (in_array(STDOUT, $w) && strlen($out) > 0) {
        $written = fwrite(STDOUT, (binary)$out, 32768);
        if (FALSE === $written) {
            die(ERR_WRITE_FAILED);
        }
        $out = (binary)substr($out, $written);
    }
    if (NULL === $read && '' === $out) {
        $write = array_diff($write, array(STDOUT));
    }

    if (in_array(STDERR, $w) && strlen($err) > 0) {
        $written = fwrite(STDERR, (binary)$err, 32768);
        if (FALSE === $written) {
            die(ERR_WRITE_FAILED);
        }
        $err = (binary)substr($err, $written);
    }
    if (NULL === $read && '' === $err) {
        $write = array_diff($write, array(STDERR));
    }

    if ($r) {
        $str = fread(STDIN, 32768);
        if (FALSE !== $str) {
            $out .= $str;
            $err .= $str;
        }
        if (FALSE === $str || feof(STDIN)) {
            $read = NULL;
            if (!feof(STDIN)) {
                die(ERR_READ_FAILED);
            }
        }
    }
}
