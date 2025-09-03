<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Server\Transport\Sse;

use Symfony\Component\Uid\Uuid;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
interface StoreInterface
{
    public function push(Uuid $id, string $message): void;

    public function pop(Uuid $id): ?string;

    public function remove(Uuid $id): void;
}
