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

interface ToolAnnotationsInterface
{
    /**
     * @return bool|null If true, the tool may perform destructive updates to its environment. If false, the tool performs only additive updates.
     *
     * (This property is meaningful only when readOnlyHint == false)
     *
     * Default: true
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#toolannotations-destructivehint
     */
    public function getDestructiveHint(): ?bool;

    /**
     * @return bool|null If true, calling the tool repeatedly with the same arguments will have no additional effect on the its environment.
     *
     * (This property is meaningful only when readOnlyHint == false)
     *
     * Default: false
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#toolannotations-idempotenthint
     */
    public function getIdempotentHint(): ?bool;

    /**
     * @return bool|null If true, this tool may interact with an “open world” of external entities. If false, the tool’s domain of interaction is closed. For example, the world of a web search tool is open, whereas that of a memory tool is not.
     *
     * Default: true
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#toolannotations-openworldhint
     */
    public function getOpenWorldHint(): ?bool;

    /**
     * @return bool|null If true, the tool does not modify its environment.
     *
     * Default: false
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#toolannotations-readonlyhint
     */
    public function getReadOnlyHint(): ?bool;

    /**
     * @return string|null A human-readable title for the tool
     *
     * @see https://modelcontextprotocol.io/specification/2025-06-18/schema#toolannotations-title
     */
    public function getTitle(): ?string;
}
