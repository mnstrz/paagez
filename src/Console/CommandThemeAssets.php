<?php

namespace Monsterz\Paagez\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandThemeAssets extends Command
{
    use Modules\TraitModule;

	protected $signature = 'paagez:theme-assets {--module=}';
	
	protected $description = 'Publish theme assets';

	protected $directory = '';

	public function handle()
    {
        if($this->option('module'))
        {
            $this->initials($this->option('module'));
            $this->createSymlinkAssetModule();
        }else{
            $this->createSymlinkAsset();
        }
        return 0;
    }

    public function createSymlinkAsset()
    {
        try {
            $link = public_path('theme');
            $target = $this->packagePath('../assets/theme');
            $this->laravel->make('files')->link($target, $link);

            $link = public_path('paagez');
            $target = $this->packagePath('../assets/paagez');
            $this->laravel->make('files')->link($target, $link);
            $this->info("Successfuly create link themes assets");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }

    public function createSymlinkAssetModule()
    {
        $this->line("Publishing assets for <fg=yellow>module\\$this->module_name</>...\n");
        try {
            if (!\File::exists($this->module_path."/assets")) {
                $this->error("'$this->module_path/assets' directory not found");
                return 1;
            }
            $target = $this->module_path."/assets";
            if(!file_exists(public_path('modules')))
            {
                \File::makeDirectory(public_path('modules'),0775,true);
            }
            $link = public_path('modules/'.$this->module_name);
            $this->laravel->make('files')->link($target, $link);
            $this->line("\nSuccessfuly create link from <fg=green>'$target'</> to <fg=green>'$link'</>\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }

    protected function packagePath($path = '')
    {
        return sprintf('%s/../%s', __DIR__, $path);
    }
}