<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Server;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
interface TransportInterface
{
    public function initialize(): void;

    public function isConnected(): bool;

    public function receive(): \Generator;

    public function send(string $data): void;

    public function close(): void;
}
