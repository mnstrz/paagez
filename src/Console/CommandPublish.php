<?php

namespace Monsterz\Paagez\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandPublish extends Command
{
	protected $signature = 'paagez:publish';
	
	protected $description = 'Publish';

	protected $directory = '';

	public function handle()
    {
        $this->publishPackages();
    }

    public function publishPackages()
    {
        $this->call('vendor:publish',['--provider' => \Spatie\Permission\PermissionServiceProvider::class]);
        $this->call('vendor:publish',['--provider' => \LaravelPWA\Providers\LaravelPWAServiceProvider::class]);
        $this->call('vendor:publish',['--provider' => \Biscolab\ReCaptcha\ReCaptchaServiceProvider::class]);
        $this->call('vendor:publish',['--provider' => \PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider::class]);
        $this->call('vendor:publish',['--provider' => \Monsterz\Paagez\Providers\PaagezServiceProvider::class]);
        $this->call('paagez:theme-assets');
        $this->call('jwt:secret');
        $this->call('notifications:table');
    }
}