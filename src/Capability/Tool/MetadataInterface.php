<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Capability\Tool;

/**
 * @see {https://modelcontextprotocol.io/specification/2025-06-18/schema#tool}
 */
interface MetadataInterface extends IdentifierInterface
{
    /**
     * @return string|null A human-readable description of the tool.
     *                     This can be used by clients to improve the LLM’s understanding of available tools. It can be thought of like a “hint” to the model
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#tool-description
     */
    public function getDescription(): ?string;

    /**
     * @return array{
     *   type?: 'object',
     *   required?: list<string>,
     *   properties?: array<string, array{
     *       type: string,
     *       description?: string,
     *   }>,
     * }
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#tool-inputschema
     */
    public function getInputSchema(): array;

    /**
     * @return array{
     *   type?: 'object',
     *   required?: list<string>,
     *   properties?: array<string, array{
     *       type: string,
     *       description?: string,
     *   }>,
     * }|null
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#tool-outputschema
     */
    public function getOutputSchema(): ?array;

    /**
     * @return string|null Intended for UI and end-user contexts — optimized to be human-readable and easily understood, even by those unfamiliar with domain-specific terminology.
     *
     * If not provided, the name should be used for display (except for Tool, where annotations.title should be given precedence over using name, if present).
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#tool-title
     */
    public function getTitle(): ?string;

    /**
     * @return ToolAnnotationsInterface|null Additional properties describing a Tool to clients.
     *
     * NOTE: all properties in ToolAnnotations are hints. They are not guaranteed to provide a faithful description of tool behavior (including descriptive properties like title).
     *
     * Clients should never make tool use decisions based on ToolAnnotations received from untrusted servers.
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#tool-annotations
     */
    public function getAnnotations(): ?ToolAnnotationsInterface;
}
