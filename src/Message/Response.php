<?php

declare(strict_types=1);

namespace PhpLlm\McpSdk\Message;

final readonly class Response implements \JsonSerializable
{
    /**
     * @param array<string, mixed> $result
     */
    public function __construct(
        public string|int $id,
        public array $result = [],
    ) {
    }

    /**
     * @return array{jsonrpc: string, id: string|int, result: array<string, mixed>}
     */
    public function jsonSerialize(): array
    {
        return [
            'jsonrpc' => '2.0',
            'id' => $this->id,
            'result' => $this->result,
        ];
    }
}
