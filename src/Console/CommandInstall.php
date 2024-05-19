<?php

namespace Monsterz\Paagez\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandInstall extends Command
{

	protected $signature = 'paagez:install';
	
	protected $description = 'Installation';

	protected $directory = '';

	public function handle()
    {
        if($this->initDatabase())
        {
            return 0;
        }
        if($this->initAssets())
        {
            return 0;
        }
        $this->call('paagez:publish');
        $url = url(config('paagez.prefix'));
        $this->info("Installation success. use <fg=yellow>{$url}</> to continue installation\n");
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