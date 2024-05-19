<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateModule extends Command
{
    use TraitModule;

	protected $signature = 'paagez:create-module {--module=}';
	
	protected $description = 'Create module';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create module\n");
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
        $this->line("\nCreating module...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            $filePath = $this->module_path . '/' . 'Module.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '<?php

namespace '.$this->namespace.';

use Illuminate\Support\Facades\Validator;
use Monsterz\Paagez\Classes\Modular;
use Monsterz\Paagez\Classes\ModuleConfig;

class Module extends Modular
{

    public $name = "'.$this->module_name.'";

    public $title = "'.$this->module_title.'";

    public $publisher = "paagez";

    public $email_publisher = "";

    public $website_publisher = "";

    public $route_prefix = "'.$this->module_route_prefix.'";

    public $route_name = "'.$this->module_route_name.'";

    public $version = '.$this->module_version.';

    public function update()
    {
        if($this->latest_version < '.$this->module_version.')
        {
           // $this->seeders[] = \App\Modules\Roles\database\seeders\FooSeeder::class;
           // $this->packages[] = [
           //   ["foo/bar"=>"*"]
           //   ["foo/bar"=>"*"]
           // ];
           // $this->artisan_call[] = [
           //  ["foo"=>["bar"=>"baz"]]
           // ];
        }
    }

    public function register()
    {
        // do something logical here on initial modules
    }

    public function config()
    {
        $config = ModuleConfig::get($this->name);
        return view($this->name."::config",compact("config"));
    }

    public function update_config()
    {
        $request = request()->except("_token");
        $validator = Validator::make($request, [
            //"name" => "required|string|max:255",
        ]);
        if ($validator->fails()) {
            return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        ModuleConfig::update($this->name,$request);
        return redirect()->back()->with(["success" => __("paagez.config_updated",["name"=>$this->title])]);
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