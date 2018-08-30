<?php

namespace Api\Posts\Services;

use Api\Posts\Events\PostWasCreated;
use Api\Posts\Events\PostWasDeleted;
use Api\Posts\Events\PostWasUpdated;
use Api\Posts\Exceptions\PostNotFoundException;
use Api\Posts\Repositories\PostRepository;
use Illuminate\Auth\AuthManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class PostService
{
    private $auth;

    private $database;

    private $dispatcher;

    private $postRepository;

    public function __construct(
        AuthManager $auth,
        DatabaseManager $database,
        Dispatcher $dispatcher,
        PostRepository $postRepository
    ) {
        $this->auth = $auth;
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->postRepository = $postRepository;
    }

    public function getAll($options = [])
    {
        return $this->postRepository->getJoined($options);
    }

    public function getById($postId, array $options = [])
    {
        $post = $this->getRequestedPost($postId);

        return $post;
    }

    public function create($data)
    {
        $post = $this->postRepository->create($data);

        $this->dispatcher->fire(new PostWasCreated($post));

        return $post;
    }

    public function update($postId, array $data)
    {
        $post = $this->getRequestedPost($postId);

        $this->postRepository->update($post, $data);

        $this->dispatcher->fire(new PostWasUpdated($post));

        return $post;
    }

    public function delete($postId)
    {
        $post = $this->getRequestedPost($postId);

        $this->postRepository->delete($postId);

        $this->dispatcher->fire(new PostWasDeleted($post));
    }

    private function getRequestedPost($postId)
    {
        $post = $this->postRepository->getJoinedById($postId);

        if (is_null($post)) {
            throw new PostNotFoundException();
        }

        return $post;
    }
}
