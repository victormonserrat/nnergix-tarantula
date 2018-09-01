<?php

/*
 * This file is part of the Tarantula package.
 *
 * (c) Nnergix
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tarantula\Application\Service;

use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkUrl;

interface Crawler
{
    /** @return Link[] */
    public function getLinksFromUrl(LinkUrl $url, int $depth = 1);
}
