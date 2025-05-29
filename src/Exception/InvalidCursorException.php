<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Exception;

final class InvalidCursorException extends \InvalidArgumentException implements ExceptionInterface
{
    public function __construct(
        public readonly string $cursor,
    ) {
        parent::__construct(\sprintf('Invalid value for pagination parameter "cursor": "%s"', $cursor));
    }
}
