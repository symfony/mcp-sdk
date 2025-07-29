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
use Symfony\AI\McpSdk\Message\Request;
use Symfony\AI\McpSdk\Message\Response;

final class ToolListHandler extends BaseRequestHandler
{
    public function __construct(
        private readonly CollectionInterface $collection,
        private readonly ?int $pageSize = 20,
    ) {
    }

    public function createResponse(Request $message): Response
    {
        $nextCursor = null;
        $tools = [];

        $metadataList = $this->collection->getMetadata(
            $this->pageSize,
            $message->params['cursor'] ?? null
        );

        foreach ($metadataList as $tool) {
            $nextCursor = $tool->getName();
            $inputSchema = $tool->getInputSchema();
            $annotations = null === $tool->getAnnotations() ? [] : array_filter([
                'title' => $tool->getAnnotations()->getTitle(),
                'destructiveHint' => $tool->getAnnotations()->getDestructiveHint(),
                'idempotentHint' => $tool->getAnnotations()->getIdempotentHint(),
                'openWorldHint' => $tool->getAnnotations()->getOpenWorldHint(),
                'readOnlyHint' => $tool->getAnnotations()->getReadOnlyHint(),
            ], static fn ($value) => null !== $value);

            $tools[] = array_filter([
                'name' => $tool->getName(),
                'description' => $tool->getDescription(),
                'inputSchema' => [] === $inputSchema ? [
                    'type' => 'object',
                    '$schema' => 'http://json-schema.org/draft-07/schema#',
                ] : $inputSchema,
                'title' => $tool->getTitle(),
                'outputSchema' => $tool->getOutputSchema(),
                'annotations' => (object) $annotations,
            ], static fn ($value) => null !== $value);
        }

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
