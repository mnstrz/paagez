<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateController extends Command
{
    use TraitModule;

    protected $signature = 'paagez:controller {name?} {--module=}';
    
    protected $description = 'Create controller';

    protected $directory = '';

    public function handle()
    {
        if(!$this->option('module') || !$this->argument('name'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create controller\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>name</>        Controller class name\n");
            $this->line("<fg=yellow>--module</>         Module name\n");
            $this->line("-----------------------------------\n");
            if(!$this->argument('name'))
            {
                $name = $this->ask('Please enter the controller name');
                $this->input->setArgument('name', $name);
            }
            if(!$this->option('module'))
            {
                $module = $this->ask('Please enter the module name');
                $this->input->setOption('module', $module);
            }
        }
        if($this->initials($this->option('module')))
        {
            return 0;
        }
        if($this->strings($this->argument('name'),"/^[A-Za-z0-9_\/\\\\-]+$/"))
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
        $this->line("\nCreating controller...\n");
        try {
            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/Controllers".$this->module_extra_path)) {
                \File::makeDirectory($this->module_path."/Controllers".$this->module_extra_path, 0755, true);
            }
            $filePath = $this->module_path . '/Controllers'.$this->module_extra_path.'/'.$this->studly_case.'.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.'\Controllers'.$this->extra_namespace.';

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class '.$this->studly_case.' extends Controller {



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