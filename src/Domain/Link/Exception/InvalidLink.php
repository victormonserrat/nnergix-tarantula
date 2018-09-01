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

final class InvalidLink extends DomainException
{
    public static function causeMoreThan5Headers(): self
    {
        return new self('Link should not have more than 5 headers');
    }
}
