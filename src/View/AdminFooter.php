<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class AdminFooter extends Component
{
    public $content = '';

    public function __construct()
    {
        $this->content = config('paagez.footer');
    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.footer');
    }
}