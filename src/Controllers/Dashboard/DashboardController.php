<?php

namespace Monsterz\Paagez\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view(config('paagez.theme').'::dashboard.index');
    }
}
