<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateNavigationTab extends Command
{
    use TraitModule;

    protected $signature = 'paagez:tab {--module=}';
    
    protected $description = 'Create tab';

    protected $directory = '';

    public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create nav\n");
            $this->line("-----------------------------------\n");
            $this->info("paagez:tab {--module=}\n\n");
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
        $this->makeFile();
        return 0;
    }

    public function makeFile()
    {
        $this->line("\nCreating nav...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Navigations")) {
                \File::makeDirectory($this->module_path."/Navigations", 0755, true);
            }
            $filePath = $this->module_path . '/Navigations/Tab.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Navigations;

use Monsterz\Paagez\Classes\Navigations\ModuleTab;

class Tab extends ModuleTab
{
    public function register()
    {
        $this->add_tab_group("tab-'.$this->module_name.'",[
            url("/".config("paagez.prefix")."/'.$this->module_name.'/index") => __("'.$this->module_title.'"),
            url("/".config("paagez.prefix")."/'.$this->module_name.'/create") => __("Create '.$this->module_title.'"),
            url("/".config("paagez.prefix")."/'.$this->module_name.'/edit",1) => __("Edit '.$this->module_title.'")
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