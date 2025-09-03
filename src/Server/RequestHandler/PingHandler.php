<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Server\RequestHandler;

use Symfony\AI\McpSdk\Message\Request;
use Symfony\AI\McpSdk\Message\Response;

/**
 * @author Christopher Hertel <mail@christopher-hertel.de>
 */
final class PingHandler extends BaseRequestHandler
{
    public function createResponse(Request $message): Response
    {
        return new Response($message->id, []);
    }

    protected function supportedMethod(): string
    {
        return 'ping';
    }
}
