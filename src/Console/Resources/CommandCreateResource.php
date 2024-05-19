<?php

namespace Monsterz\Paagez\Console\Resources;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Monsterz\Paagez\Console\Modules\TraitModule;

class CommandCreateResource extends Command
{
    use TraitModule, ResourceInit, ResourceController, ResourceRequest, ResourceForm, ResourceIndex, ResourceShow, ResourceBreadcrumb, ResourceDatabase;

	protected $signature = 'paagez:resource {--module=} {--model=}';
	
	protected $description = 'Create resource';

	protected $directory = '';

    public $title = '';

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->inputModule();
        }

        if(!$this->option('model'))
        {
            $this->inputModel();
        }

        $this->inputTitle();

        if($this->initials($this->option('module')))
        {
            return 0;
        }

        if($this->initResourceName($this->title,$this->option('model')))
        {
            return 0;
        }

        if($this->validateResource())
        {
            return 0;
        }

        if($this->initController())
        {
            return 0;
        }

        if($this->initRequest())
        {
            return 0;
        }

        if($this->initBreadcrumb())
        {
            return 0;
        }

        if($this->initIndex())
        {
            return 0;
        }

        if($this->initForm())
        {
            return 0;
        }
        if($this->initShow())
        {
            return 0;
        }
        $this->info("Successfully create resource '{$this->title}'");
        $this->line("Insert this script into <fg=yellow>{$this->module_path}/routes/admin.php</>:");
        $this->info("Route::resource('/$this->model_var',App\Modules\Student\Controllers\\".$this->controller_name."::class);");
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