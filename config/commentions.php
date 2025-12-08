<?php /** @noinspection SpellCheckingInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection SpellCheckingInspection */
/** @noinspection SpellCheckingInspection */

return [
    /*
    |--------------------------------------------------------------------------
    | Table name configurations
    |--------------------------------------------------------------------------
    */
    'tables' => [
        'comments' => 'res_comments',
        'comment_reactions' => 'res_reactions',
        'comment_subscriptions' => 'res_subscriptions',
    ],

    /*
    |--------------------------------------------------------------------------
    | Commenter model configuration
    |--------------------------------------------------------------------------
    */
    'commenter' => [
        'model' => \App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Comment model configuration
    |--------------------------------------------------------------------------
    */
    'comment' => [
        'model' => \Kirschbaum\Commentions\Comment::class,
        'policy' => \Kirschbaum\Commentions\Policies\CommentPolicy::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reactions
    |--------------------------------------------------------------------------
    */
    'reactions' => [
        'allowed' => ['👍', '❤️', '😂', '😮', '😢', '🤔'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Subscriptions
    |--------------------------------------------------------------------------
    */
    'subscriptions' => [
        // When true, subscribed users will also receive the same event as mentions
        // (UserWasMentionedEvent). When false, a distinct
        // UserIsSubscribedToCommentableEvent will be dispatched instead.
        'dispatch_as_mention' => env('COMMENTIONS_SUBSCRIPTIONS_DISPATCH_AS_MENTION', false),
        // Controls whether the subscribers list is shown in the sidebar UI
        'show_subscribers' => env('COMMENTIONS_SUBSCRIPTIONS_SHOW_SUBSCRIBERS', true),
        // Automatically subscribe the author when they add a comment
        'auto_subscribe_on_comment' => env('COMMENTIONS_SUBSCRIPTIONS_AUTO_SUBSCRIBE_ON_COMMENT', true),
        // Automatically subscribe a user when they are mentioned in a comment
        'auto_subscribe_on_mention' => env('COMMENTIONS_SUBSCRIPTIONS_AUTO_SUBSCRIBE_ON_MENTION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications (opt-in)
    |--------------------------------------------------------------------------
    |
    | Configure notification delivery when a user is mentioned in a comment.
    | Disabled by default; enable and choose the channels you want to use.
    |
    */
    'notifications' => [
        'mentions' => [
            'enabled' => env('COMMENTIONS_NOTIFICATIONS_MENTIONS_ENABLED', false),

            'channels' => explode(',', env('COMMENTIONS_NOTIFICATIONS_MENTIONS_CHANNELS', 'mail')),

            'listener' => \Kirschbaum\Commentions\Listeners\SendUserMentionedNotification::class,
            'notification' => \Kirschbaum\Commentions\Notifications\UserMentionedInComment::class,

            'mail' => [
                'subject' => env('COMMENTIONS_NOTIFICATIONS_MENTIONS_MAIL_SUBJECT', 'You were mentioned in a comment'),
            ],
        ],
    ],
];
