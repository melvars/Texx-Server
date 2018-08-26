<?php

namespace Infrastructure\Api\Controllers;

use Infrastructure\Http\Controller as BaseController;
use Infrastructure\Version;

class DefaultApiController extends BaseController
{
    public function index()
    {
        return response()->json([
            'message'   => 'Welcome to the official Texx API'
        ]);
    }
}
