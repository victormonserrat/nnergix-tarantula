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

namespace Tarantula\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Tarantula\Domain\Link\Model\LinkUrl;

final class LinkUrlType extends Type
{
    private const NAME = 'link_url';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    /** @param LinkUrl $value */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->__toString();
    }

    /** @param string $value */
    public function convertToPHPValue($value, AbstractPlatform $platform): LinkUrl
    {
        return LinkUrl::fromString($value);
    }

    public function getName(): string
    {
        return static::NAME;
    }
}
