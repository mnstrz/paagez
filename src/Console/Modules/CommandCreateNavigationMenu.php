<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateNavigationMenu extends Command
{
    use TraitModule;

    protected $signature = 'paagez:menu {--module=}';
    
    protected $description = 'Create menu';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create menu\n");
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
        $this->line("\nCreating menu...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Navigations")) {
                \File::makeDirectory($this->module_path."/Navigations", 0755, true);
            }
            $filePath = $this->module_path . '/Navigations/Menu.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Navigations;

use Monsterz\Paagez\Classes\Navigations\ModuleMenu;

class Menu extends ModuleMenu
{
    public function register()
    {
        $this->add_menu([
            "order" => 1,
            "name" => "'.$this->module_name.'",
            "label" => __("'.$this->module_title.'"),
            "icon" => "fa-solid fa-lock",
            "url" => "#"
        ]);
        $this->add_menu([
            "parent" => "'.$this->module_name.'",
            "order" => 1,
            "name" => "create_roles",
            "label" => __("Create New '.$this->module_title.'"),
            "url" => "#"
        ]);
        $this->add_menu([
            "parent" => "'.$this->module_name.'",
            "order" => 2,
            "name" => "list_of_roles",
            "label" => __("Lists of '.$this->module_title.'"),
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