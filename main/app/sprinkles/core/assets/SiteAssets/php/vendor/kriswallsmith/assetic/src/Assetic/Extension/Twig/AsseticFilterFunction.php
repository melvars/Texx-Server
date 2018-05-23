<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2014 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Extension\Twig;

class AsseticFilterFunction extends \Twig_SimpleFunction
{
    public function __construct($name, $options = array()) {
        parent::__construct($name, NULL, array_merge($options, array(
            'needs_environment' => FALSE,
            'needs_context' => FALSE,
            'node_class' => '\Assetic\Extension\Twig\AsseticFilterNode',
        )));
    }
}
