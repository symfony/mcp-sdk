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

use Symfony\AI\McpSdk\Capability\Tool\CollectionInterface;
use Symfony\AI\McpSdk\Capability\Tool\MetadataInterface;
use Symfony\AI\McpSdk\Message\Request;
use Symfony\AI\McpSdk\Message\Response;

final class ToolListHandler extends BaseRequestHandler
{
    public function __construct(
        private readonly CollectionInterface $collection,
        private readonly int $pageSize = 20,
    ) {
    }

    public function createResponse(Request $message): Response
    {
        $nextCursor = null;
        $tools = array_map(function (MetadataInterface $tool) use (&$nextCursor) {
            $nextCursor = $tool->getName();
            $inputSchema = $tool->getInputSchema();

            return [
                'name' => $tool->getName(),
                'description' => $tool->getDescription(),
                'inputSchema' => [] === $inputSchema ? [
                    'type' => 'object',
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                ] : $inputSchema,
            ];
        }, $this->collection->getMetadata($this->pageSize, $message->params['cursor'] ?? null));

        $result = [
            'tools' => $tools,
        ];

        if (null !== $nextCursor && \count($tools) === $this->pageSize) {
            $result['nextCursor'] = $nextCursor;
        }

        return new Response($message->id, $result);
    }

    protected function supportedMethod(): string
    {
        return 'tools/list';
    }
}
