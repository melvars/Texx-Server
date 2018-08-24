<?php

namespace Api\Posts\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('The post was not found.');
    }
}
