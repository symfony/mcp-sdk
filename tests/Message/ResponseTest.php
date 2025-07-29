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
use PHPUnit\Framework\TestCase;
use Symfony\AI\McpSdk\Message\Response;

#[Small]
#[CoversClass(Response::class)]
final class ResponseTest extends TestCase
{
    public function testWithIntegerId()
    {
        $response = new Response(1, ['foo' => 'bar']);
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => ['foo' => 'bar'],
        ];

        $this->assertSame($expected, $response->jsonSerialize());
    }

    public function testWithStringId()
    {
        $response = new Response('abc', ['foo' => 'bar']);
        $expected = [
            'jsonrpc' => '2.0',
            'id' => 'abc',
            'result' => ['foo' => 'bar'],
        ];

        $this->assertSame($expected, $response->jsonSerialize());
    }
}
