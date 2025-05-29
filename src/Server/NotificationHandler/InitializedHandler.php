<?php

declare(strict_types=1);

namespace PhpLlm\McpSdk\Server\NotificationHandler;

use PhpLlm\McpSdk\Message\Notification;

final class InitializedHandler extends BaseNotificationHandler
{
    protected function supportedNotification(): string
    {
        return 'initialized';
    }

    public function handle(Notification $notification): null
    {
        return null;
    }
}
