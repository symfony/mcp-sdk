<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Tests\Message;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Small;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\AI\McpSdk\Exception\InvalidInputMessageException;
use Symfony\AI\McpSdk\Message\Factory;
use Symfony\AI\McpSdk\Message\Notification;
use Symfony\AI\McpSdk\Message\Request;

#[Small]
#[CoversClass(Factory::class)]
final class FactoryTest extends TestCase
{
    private Factory $factory;

    protected function setUp(): void
    {
        $this->factory = new Factory();
    }

    /**
     * @param iterable<mixed> $items
     */
    private function first(iterable $items): mixed
    {
        foreach ($items as $item) {
            return $item;
        }

        return null;
    }

    #[Test]
    public function createRequest(): void
    {
        $json = '{"jsonrpc": "2.0", "method": "test_method", "params": {"foo": "bar"}, "id": 123}';

        $result = $this->first($this->factory->create($json));

        $this->assertInstanceOf(Request::class, $result);
        $this->assertSame('test_method', $result->method);
        $this->assertSame(['foo' => 'bar'], $result->params);
        $this->assertSame(123, $result->id);
    }

    #[Test]
    public function createNotification(): void
    {
        $json = '{"jsonrpc": "2.0", "method": "notifications/test_event", "params": {"foo": "bar"}}';

        $result = $this->first($this->factory->create($json));

        $this->assertInstanceOf(Notification::class, $result);
        $this->assertSame('notifications/test_event', $result->method);
        $this->assertSame(['foo' => 'bar'], $result->params);
    }

    #[Test]
    public function invalidJson(): void
    {
        $this->expectException(\JsonException::class);

        $this->first($this->factory->create('invalid json'));
    }

    #[Test]
    public function missingMethod(): void
    {
        $result = $this->first($this->factory->create('{"jsonrpc": "2.0", "params": {}, "id": 1}'));
        $this->assertInstanceOf(InvalidInputMessageException::class, $result);
        $this->assertEquals('Invalid JSON-RPC request, missing "method".', $result->getMessage());
    }

    #[Test]
    public function batchMissingMethod(): void
    {
        $results = $this->factory->create('[{"jsonrpc": "2.0", "params": {}, "id": 1}, {"jsonrpc": "2.0", "method": "notifications/test_event", "params": {}, "id": 2}]');

        $results = iterator_to_array($results);
        $result = array_shift($results);
        $this->assertInstanceOf(InvalidInputMessageException::class, $result);
        $this->assertEquals('Invalid JSON-RPC request, missing "method".', $result->getMessage());

        $result = array_shift($results);
        $this->assertInstanceOf(Notification::class, $result);
    }
}
