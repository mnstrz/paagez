<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class Navbar extends Component
{

    public function __construct()
    {

    }

    public function render()
    {
        return view(config('paagez.theme').'::components.navbar');
    }
}