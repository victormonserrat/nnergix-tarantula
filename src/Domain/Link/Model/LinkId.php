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

namespace Tarantula\Domain\Link\Model;

use Ramsey\Uuid\Uuid;
use Tarantula\Domain\Link\Exception\InvalidLinkId;
use Tarantula\Domain\ValueObject;

final class LinkId implements ValueObject
{
    /** @var string */
    private $id;

    public static function generate()
    {
        return new self(Uuid::uuid4()->toString());
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    private function __construct(string $id)
    {
        if (!Uuid::isValid($id)) {
            throw InvalidLinkId::causeInvalidUuidFormat();
        }

        $this->id = $id;
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function hasSameValueAs(ValueObject $valueObject): bool
    {
        /** @var self $valueObject */
        return get_class($this) === get_class($valueObject) && $this->__toString() === $valueObject->__toString();
    }
}
