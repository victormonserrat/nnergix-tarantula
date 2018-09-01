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

use Tarantula\Domain\Link\Exception\InvalidLinkUrl;
use Tarantula\Domain\ValueObject;

final class LinkUrl implements ValueObject
{
    /** @var string */
    private $url;

    public static function fromString(string $url): self
    {
        return new self($url);
    }

    private function __construct(string $url)
    {
        if (!filter_var($url, \FILTER_VALIDATE_URL)) {
            throw InvalidLinkUrl::causeInvalidUrlFormat();
        }

        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->url;
    }

    public function hasSameValueAs(ValueObject $valueObject): bool
    {
        /** @var self $valueObject */
        return get_class($this) === get_class($valueObject) && $this->__toString() === $valueObject->__toString();
    }
}
