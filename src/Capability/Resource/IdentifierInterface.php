<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Capability\Resource;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface IdentifierInterface
{
    public function getUri(): string;
}
