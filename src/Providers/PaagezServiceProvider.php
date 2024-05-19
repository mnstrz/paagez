<?php

namespace Monsterz\Paagez\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Pagination\Paginator;

class PaagezServiceProvider extends BaseServiceProvider
{
    protected $defer = false;


    protected $commands = [
        \Monsterz\Paagez\Console\CommandPaagez::class,
        \Monsterz\Paagez\Console\CommandInstall::class,
        \Monsterz\Paagez\Console\CommandThemeAssets::class,
        \Monsterz\Paagez\Console\CommandPublish::class,
    ];

    protected $commands_installed = [
        \Monsterz\Paagez\Console\Modules\CommandDbUpdate::class,
        \Monsterz\Paagez\Console\Modules\CommandPackageUpdate::class,
        \Monsterz\Paagez\Console\Modules\CommandModuleArtisanCall::class,
        \Monsterz\Paagez\Console\Modules\CommandVersionUpdate::class,
        \Monsterz\Paagez\Console\Modules\CommandModule::class,
        \Monsterz\Paagez\Console\Modules\CommandModuleInstall::class,
        \Monsterz\Paagez\Console\Modules\CommandModuleUpdate::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateModule::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateServiceProvider::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateAsset::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateMigration::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateSeeder::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateModel::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateController::class,
        \Monsterz\Paagez\Console\Modules\CommandNavigation::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateNavigationBreadcrumb::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateNavigationMenu::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateNavigationLauncher::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateNavigationNav::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateNavigationTab::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateRoute::class,
        \Monsterz\Paagez\Console\Modules\CommandCreateView::class,
        \Monsterz\Paagez\Console\Resources\CommandCreateResource::class,
    ];

    public function register()
    {
        if(\Schema::hasTable(config('paagez.db_prefix').'config'))
        {
            $commands = array_merge($this->commands,$this->commands_installed);
            $this->commands($commands);
        }else{
            $this->commands($this->commands);
        }
    }

    public function provides()
    {

    }

    public function boot()
    {
        $this->publishConfiguration();
        $this->registerConfigurations();
        $this->registerMiddleware();
        if(\Schema::hasTable(config('paagez.db_prefix').'config'))
        {
            $this->registerComponents();
            $this->registerModules();
        }
        if(!app()->runningInConsole()){
            if(config('paagez.base_theme') == 'bootstrap')
            {
                Paginator::useBootstrap();
            }
        }
    }

    protected function registerConfigurations()
    {
        $this->mergeConfigFrom($this->packagePath('paagez.php'), 'paagez');

        \Route::middleware('web')
            ->prefix(config('paagez.prefix'))
            ->as('paagez.')
            ->group($this->packagePath('routes/web.php'));

        \View::addNamespace('paagez',$this->packagePath('resources/views'),'paagez');
    }

    protected function packagePath($path = '')
    {
        return sprintf('%s/../%s', __DIR__, $path);
    }

    protected function publishConfiguration()
    {
        $this->publishes([$this->packagePath('paagez.php') => config_path('paagez.php')], 'config');
        $this->publishes([$this->packagePath('Database\Migrations') => database_path('migrations')], 'paagez-migration');
        $this->publishes([$this->packagePath('resources\lang') => base_path('lang')], 'paagez-lang');
    }

    protected function registerComponents()
    {
        \Blade::component(config('paagez.theme').'::components.alert-floating','alert-floating');
        \Blade::component(config('paagez.theme').'::components.alert','alert');
        \Blade::component(config('paagez.theme').'::components.loading-modal','loading-modal');
        \Blade::component(config('paagez.theme').'::components.modal','modal');
        \Blade::component(config('paagez.theme').'::components.box-left','box-left');
        \Blade::component(config('paagez.theme').'::components.box-right','box-right');
        \Blade::component('navbar',\Monsterz\Paagez\View\Navbar::class);
        \Blade::component('admin-navbar',\Monsterz\Paagez\View\AdminNavbar::class);
        \Blade::component('admin-sidebar',\Monsterz\Paagez\View\AdminSidebar::class);
        \Blade::component('admin-breadcrumb',\Monsterz\Paagez\View\AdminBreadcrumb::class);
        \Blade::component('admin-tab',\Monsterz\Paagez\View\AdminTab::class);
        \Blade::component('widget-left',\Monsterz\Paagez\View\WidgetLeft::class);
        \Blade::component('widget-right',\Monsterz\Paagez\View\WidgetRight::class);
        \Blade::component('admin-footer',\Monsterz\Paagez\View\AdminFooter::class);
    }

    protected function registerMiddleware()
    {
        $this->app['router']->aliasMiddleware('setup', \Monsterz\Paagez\Middleware\SetupConfigMiddleware::class);
        $this->app['router']->aliasMiddleware('admin', \Monsterz\Paagez\Middleware\AdminMiddleware::class);
        $this->app['router']->aliasMiddleware('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        $this->app['router']->aliasMiddleware('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);
    }

    protected function registerModules()
    {
        $app_logo = config('paagez.models.config')::where('name','app_name')->first();
        if($app_logo)
        {
            \Config::set('paagez.app_name',$app_logo->value);
        }
        $app_logo = config('paagez.models.config')::where('name','app_logo')->first();
        if($app_logo)
        {
            \Config::set('paagez.app_logo',\Storage::url($app_logo->value));
        }
        $updated_at = config('paagez.models.module')::orderBy('updated_at','desc')->first();
        if($updated_at)
        {
            \Config::set('paagez.cache',\Carbon\Carbon::parse($updated_at->updated_at)->format('Ymdhis'));
        }
        \Config::set('auth.providers.users.model',config('paagez.models.user'));
        $modules = config('paagez.models.module')::where('is_active',1)->get();
        foreach ($modules as $key => $module) {
            $classname = $module->namespace."\\ModuleServiceProvider";
            if(class_exists($classname))
            {
                $this->app->register($classname);
            }
        }
    
    }
}
