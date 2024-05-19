<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandNavigation extends Command
{
    use TraitModule;

	protected $signature = 'paagez:navigation {--module=} {--all} {--menu} {--nav} {--breadcrumb} {--tab} {--launcher}';
	
	protected $description = 'Create navigation';

	protected $directory = '';

    protected $navigations = [];

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create module\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>--module</>     Module name\n");
            $this->line("<fg=yellow>--all</>        All options\n");
            $this->line("<fg=yellow>--menu</>       Menu only\n");
            $this->line("<fg=yellow>--nav</>        Nav only\n");
            $this->line("<fg=yellow>--breadcrumb</> Breadcrumb only\n");
            $this->line("<fg=yellow>--tab</>        Tab only\n");
            $this->line("<fg=yellow>--launcher</>   Launcher only\n");
            $this->line("-----------------------------------\n");
            $module = $this->ask('Please enter the module name');
            $this->input->setOption('module', $module);
            $this->inputOptions();
        }
        if($this->initials($this->option('module')))
        {
            return 0;
        }
        if($this->getOptions())
        {
            return 0;
        }
        $this->runCommands();
    }

    public function inputOptions($value=null)
    {
        $options = ['all','menu','nav','breadcrumb','tab','launcher'];
        $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
        foreach ($roles as $key => $role) {
            $options[] = $role->name;
        } 
        $option = $this->ask('Please enter the option <fg=yellow>['.implode(",",$options).']</>');
        if(!$option || !$this->hasOption($option))
        {
            $this->comment("At least please choose one of navigation type\n");
            return $this->inputOptions($option);
        }else{
            if(!in_array($option,$options))
            {
                $this->comment("At least please choose one of navigation type\n");
                return $this->inputOptions($option);
            }else{
                $this->input->setOption($option,1);
                return 0;
            }
        }
    }

    public function getOptions()
    {
        $navigations = [];
        if($this->option('all'))
        {
            $navigations = ['menu','nav','breadcrumb','tab','launcher'];
            $this->navigations = $navigations;
            return 0;
        }
        $navigations = [];
        if($this->option('menu'))
        {
            $navigations[] = 'menu';
        }
        if($this->option('nav'))
        {
            $navigations[] = 'nav';
        }
        if($this->option('breadcrumb'))
        {
            $navigations[] = 'breadcrumb';
        }
        if($this->option('tab'))
        {
            $navigations[] = 'tab';
        }
        if($this->option('launcher'))
        {
            $navigations[] = 'launcher';
        }
        if(!$navigations)
        {
            $this->comment("At least please choose one of navigation type\n");
            $this->inputOptions();
            $this->getOptions();
            return 0;
        }
        $this->navigations = $navigations;
        return 0;
    }

    public function runCommands()
    {
        try {
            foreach ($this->navigations as $key => $navigation) {
                $this->call("paagez:".$navigation,['--module' => $this->module_name]);
            }
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }
}