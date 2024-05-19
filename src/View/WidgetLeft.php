<?php

namespace Monsterz\Paagez\View;

use Illuminate\View\Component;

class WidgetLeft extends Component
{

    public $id = '';

    public $contents = '';

    public function __construct()
    {
        $this->id = \Str::random(10);

        $this->contents = $this->getContents();
    }

    protected function getContents()
    {

    }

    public function render()
    {
        return view(config('paagez.theme').'::components.admin.widget-left');
    }
}