<?php

namespace Api\Posts\Repositories;

use Api\Posts\Models\Post;
use Infrastructure\Database\Eloquent\Repository;

class PostRepository extends Repository
{
    public function getModel()
    {
        return new Post();
    }

    public function create(array $data)
    {
        $post = $this->getModel();

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

        $post->fill($data);
        $post->save();

        return $post;
    }

    public function update(Post $post, array $data)
    {
        $post->fill($data);

        $post->save();

        return $post;
    }
}
