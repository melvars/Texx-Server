<?php

namespace Api\Posts\Events;

use Infrastructure\Events\Event;
use Api\Posts\Models\Post;

class PostWasDeleted extends Event
{
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
