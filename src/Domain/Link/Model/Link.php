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

use Tarantula\Domain\Entity;
use Tarantula\Domain\Link\Exception\InvalidLink;

final class Link implements Entity
{
    /** @var LinkId */
    private $id;

    /** @var LinkUrl */
    private $url;

    /** @var array|null */
    private $headers;

    public static function with(LinkId $id, LinkUrl $url, ?array $headers = null): self
    {
        return new self($id, $url, $headers);
    }

    private function __construct(LinkId $id, LinkUrl $url, ?array $headers = null)
    {
        if (is_array($headers) && count($headers) > 5) {
            throw InvalidLink::causeMoreThan5Headers();
        }

        $this->id = $id;
        $this->url = $url;
        $this->headers = $headers;
    }

    public function visit(array $headers): void
    {
        if (count($headers) > 5) {
            throw InvalidLink::causeMoreThan5Headers();
        }

        $this->headers = $headers;
    }

    public function toArray(): array
    {
        $array = [
            'id' => $this->id()->__toString(),
            'url' => $this->url()->__toString(),
        ];

        if ($this->isVisited()) {
            $array['headers'] = $this->headers();
        }

        return $array;
    }

    public function id(): LinkId
    {
        return $this->id;
    }

    public function url(): LinkUrl
    {
        return $this->url;
    }

    public function headers(): ?array
    {
        return $this->headers;
    }

    public function isVisited(): bool
    {
        return null !== $this->headers();
    }

    public function hasSameIdentityAs(Entity $entity): bool
    {
        /** @var self $entity */
        return get_class($this) === get_class($entity) && $this->id()->hasSameValueAs($entity->id());
    }
}
