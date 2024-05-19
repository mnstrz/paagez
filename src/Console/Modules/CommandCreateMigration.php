<?php

namespace Monsterz\Paagez\Console\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CommandCreateMigration extends Command
{
    use TraitModule;

	protected $signature = 'paagez:migration {init?} {--module=}';
	
	protected $description = 'Create module migration';

	protected $directory = '';

	public function handle()
    {
        if(!$this->option('module') || !$this->argument('init'))
        {
            $this->line("-----------------------------------\n");
            $this->line("Paagez create migration\n");
            $this->line("-----------------------------------\n");
            $this->info($this->signature."\n\n");
            $this->line("<fg=yellow>init</>             Migration initials\n");
            $this->line("<fg=yellow>--module</>         Module name\n");
            $this->line("-----------------------------------\n");
            if(!$this->argument('init')){
                $init = $this->ask('Please enter the migration name');
                $this->input->setArgument('init', $init);
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
        if($this->strings($this->argument('init')))
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
        $this->line("\nCreating migration...\n");
        try{
            if (!\File::exists($this->module_path)) {
                \File::makeDirectory($this->module_path, 0755, true);
            }
            if (!\File::exists($this->module_path."/database")) {
                \File::makeDirectory($this->module_path."/database", 0755, true);
            }
            if (!\File::exists($this->module_path."/database/migrations")) {
                \File::makeDirectory($this->module_path."/database/migrations", 0755, true);
            }
            $init = $this->argument('init');

            $type = '';

            $tablename = '';

            if(strpos($init,'create_') > -1)
            {
                $type = 'create';
                $tablename = str_replace('create_','',$init);
            }elseif(strpos($init,'update_') > -1)
            {
                $type = 'update';
                $tablename = str_replace('update_','',$init);
            }

            $datetime = \Carbon\Carbon::now()->format('Y_m_d_his');

            $filename = "{$datetime}_{$this->module_name}_{$init}.php";

            $filePath = $this->module_path . '/database/migrations/'.$filename;
            
            if($type == 'update')
            {
                $fileContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable("'.$tablename.'")){
            Schema::table("'.$tablename.'", function (Blueprint $table) {
                //$table->bool("is_deleted")->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("'.$tablename.'", function (Blueprint $table) {
            //$table->dropColumn(array("is_deleted"));
        });
    }
};';
            }elseif($type == 'create'){
                $fileContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("'.$tablename.'", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("'.$tablename.'");
    }
};';
            }else{
                $fileContent = '<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};';
            }
            \File::put($filePath, $fileContent);
            $this->info("$filePath ........................................... Success\n");
            return 0;
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}