<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Auth\User;

class AdminController extends Controller
{
    public function Dashboard()
    {
        $this->checkAdmin();
        return view('admin.dashboard', [
            'user' => Auth::user(),
            'cpu_load' => round(sys_getloadavg()[1]/(trim(shell_exec("grep -P '^processor' /proc/cpuinfo|wc -l")) + 1)*100, 0)
        ]);
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
