<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandModule extends Command
{
    use TraitModule;

	protected $signature = 'paagez:module {--module=} {--all} {--init} {--provider} {--routes} {--navigation} {--assets} {--install} {--update}';
	
	protected $description = 'Create module';

	protected $directory = '';

    protected $modules = [];

	public function handle()
    {
        if(!$this->option('module') && $this->option('install'))
        {
            $this->call('paagez:install-module',['--all' => 1]);
            return 0;
        }
        if($this->option('module') && $this->option('install'))
        {
            $this->call('paagez:install-module',['--module' => $this->option('module')]);
            return 0;
        }
        if(!$this->option('module') && $this->option('update'))
        {
            $this->call('paagez:update-module',['--all' => 1]);
            return 0;
        }
        if($this->option('module') && $this->option('update'))
        {
            $this->call('paagez:update-module',['--module' => $this->option('module')]);
            return 0;
        }
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create module\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>--module</>     Module name\n");
            $this->line("<fg=yellow>--all</>        All options\n");
            $this->line("<fg=yellow>--init</>       Initials only\n");
            $this->line("<fg=yellow>--provider</>   Providers only\n");
            $this->line("<fg=yellow>--routes</>     Routes only\n");
            $this->line("<fg=yellow>--navigation</> Navigation only\n");
            $this->line("<fg=yellow>--assets</>     Assets only\n");
            $this->line("-----------------------------------\n");
            $module = $this->ask('Please enter the module name');
            $this->input->setOption('module', $module);
            if(!$this->option('all') && !$this->option('init') && !$this->option('provider') && !$this->option('routes') && !$this->option('navigation') && !$this->option('assets'))
            {
                $this->inputOptions();
            }
        }
        if($this->initials($this->option('module')))
        {
            return 0;
        }
        if($this->getOptions())
        {
            return 0;
        }
        if($this->option('install'))
        {
            $this->call('paagez:install-module',['--module',$this->module_name]);
            return 0;
        }
        if($this->option('update'))
        {
            $this->call('paagez:update-module',['--module',$this->module_name]);
            return 0;
        }
        $this->runCommands();
        return 0;
    }

    public function inputOptions($value=null)
    {
        $options = ['all','init','provider','routes','navigation','assets'];
        $roles = \DB::table('roles')->where('guard_name','web')->where('name','!=','admin')->get();
        foreach ($roles as $key => $role) {
            $options[] = $role->name;
        } 
        $option = $this->ask('Please enter the option <fg=yellow>['.implode(",",$options).']</>');
        if(!$option || !$this->hasOption($option))
        {
            $this->comment("At least please choose one of module option\n");
            return $this->inputOptions($option);
        }else{
            if(!in_array($option,$options))
            {
                $this->comment("At least please choose one of module option\n");
                return $this->inputOptions($option);
            }else{
                $this->input->setOption($option,1);
                return $option;
            }
        }
    }

    public function getOptions()
    {
        $modules = [];
        if($this->option('all'))
        {
            $modules = ['create-module','module-service','routes','navigation','module-assets'];
            $this->modules = $modules;
            return 0;
        }
        $modules = [];
        if($this->option('init'))
        {
            $modules[] = 'create-module';
        }
        if($this->option('provider'))
        {
            $modules[] = 'module-service';
        }
        if($this->option('assets'))
        {
            $modules[] = 'module-assets';
        }
        if($this->option('routes'))
        {
            $modules[] = 'routes';
        }
        if($this->option('navigation'))
        {
            $modules[] = 'navigation';
        }
        if(!$modules)
        {
            $this->comment("At least please choose one of module option\n");
            $this->inputOptions();
            return 1;
        }
        $this->modules = $modules;
        return 0;
    }

    public function runCommands()
    {
        try {
            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            foreach ($this->modules as $key => $module) {
                $this->call("paagez:".$module,['--module' => $this->module_name]);
            }
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }
}