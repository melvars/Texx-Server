<?php

namespace Api\Posts;

use Infrastructure\Events\EventServiceProvider;
use Api\Posts\Events\PostWasCreated;
use Api\Posts\Events\PostWasDeleted;
use Api\Posts\Events\PostWasUpdated;

class PostServiceProvider extends EventServiceProvider
{
    protected $listen = [
        PostWasCreated::class => [
            // listeners for when a post is created
        ],
        PostWasDeleted::class => [
            // listeners for when a post is deleted
        ],
        PostWasUpdated::class => [
            // listeners for when a post is updated
        ]
    ];
}
