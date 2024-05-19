<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

use Monsterz\Paagez\Classes\ModulePackage;

class CommandPackageUpdate extends Command
{
	use TraitModule;

    protected $signature = 'paagez:package-update {--module=}';
    
    protected $description = 'Update module package';

    protected $directory = '';

    public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez module package update\n");
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
        $this->line("\nUpdating module packages for <fg=yellow>module\\$this->module_name</>...\n");
        try {

            $class = $this->namespace."\\Module";
            if(class_exists($class))
            {
                $module = new $class;
                $packages = new ModulePackage;
                $packages->update($module->packages);
            }else{
                $this->error("<fg=yellow>module\\$this->module_name</> not found");
                return 1;
            }
            $this->info("<fg=yellow>module\\$this->module_name</> packages ........................................... Success\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}