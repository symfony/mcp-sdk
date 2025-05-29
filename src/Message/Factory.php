<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Message;

use Symfony\AI\McpSdk\Exception\InvalidInputMessageException;

final class Factory
{
    /**
     * @return iterable<Notification|Request|InvalidInputMessageException>
     *
     * @throws \JsonException
     */
    public function create(string $input): iterable
    {
        $data = json_decode($input, true, flags: \JSON_THROW_ON_ERROR);

        if ('{' === $input[0]) {
            $data = [$data];
        }

        foreach ($data as $message) {
            if (!isset($message['method'])) {
                yield new InvalidInputMessageException('Invalid JSON-RPC request, missing "method".');
            } elseif (str_starts_with((string) $message['method'], 'notifications/')) {
                yield Notification::from($message);
            } else {
                yield Request::from($message);
            }
        }
    }
}
