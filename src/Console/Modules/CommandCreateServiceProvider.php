<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateServiceProvider extends Command
{
    use TraitModule;

	protected $signature = 'paagez:module-service {--module=}';
	
	protected $description = 'Create module service providers';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create module service providers\n");
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
        if($this->makeFile())
        {
            return 0;
        }
        return 0;
    }

    public function makeFile()
    {
        $this->line("\nCreating module service providers...\n");
        try{
            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            $filePath = $this->module_path . '/' . 'ModuleServiceProvider.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.';

use Monsterz\Paagez\Classes\PaagezProvider;

class ModuleServiceProvider extends PaagezProvider
{
    public function module_boot()
    {
        
    }

    public function module_register()
    {
        
    }
}';
            \File::put($filePath, $fileContent);
            $this->info("$filePath ........................................... Success\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}