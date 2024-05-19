<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateNavigationNav extends Command
{
    use TraitModule;

    protected $signature = 'paagez:nav {--module=}';
    
    protected $description = 'Create nav';

    protected $directory = '';

    public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create nav\n");
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
        $this->line("\nCreating nav...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Navigations")) {
                \File::makeDirectory($this->module_path."/Navigations", 0755, true);
            }
            $filePath = $this->module_path . '/Navigations/Nav.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Navigations;

use Monsterz\Paagez\Classes\Navigations\ModuleNav;

class Nav extends ModuleNav
{
    public function register()
    {
        $this->add_nav([
            "order" => 1,
            "name" => "'.$this->module_name.'",
            "icon" => "",
            "image" => "/theme/images/logo.jpg",
            "url" => "#"
        ]);
        $this->add_nav([
            "parent" => "'.$this->module_name.'",
            "order" => 1,
            "name" => "create_roles",
            "label" => __("Create New '.$this->module_title.'"),
            "url" => "#"
        ]);
        $this->add_nav([
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