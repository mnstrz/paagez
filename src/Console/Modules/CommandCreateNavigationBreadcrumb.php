<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateNavigationBreadcrumb extends Command
{
    use TraitModule;

	protected $signature = 'paagez:breadcrumb {--module=}';
	
	protected $description = 'Create breadcrumb';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create breadcrumb\n");
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
        $this->line("\nCreating breadcrumb...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Navigations")) {
                \File::makeDirectory($this->module_path."/Navigations", 0755, true);
            }
            if (!\File::exists($this->module_path."/Navigations/Breadcrumbs")) {
                \File::makeDirectory($this->module_path."/Navigations/Breadcrumbs", 0755, true);
            }
            $filePath = $this->module_path . '/Navigations/Breadcrumb.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Navigations;

use Monsterz\Paagez\Classes\Navigations\ModuleBreadcrumb;

class Breadcrumb extends ModuleBreadcrumb
{
    public function register()
    {
        // $this->add_breadcrumb(url("/'.config('paagez.prefix').'/'.$this->module_name.'"),[
        //      url("/") => "<i class='."'".'fa fa-home'."'".'></i>",
        //     url("/'.config('paagez.prefix').'/'.$this->module_name.'") => __("'.$this->module_title.'"),
        //    url("/'.config('paagez.prefix').'/'.$this->module_name.'/create") => __("Create '.$this->module_title.'")
        // ]);
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