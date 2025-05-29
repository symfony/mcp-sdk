<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Tests\Server\RequestHandler;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\TestCase;
use Symfony\AI\McpSdk\Capability\Tool\CollectionInterface;
use Symfony\AI\McpSdk\Capability\Tool\MetadataInterface;
use Symfony\AI\McpSdk\Message\Request;
use Symfony\AI\McpSdk\Server\RequestHandler\ToolListHandler;

#[Small]
#[CoversClass(ToolListHandler::class)]
class ToolListHandlerTest extends TestCase
{
    public function testHandleEmpty(): void
    {
        $collection = $this->getMockBuilder(CollectionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $collection->expects($this->once())->method('getMetadata')->willReturn([]);

        $handler = new ToolListHandler($collection);
        $message = new Request(1, 'tools/list', []);
        $response = $handler->createResponse($message);
        $this->assertEquals(1, $response->id);
        $this->assertEquals(['tools' => []], $response->result);
    }

    public function testHandleReturnAll(): void
    {
        $item = new class implements MetadataInterface {
            public function getName(): string
            {
                return 'test_tool';
            }

            public function getDescription(): string
            {
                return 'A test tool';
            }

            public function getInputSchema(): array
            {
                return [
                    'type' => 'object',
                ];
            }
        };
        $collection = $this->getMockBuilder(CollectionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $collection->expects($this->once())->method('getMetadata')->willReturn([$item]);

        $handler = new ToolListHandler($collection);
        $message = new Request(1, 'tools/list', []);
        $response = $handler->createResponse($message);
        $this->assertCount(1, $response->result['tools']);
        $this->assertArrayNotHasKey('nextCursor', $response->result);
    }

    public function testHandlePagination(): void
    {
        $item = new class implements MetadataInterface {
            public function getName(): string
            {
                return 'test_tool';
            }

            public function getDescription(): string
            {
                return 'A test tool';
            }

            public function getInputSchema(): array
            {
                return [
                    'type' => 'object',
                ];
            }
        };
        $collection = $this->getMockBuilder(CollectionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getMetadata'])
            ->getMock();
        $collection->expects($this->once())->method('getMetadata')->willReturn([$item, $item]);

        $handler = new ToolListHandler($collection, 2);
        $message = new Request(1, 'tools/list', []);
        $response = $handler->createResponse($message);
        $this->assertCount(2, $response->result['tools']);
        $this->assertArrayHasKey('nextCursor', $response->result);
    }
}
