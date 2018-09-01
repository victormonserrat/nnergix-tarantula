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

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tarantula\Application\Service\Crawler;
use Tarantula\Application\Service\CrawlerClient;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class CrawlerSpec extends ObjectBehavior
{
    public function let(CrawlerClient $client): void
    {
        $this->beConstructedWith($client);
    }

    public function it_is_a_crawler(): void
    {
        $this->shouldImplement(Crawler::class);
    }

    public function it_can_get_links_from_an_url(CrawlerClient $client): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];
        $link = Link::with(
            LinkId::generate(),
            LinkUrl::fromString('https://www.nnergix.com/contact/')
        );

        $client->requestLink(Argument::that(function (Link $link) use ($url) {
            return $link->url()->hasSameValueAs(LinkUrl::fromString($url));
        }))->shouldBeCalled();
        $client->getHeaders()->willReturn($headers);
        $client->getLinks()->willReturn([$link]);

        $this->getLinksFromUrl(LinkUrl::fromString($url))->shouldContain($link);
        $this->getLinksFromUrl(LinkUrl::fromString($url))->shouldContainLinkWith(LinkUrl::fromString($url), $headers);
    }

    public function it_can_not_get_links_with_a_depth_less_than_1(): void
    {
        $url = 'https://www.nnergix.com/';

        $this->shouldThrow(
            new InvalidArgumentException('Depth should not be less than 1')
        )->during('getLinksFromUrl', [
            LinkUrl::fromString($url),
            0,
        ]);
    }

    public function it_does_not_get_duplicated_links(CrawlerClient $client): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];
        $link = Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers
        );

        $client->requestLink(Argument::that(function (Link $link) use ($url) {
            return $link->url()->hasSameValueAs(LinkUrl::fromString($url));
        }))->shouldBeCalled();
        $client->getHeaders()->willReturn($headers);
        $client->getLinks()->willReturn([$link]);

        $this->getLinksFromUrl(LinkUrl::fromString($url))->shouldHaveCount(1);
    }

    public function it_does_not_get_links_requesting_the_same_more_than_once(CrawlerClient $client): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];
        $link = Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers
        );

        $client->requestLink(Argument::that(function (Link $link) use ($url) {
            return $link->url()->hasSameValueAs(LinkUrl::fromString($url));
        }))->shouldBeCalledOnce();
        $client->getHeaders()->willReturn($headers);
        $client->getLinks()->willReturn([$link]);

        $this->getLinksFromUrl(LinkUrl::fromString($url), 2);
    }

    public function getMatchers(): array
    {
        return [
            'containLinkWith' => function ($links, LinkUrl $url, array $headers) {
                /** @var Link[] $links */
                foreach ($links as $link) {
                    if (
                        $link->url()->hasSameValueAs($url) &&
                        $link->headers() === $headers
                    ) {
                        return true;
                    }
                }

                return false;
            },
        ];
    }
}
