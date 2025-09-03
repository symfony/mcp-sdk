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

use Symfony\AI\McpSdk\Exception\InvalidCursorException;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface CollectionInterface
{
    /**
     * @param int $count the number of metadata items to return
     *
     * @return iterable<MetadataInterface>
     *
     * @throws InvalidCursorException if no item with $lastIdentifier was found
     */
    public function getMetadata(int $count, ?string $lastIdentifier = null): iterable;
}
