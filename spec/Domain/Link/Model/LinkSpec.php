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

namespace spec\Tarantula\Domain\Link\Model;

use PhpSpec\ObjectBehavior;
use Tarantula\Domain\Entity;
use Tarantula\Domain\Link\Exception\InvalidLink;
use Tarantula\Domain\Link\Model\Link;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\Link\Model\LinkUrl;

final class LinkSpec extends ObjectBehavior
{
    public function it_is_an_entity(): void
    {
        $this->shouldImplement(Entity::class);
    }

    public function it_can_be_created_unvisited(): void
    {
        $uuid = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
        $url = 'https://www.nnergix.com/';

        $this->beConstructedThrough('with', [
            LinkId::fromString($uuid),
            LinkUrl::fromString($url),
        ]);
        $this->id()->shouldBeLike(LinkId::fromString($uuid));
        $this->url()->shouldBeLike(LinkUrl::fromString($url));
        $this->isVisited()->shouldBe(false);
    }

    public function it_can_be_created_visited(): void
    {
        $uuid = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];

        $this->beConstructedThrough('with', [
            LinkId::fromString($uuid),
            LinkUrl::fromString($url),
            $headers,
        ]);
        $this->id()->shouldBeLike(LinkId::fromString($uuid));
        $this->url()->shouldBeLike(LinkUrl::fromString($url));
        $this->isVisited()->shouldBe(true);
        $this->headers()->shouldBeLike($headers);
    }

    public function it_can_not_be_created_visited_with_more_than_5_headers(): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = array_fill(0, 6, null);

        $this->beConstructedThrough('with', [
            LinkId::generate(),
            LinkUrl::fromString($url),
            $headers,
        ]);
        $this->shouldThrow(InvalidLink::causeMoreThan5Headers())->duringInstantiation();
    }

    public function it_can_be_visited(): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];

        $this->beConstructedThrough('with', [
            LinkId::generate(),
            LinkUrl::fromString($url),
        ]);
        $this->visit($headers);
        $this->isVisited()->shouldBe(true);
        $this->headers()->shouldBeLike($headers);
    }

    public function it_can_not_be_visited_with_more_than_5_headers(): void
    {
        $url = 'https://www.nnergix.com/';
        $headers = array_fill(0, 6, null);

        $this->beConstructedThrough('with', [
            LinkId::generate(),
            LinkUrl::fromString($url),
        ]);
        $this->shouldThrow(InvalidLink::causeMoreThan5Headers())->during('visit', [$headers]);
    }

    public function it_can_be_an_array(): void
    {
        $uuid = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
        $url = 'https://www.nnergix.com/';
        $headers = [
            'Content-Type' => [
                'text/html; charset=UTF-8',
            ],
        ];

        $this->beConstructedThrough('with', [
            LinkId::fromString($uuid),
            LinkUrl::fromString($url),
        ]);
        $this->toArray()->shouldBeLike([
            'id' => $uuid,
            'url' => $url,
        ]);
        $this->visit($headers);
        $this->toArray()->shouldBeLike([
            'id' => $uuid,
            'url' => $url,
            'headers' => $headers,
        ]);
    }

    public function it_can_be_compared(): void
    {
        $uuid = 'e8a68535-3e17-468f-acc3-8a3e0fa04a59';
        $url = 'https://www.nnergix.com/';

        $this->beConstructedThrough('with', [
            LinkId::fromString($uuid),
            LinkUrl::fromString($url),
        ]);
        $this->hasSameIdentityAs(Link::with(
            LinkId::generate(),
            LinkUrl::fromString($url)
        ))->shouldBe(false);
        $this->hasSameIdentityAs(Link::with(
            LinkId::fromString($uuid),
            LinkUrl::fromString($url)
        ))->shouldBe(true);
    }
}
