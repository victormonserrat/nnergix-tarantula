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
use Tarantula\Domain\Link\Exception\InvalidLinkUrl;
use Tarantula\Domain\Link\Model\LinkUrl;
use Tarantula\Domain\ValueObject;

final class LinkUrlSpec extends ObjectBehavior
{
    public function it_is_a_value_object(): void
    {
        $this->shouldImplement(ValueObject::class);
    }

    public function it_can_be_created_from_a_string(): void
    {
        $url = 'https://www.nnergix.com/';

        $this->beConstructedThrough('fromString', [$url]);
        $this->__toString()->shouldReturn($url);
    }

    public function it_can_not_be_created_from_an_invalid_url_formatted_string(): void
    {
        $invalidUrlFormat = 'invalid url format';

        $this->beConstructedThrough('fromString', [$invalidUrlFormat]);
        $this->shouldThrow(InvalidLinkUrl::causeInvalidUrlFormat())->duringInstantiation();
    }

    public function it_can_be_compared(): void
    {
        $url = 'https://www.nnergix.com/';
        $otherUrl = 'https://www.nnergix.com/contact/';

        $this->beConstructedThrough('fromString', [$url]);
        $this->hasSameValueAs(LinkUrl::fromString($otherUrl))->shouldBe(false);
        $this->hasSameValueAs(LinkUrl::fromString($url))->shouldBe(true);
    }
}
