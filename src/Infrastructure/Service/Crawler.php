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

namespace Tarantula\Infrastructure\Service;

use InvalidArgumentException;
use Tarantula\Application\Service\Crawler as CrawlerInterface;
use Tarantula\Application\Service\CrawlerClient;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class Crawler implements CrawlerInterface
{
    /** @var CrawlerClient */
    private $client;

    public function __construct(CrawlerClient $client)
    {
        $this->client = $client;
    }

    /** @return Link[] */
    public function getLinksFromUrl(LinkUrl $url, int $depth = 1)
    {
        if ($depth < 1) {
            throw new InvalidArgumentException('Depth should not be less than 1');
        }

        $depthLinks = [Link::with(LinkId::generate(), $url)];
        $indexedLinks = [];

        for ($i = 0; $i < $depth; ++$i) {
            $nextDepthLinks = [];

            foreach ($depthLinks as $link) {
                $url = $link->url()->__toString();

                if (isset($indexedLinks[$url])) {
                    continue;
                }

                $this->client->requestLink($link);

                $linkHeaders = $this->client->getHeaders();

                $link->visit($linkHeaders);

                $indexedLinks[$url] = $link;
                $linkLinks = $this->client->getLinks();
                $nextDepthLinks = array_merge($nextDepthLinks, $linkLinks);
            }

            $depthLinks = $nextDepthLinks;
        }

        foreach ($depthLinks as $link) {
            $url = $link->url()->__toString();

            if (isset($indexedLinks[$url])) {
                continue;
            }

            $indexedLinks[$url] = $link;
        }

        $links = array_values($indexedLinks);

        return $links;
    }
}
