<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\AI\McpSdk\Capability;

use Symfony\AI\McpSdk\Capability\Prompt\CollectionInterface;
use Symfony\AI\McpSdk\Capability\Prompt\IdentifierInterface;
use Symfony\AI\McpSdk\Capability\Prompt\MetadataInterface;
use Symfony\AI\McpSdk\Capability\Prompt\PromptGet;
use Symfony\AI\McpSdk\Capability\Prompt\PromptGetResult;
use Symfony\AI\McpSdk\Capability\Prompt\PromptGetterInterface;
use Symfony\AI\McpSdk\Exception\InvalidCursorException;
use Symfony\AI\McpSdk\Exception\PromptGetException;
use Symfony\AI\McpSdk\Exception\PromptNotFoundException;

/**
 * A collection of prompts. All prompts need to implement IdentifierInterface.
 */
class PromptChain implements PromptGetterInterface, CollectionInterface
{
    public function __construct(
        /**
         * @var IdentifierInterface[]
         */
        private readonly array $items,
    ) {
    }

    public function getMetadata(int $count, ?string $lastIdentifier = null): iterable
    {
        $found = null === $lastIdentifier;
        foreach ($this->items as $item) {
            if (!$item instanceof MetadataInterface) {
                continue;
            }

            if (false === $found) {
                $found = $item->getName() === $lastIdentifier;
                continue;
            }

            yield $item;
            if (--$count <= 0) {
                break;
            }
        }

        if (!$found) {
            throw new InvalidCursorException($lastIdentifier);
        }
    }

    public function get(PromptGet $input): PromptGetResult
    {
        foreach ($this->items as $item) {
            if ($item instanceof PromptGetterInterface && $input->name === $item->getName()) {
                try {
                    return $item->get($input);
                } catch (\Throwable $e) {
                    throw new PromptGetException($input, $e);
                }
            }
        }

        throw new PromptNotFoundException($input);
    }
}
