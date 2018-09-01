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

namespace Tarantula\Domain\Link\Exception;

use DomainException;

final class InvalidLinkUrl extends DomainException
{
    public static function causeInvalidUrlFormat(): self
    {
        return new self('Link url has not a valid url format');
    }
}
