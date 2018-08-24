<?php

namespace Api\Posts\Controllers;

use Illuminate\Http\Request;
use Infrastructure\Http\Controller;
use Api\Posts\Requests\CreatePostRequest;
use Api\Posts\Services\PostService;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getAll()
    {
        $resourceOptions = $this->parseResourceOptions();

        $data = $this->postService->getAll($resourceOptions);
        $parsedData = $this->parseData($data, $resourceOptions, 'posts');

        return $this->response($parsedData);
    }

    public function getById($postId)
    {
        $resourceOptions = $this->parseResourceOptions();

        $data = $this->postService->getById($postId, $resourceOptions);
        $parsedData = $this->parseData($data, $resourceOptions, 'post');

        return $this->response($parsedData);
    }

    public function create(CreatePostRequest $request)
    {
        $data = $request->get('post', []);

        return $this->response($this->postService->create($data), 201);
    }

    public function update($postId, Request $request)
    {
        $data = $request->get('post', []);

        return $this->response($this->postService->update($postId, $data));
    }

    public function delete($postId)
    {
        return $this->response($this->postService->delete($postId));
    }
}
