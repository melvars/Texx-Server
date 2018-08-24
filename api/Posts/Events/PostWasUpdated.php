<?php

namespace Api\Posts\Events;

use Infrastructure\Events\Event;
use Api\Posts\Models\Post;

class PostWasUpdated extends Event
{
    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
