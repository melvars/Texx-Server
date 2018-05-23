<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2014 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Factory\AssetFactory;

/**
 * A filter that wraps callables.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class CallablesFilter implements FilterInterface, DependencyExtractorInterface
{
    private $loader;
    private $dumper;
    private $extractor;

    /**
     * @param callable|null $loader
     * @param callable|null $dumper
     * @param callable|null $extractor
     */
    public function __construct($loader = NULL, $dumper = NULL, $extractor = NULL) {
        $this->loader = $loader;
        $this->dumper = $dumper;
        $this->extractor = $extractor;
    }

    public function filterLoad(AssetInterface $asset) {
        if (NULL !== $callable = $this->loader) {
            $callable($asset);
        }
    }

    public function filterDump(AssetInterface $asset) {
        if (NULL !== $callable = $this->dumper) {
            $callable($asset);
        }
    }

    public function getChildren(AssetFactory $factory, $content, $loadPath = NULL) {
        if (NULL !== $callable = $this->extractor) {
            return $callable($factory, $content, $loadPath);
        }

        return array();
    }
}
