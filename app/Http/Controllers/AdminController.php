<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\User;

class AdminController extends Controller
{
    public function Overview()
    {
        $this->checkAdmin();
        return view('admin.overview', ['user' => Auth::user()]);
    }

    private function checkAdmin()
    {
        $isAdmin = User::findOrFail(Auth::user()->id)->admin;
        if ($isAdmin !== 1) {
            abort(403, 'Sorry, you are not an administrator.');
        }

        return true;
    }
}
