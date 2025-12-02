<?php

declare(strict_types=1);

namespace App\Features;

use Illuminate\Support\Lottery;

/**
 * Feature flag: SendCommentSubscriptionNotifications
 *
 * Defines the feature flag for enabling or disabling comment subscription notifications.
 *
 * This feature is currently implemented to be always active, meaning subscription notifications
 * will always be enabled unless the 'resolve' method logic is changed (e.g., to use a Lottery for gradual rollout or an environment check).
 *
 * @see \App\Listeners\SendSubscribedUserNotification
 * @package App\Features
 */
final readonly class SendCommentSubscriptionNotifications
{
    /**
     * Resolve the current state of the feature flag.
     * This method determines whether the feature is active for the current context (user, tenant, etc.)
     *
     * Return true, indicating the feature is permanently active.
     */
    public function resolve(): true
    {
        return true;
    }
}
