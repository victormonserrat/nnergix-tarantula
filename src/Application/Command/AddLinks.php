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

namespace Tarantula\Application\Command;

use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class AddLinks
{
    /** @var array */
    private $content;

    /** @param Link[] $links */
    public static function with($links): self
    {
        $linksArray = array_map(function (Link $link) {
            return $link->toArray();
        }, $links);

        return new self($linksArray);
    }

    private function __construct(array $content)
    {
        $this->content = $content;
    }

    /** @return Link[] */
    public function links()
    {
        $links = array_map(function (array $link) {
            return Link::with(
                LinkId::fromString($link['id']),
                LinkUrl::fromString($link['url']),
                $link['headers'] ?? null
            );
        }, $this->content);

        return $links;
    }
}
