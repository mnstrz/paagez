<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateView extends Command
{
    use TraitModule;

	protected $signature = 'paagez:view {name?} {--module=} {--layout=}';
	
	protected $description = 'Create view';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module') || !$this->option('layout') || !$this->argument('name'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create view\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>name</>             View file name\n");
            $this->line("<fg=yellow>--module</>         Module name\n");
            $this->line("<fg=yellow>--layout</>         Layout name\n");
            $this->line("-----------------------------------\n");
            if(!$this->argument('name'))
            {
                $name = $this->ask('Please enter the view name');
                $this->input->setArgument('name', $name);
            }
            if(!$this->option('module'))
            {
                $module = $this->ask('Please enter the module name');
                $this->input->setOption('module', $module);
            }
            if(!$this->option('layout'))
            {
                $this->inputLayout();
            }
        }
        if($this->checkString($this->option('layout')))
        {
            return 0;
        }
        if($this->initials($this->option('module'))){
            return 0;
        };
        if($this->strings($this->argument('name'),"/^[A-Za-z0-9_\/\\\\\.\-]+$/")){
            return 0;
        };
        if($this->makeFile())
        {
            return 0;
        }
        return 0;
    }

    public function inputLayout($value=null)
    {
        $layouts = config('paagez.layouts');
        $layout = $this->ask('Please enter the layout <fg=yellow>app,admin,admin-navbar-only</>');
        $this->input->setOption('layout', $layout);

        if(!$layout || !$this->hasOption('layout'))
        {
            $this->comment("At least please choose one of layout type\n");
            return $this->inputLayout($layout);
        }else{
            if(!in_array($layout,$layouts))
            {
                $this->comment("At least please choose one of layout type\n");
                return $this->inputLayout($layout);
            }else{
                $this->input->setOption('layout',$layout);
                return $layout;
            }
        }
    }

    public function makeFile()
    {
        $this->line("\nCreating view...\n");
        try {

            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/resources/views".$this->module_extra_path)) {
                \File::makeDirectory($this->module_path."/resources/views".$this->module_extra_path, 0755, true);
            }
            $filePath = $this->module_path . '/resources/views'.$this->module_extra_path.'/'.$this->snake_case.'.blade.php';
            if(file_exists($filePath))
            {
                $this->comment("$filePath ........................................... Already exists\n");
                return 1;
            }

        $fileContent = '@extends("'.config('paagez.theme').'::layouts.'.$this->option('layout').'")

@push("meta")

    <title>{{__("'.$this->title_case.'")}}</title>

    {{-- meta head goes here --}}

@endpush

@section("contents")
    
    {{-- your content should be written here --}}

@endsection

@push("styles")

    {{-- css goes here --}}

@endpush

@push("scripts")

    {{-- javascript goes here --}}

@endpush';
            \File::put($filePath, $fileContent);
            $this->info("$filePath ........................................... Success");
            $this->info("Use <fg=yellow>view('{$this->module_name}::{$this->route_path}')</> to load this view\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}