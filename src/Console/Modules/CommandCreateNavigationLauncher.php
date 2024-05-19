<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateNavigationLauncher extends Command
{
    use TraitModule;

	protected $signature = 'paagez:launcher {--module=}';
	
	protected $description = 'Create launcher';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create launcher\n");
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
        if($this->makeFile())
        {
            return 0;
        }
        return 0;
    }

    public function makeFile()
    {
        $this->line("\nCreating launcher...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Navigations")) {
                \File::makeDirectory($this->module_path."/Navigations", 0755, true);
            }
            $filePath = $this->module_path . '/Navigations/Launcher.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Navigations;

use Monsterz\Paagez\Classes\Navigations\ModuleLauncher;

class Launcher extends ModuleMenu
{
    public function register()
    {
        $this->add_launcher([
          "order" => 1,
          "name" => "'.$this->module_name.'",
          "label" => __("'.$this->module_title.'"),
          "icon" => "",
          "image" => "/theme/images/logo.jpg",
          "url" => "#"
        ]);
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