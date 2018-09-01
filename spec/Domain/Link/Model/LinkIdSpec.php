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
use Tarantula\Domain\Link\Exception\InvalidLinkId;
use Tarantula\Domain\Link\Model\LinkId;
use Tarantula\Domain\ValueObject;

final class LinkIdSpec extends ObjectBehavior
{
    public function it_is_a_value_object(): void
    {
        $this->shouldImplement(ValueObject::class);
    }

    public function it_can_be_generated(): void
    {
        $this->beConstructedThrough('generate');
        $this->shouldHaveType(LinkId::class);
    }

    public function it_can_be_created_from_a_string(): void
    {
        $uuid = 'aac4c7e7-5cbd-4cc8-8742-c361584558de';

        $this->beConstructedThrough('fromString', [$uuid]);
        $this->__toString()->shouldReturn($uuid);
    }

    public function it_can_not_be_created_from_an_invalid_uuid_formatted_string(): void
    {
        $invalidUuidFormat = 'invalid uuid format';

        $this->beConstructedThrough('fromString', [$invalidUuidFormat]);
        $this->shouldThrow(InvalidLinkId::causeInvalidUuidFormat())->duringInstantiation();
    }

    public function it_can_be_compared(): void
    {
        $uuid = 'aac4c7e7-5cbd-4cc8-8742-c361584558de';

        $this->beConstructedThrough('fromString', [$uuid]);
        $this->hasSameValueAs(LinkId::generate())->shouldBe(false);
        $this->hasSameValueAs(LinkId::fromString($uuid))->shouldBe(true);
    }
}
