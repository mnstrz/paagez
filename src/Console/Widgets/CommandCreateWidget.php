<?php

namespace Monsterz\Paagez\Console\Widgets;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Monsterz\Paagez\Console\Modules\TraitModule;

class CommandCreateWidget extends Command
{
    use TraitModule, ResourceInit, ResourceWidget, ResourceView;

	protected $signature = 'paagez:widget {name?} {--module=} {--main} {--side} {--left} {--right}';
	
	protected $description = 'Create widget';

	protected $directory = '';

    public $title = '';

    public $type = '';

	public function handle()
    {
        if(!$this->argument('name'))
        {
            $this->inputName();
        }

        if(!$this->option('module'))
        {
            $this->inputModule();
        }

        if(!$this->option('main') && !$this->option('side') && !$this->option('left') && !$this->option('right'))
        {
            $this->inputType();
        }
        if($this->option('main'))
        {
            $this->type = 'main';
        }elseif($this->option('side'))
        {
            $this->type = 'side';
        }elseif($this->option('left'))
        {
            $this->type = 'left';
        }else{
            $this->type = 'right';
        }

        if($this->initials($this->option('module')))
        {
            return 0;
        }

        if($this->initResourceName($this->argument('name')))
        {
            return 0;
        }

        if($this->initWidget())
        {
            return 0;
        }

        if($this->initView())
        {
            return 0;
        }

        $this->info("Successfully create widget '{$this->title}'");
        return 0;
    }

    protected function initDatabase()
    {
        $this->call('migrate');
        $pdo = config('database.default');

        $role_table = config("database.$pdo.prefix").'roles';
        if (!\DB::table($role_table)->first()) {
            $this->call('db:seed', ['--class' => \Monsterz\Paagez\Database\Seeders\RoleSeeder::class]);
        }
    }

    protected function initAssets()
    {
        $this->call('storage:link');
        $this->call('paagez:theme-assets');
    }
}