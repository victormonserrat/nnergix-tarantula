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

use Goutte\Client;
use Tarantula\Application\Service\CrawlerClient as CrawlerClientInterface;
use Tarantula\Domain\Link\Exception\InvalidLinkUrl;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class CrawlerClient implements CrawlerClientInterface
{
    /** @var Client */
    private $client;

    /** @var array|null */
    private $desiredHeaders;

    public function __construct(Client $client, ?array $desiredHeaders = null)
    {
        $this->client = $client;
        $this->desiredHeaders = $desiredHeaders;
    }

    public function requestLink(Link $link): void
    {
        $url = $link->url()->__toString();

        $this->client->request('GET', $url);
    }

    public function getHeaders(): array
    {
        $headers = $this->client->getInternalResponse()->getHeaders();

        if (null === $this->desiredHeaders) {
            return $headers;
        }

        $desiredHeaders = array_intersect_key($headers, array_flip($this->desiredHeaders));

        return $desiredHeaders;
    }

    /** @return Link[] */
    public function getLinks()
    {
        $domLinks = $this->client->getCrawler()->filter('a')->links();
        $links = [];

        foreach ($domLinks as $link) {
            try {
                $url = LinkUrl::fromString($link->getUri());
            } catch (InvalidLinkUrl $exception) {
                continue;
            }

            $links[] = Link::with(
                LinkId::generate(),
                $url
            );
        }

        return $links;
    }
}
