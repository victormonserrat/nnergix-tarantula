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

namespace Tarantula\Application\Repository;

use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkUrl;

interface Links
{
    /**
     * @param LinkUrl[] $urls
     *
     * @return Link[]
     */
    public function withUrls($urls);

    public function add(Link $link): void;

    public function update(Link $link): void;

    public function save(): void;
}
