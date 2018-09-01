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

namespace spec\Tarantula\Application\Handler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Tarantula\Application\Command\AddLinks;
use Tarantula\Application\Repository\Links;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class AddLinksHandlerSpec extends ObjectBehavior
{
    public function let(Links $links): void
    {
        $this->beConstructedWith($links);
    }

    public function it_adds_not_added_links(Links $links): void
    {
        $url = 'https://www.nnergix.com/';
        $addedLinks = [];
        $link = Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url)
        );

        $links->withUrls([LinkUrl::fromString($url)])->willReturn($addedLinks);
        $links->add($link)->shouldBeCalled();
        $links->save()->shouldBeCalled();

        $this(AddLinks::with([$link]));
    }

    public function it_updates_added_links(Links $links): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html',
            ],
        ];
        $addedLink = Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers
        );
        $updatedHeaders = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];

        $links->withUrls([LinkUrl::fromString($url)])->willReturn([$addedLink]);
        $links->update(Argument::that(function (Link $link) use ($addedLink, $updatedHeaders) {
            return
                $link->hasSameIdentityAs($addedLink) &&
                $link->headers() === $updatedHeaders
            ;
        }))->shouldBeCalled();
        $links->save()->shouldBeCalled();

        $this(AddLinks::with([Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $updatedHeaders
        )]));
    }

    public function it_does_not_update_added_links_to_unvisited(Links $links): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];
        $addedLink = Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers
        );

        $links->withUrls([LinkUrl::fromString($url)])->willReturn([$addedLink]);
        $links->update($addedLink)->shouldNotBeCalled();
        $links->save()->shouldBeCalled();

        $this(AddLinks::with([Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url)
        )]));
    }

    public function it_does_not_update_unchanged_added_links(Links $links): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];
        $addedLink = Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers
        );

        $links->withUrls([LinkUrl::fromString($url)])->willReturn([$addedLink]);
        $links->update($addedLink)->shouldNotBeCalled();
        $links->save()->shouldBeCalled();

        $this(AddLinks::with([Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers
        )]));
    }
}
