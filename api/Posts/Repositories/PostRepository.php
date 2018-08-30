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

    public function getJoined($options)
    {
        $query = Post::query()->with('user')->with('post_type');
        $this->applyResourceOptions($query, $options);
        $posts = $query->get();
        $joinedPosts = [];

        foreach ($posts as $post) {
            $postType = 'Api\Posts\Models\\' . $post["post_type"]["type"] . 'Post';
            $postTypeClass = new $postType();
            $post["post"] = $postTypeClass::query()->where('id', $post->id)->first();
            array_push($joinedPosts, $post);
        }
        return $joinedPosts;
    }

    public function getJoinedById($postId)
    {
        $query = Post::query()->with('user')->with('post_type')->where('id', $postId);
        $post = $query->first();

        $postType = 'Api\Posts\Models\\' . $post["post_type"]["type"] . 'Post';
        $postTypeClass = new $postType();
        $post["post"] = $postTypeClass::query()->where('id', $post["id"])->first();
        return $post;
    }

    public function create(array $data)
    {
        $post = $this->getModel();

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
