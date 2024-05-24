<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandDbUpdate extends Command
{
	use TraitModule;

    protected $signature = 'paagez:db-update {--module=}';
    
    protected $description = 'Update module database';

    protected $directory = '';

    public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez module database update\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>--module</>         Module name\n");
            $this->line("-----------------------------------\n");
            $module = $this->ask('Please enter the module name');
            $this->input->setOption('module', $module);
        }
        if($this->initials($this->option('module')))
        {
            return 0;
        }
        if($this->strings($this->option('module')))
        {
            return 0;
        }
        if($this->runMigration())
        {
            return 0;
        }
        return 0;
    }

    public function runMigration()
    {
        $this->line("\nUpdating database for <fg=yellow>module\\$this->module_name</>...\n");
        try {
            if(!app()->runningInConsole())
            {
                $this->call("optimize:clear");
                $this->call("clear");
                $this->call("config:clear");
                $this->call("config:cache");
            }
            $this->line("\nUpdating database stucture for <fg=yellow>module\\$this->module_name</>...\n");
            $path = (app()->runningInConsole()) ? 'app/Modules/'.$this->module_name.'/database/migrations' : $this->module_path."/database/migrations";
            if(!file_exists($path))
            {
                $this->comment("<fg=yellow>module\\$this->module_name</> migration not found\n");
                return 1;
            }            
            $this->call('migrate',[
                '--path' => 'app/Modules/'.$this->module_name.'/database/migrations',
                '--force' => 1
            ]);

            $class = $this->namespace."\\Module";
            if(class_exists($class))
            {
                $module = new $class;
                if($module->seeders)
                {
                    $this->line("\nUpdating database seed for <fg=yellow>module\\$this->module_name</>...\n");
                    foreach ($module->seeders as $seeder) {
                        $this->call('db:seed', ['--class' => $seeder]);
                    }
                }
            }else{
                $this->error("<fg=yellow>module\\$this->module_name</> not found");
                return 1;
            }
            $this->info("<fg=yellow>module\\$this->module_name</> database update...................................... Success\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}