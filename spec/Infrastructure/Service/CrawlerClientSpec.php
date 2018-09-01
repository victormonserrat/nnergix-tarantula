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

namespace spec\Tarantula\Infrastructure\Service;

use Goutte\Client;
use PhpSpec\ObjectBehavior;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Link as DomLink;
use Tarantula\Application\Service\CrawlerClient;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class CrawlerClientSpec extends ObjectBehavior
{
    public function let(Client $client): void
    {
        $desiredHeaders = ['Content-Type'];

        $this->beConstructedWith($client, $desiredHeaders);
    }

    public function it_is_a_crawler_client(): void
    {
        $this->shouldImplement(CrawlerClient::class);
    }

    public function it_can_request_a_link(Client $client): void
    {
        $url = 'https://www.nnergix.com/';

        $client->request('GET', $url)->shouldBeCalled();

        $this->requestLink(Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url)
        ));
    }

    public function it_can_get_the_desired_headers(Client $client, Response $response): void
    {
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
            'Server' => [
                'Apache',
            ],
        ];
        $desiredHeaders = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];

        $client->getInternalResponse()->willReturn($response);
        $response->getHeaders()->willReturn($headers);

        $this->getHeaders()->shouldReturn($desiredHeaders);
    }

    public function it_gets_all_headers_when_there_are_not_desired_headers(Client $client, Response $response): void
    {
        $this->beConstructedWith($client);

        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
            'Server' => [
                'Apache',
            ],
        ];

        $client->getInternalResponse()->willReturn($response);
        $response->getHeaders()->willReturn($headers);

        $this->getHeaders()->shouldReturn($headers);
    }

    public function it_can_get_links(Client $client, Crawler $crawler, DomLink $link): void
    {
        $url = 'https://www.nnergix.com/';

        $client->getCrawler()->willReturn($crawler);
        $crawler->filter('a')->willReturn($crawler);
        $crawler->links()->willReturn([$link]);
        $link->getUri()->willReturn($url);

        $this->getLinks()->shouldContainLinkWithUrl(LinkUrl::fromString($url));
    }

    public function it_does_not_get_links_with_invalid_url_format(Client $client, Crawler $crawler, DomLink $link): void
    {
        $invalidUrlFormat = 'invalid url format';

        $client->getCrawler()->willReturn($crawler);
        $crawler->filter('a')->willReturn($crawler);
        $crawler->links()->willReturn([$link]);
        $link->getUri()->willReturn($invalidUrlFormat);

        $this->getLinks()->shouldReturn([]);
    }

    public function getMatchers(): array
    {
        return [
            'containLinkWithUrl' => function ($links, LinkUrl $url) {
                /** @var Link[] $links */
                foreach ($links as $link) {
                    if ($link->url()->hasSameValueAs($url)) {
                        return true;
                    }
                }

                return false;
            },
        ];
    }
}
