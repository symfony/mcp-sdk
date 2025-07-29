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

interface IdentifierInterface
{
    /**
     * @return string intended for programmatic or logical use, but used as a display name in past specs or fallback (if title isn’t present)
     */
    public function getName(): string;
}
