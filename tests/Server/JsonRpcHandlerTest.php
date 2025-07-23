<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Tests\Server;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\AI\McpSdk\Message\Factory;
use Symfony\AI\McpSdk\Message\Response;
use Symfony\AI\McpSdk\Server\JsonRpcHandler;
use Symfony\AI\McpSdk\Server\NotificationHandlerInterface;
use Symfony\AI\McpSdk\Server\RequestHandlerInterface;

#[Small]
#[CoversClass(JsonRpcHandler::class)]
class JsonRpcHandlerTest extends TestCase
{
    #[Test]
    #[TestDox('Make sure a single notification can be handled by multiple handlers.')]
    public function handleMultipleNotifications(): void
    {
        $handlerA = $this->getMockBuilder(NotificationHandlerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['supports', 'handle'])
            ->getMock();
        $handlerA->method('supports')->willReturn(true);
        $handlerA->expects($this->once())->method('handle');

        $handlerB = $this->getMockBuilder(NotificationHandlerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['supports', 'handle'])
            ->getMock();
        $handlerB->method('supports')->willReturn(false);
        $handlerB->expects($this->never())->method('handle');

        $handlerC = $this->getMockBuilder(NotificationHandlerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['supports', 'handle'])
            ->getMock();
        $handlerC->method('supports')->willReturn(true);
        $handlerC->expects($this->once())->method('handle');

        $jsonRpc = new JsonRpcHandler(new Factory(), [], [$handlerA, $handlerB, $handlerC], new NullLogger());
        $result = $jsonRpc->process(
            '{"jsonrpc": "2.0", "id": 1, "method": "notifications/foobar"}'
        );
        iterator_to_array($result);
    }

    #[Test]
    #[TestDox('Make sure a single request can NOT be handled by multiple handlers.')]
    public function handleMultipleRequests(): void
    {
        $handlerA = $this->getMockBuilder(RequestHandlerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['supports', 'createResponse'])
            ->getMock();
        $handlerA->method('supports')->willReturn(true);
        $handlerA->expects($this->once())->method('createResponse')->willReturn(new Response(1));

        $handlerB = $this->getMockBuilder(RequestHandlerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['supports', 'createResponse'])
            ->getMock();
        $handlerB->method('supports')->willReturn(false);
        $handlerB->expects($this->never())->method('createResponse');

        $handlerC = $this->getMockBuilder(RequestHandlerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['supports', 'createResponse'])
            ->getMock();
        $handlerC->method('supports')->willReturn(true);
        $handlerC->expects($this->never())->method('createResponse');

        $jsonRpc = new JsonRpcHandler(new Factory(), [$handlerA, $handlerB, $handlerC], [], new NullLogger());
        $result = $jsonRpc->process(
            '{"jsonrpc": "2.0", "id": 1, "method": "request/foobar"}'
        );
        iterator_to_array($result);
    }
}
