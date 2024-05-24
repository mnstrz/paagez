<?php

Route::get('/setup', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'index'])->name('setup');
Route::post('/setup', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'install'])->name('setup.submit');
Route::group(['middleware' => ['setup']],function(){
    Route::group(['middleware' => ['guest']],function(){
		Route::get('/login', [\Monsterz\Paagez\Controllers\Auth\LoginController::class, 'index'])->name('login');
		Route::post('/login', [\Monsterz\Paagez\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
    });
    Route::group(['middleware' => ['admin']],function(){

		Route::get('/', [\Monsterz\Paagez\Controllers\Dashboard\DashboardController::class, 'index'])->name('index');
		Route::get('/logout', [\Monsterz\Paagez\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

        Route::get('/notifications', [\Monsterz\Paagez\Controllers\Notification\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/read/all', [\Monsterz\Paagez\Controllers\Notification\NotificationController::class, 'readAll'])->name('notifications.read.all');

        Route::group(['middleware' => ['role:admin']],function(){
            Route::get('/update', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'update'])->name('update');
            Route::post('/update/package', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updatePackage'])->name('update.package');
            Route::post('/update/database', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updateDatabase'])->name('update.database');
            Route::post('/update/module', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updateModule'])->name('update.module');

            Route::get('/app/config', [\Monsterz\Paagez\Controllers\App\SettingController::class, 'index'])->name('app.config');
            Route::post('/app/config', [\Monsterz\Paagez\Controllers\App\SettingController::class, 'update'])->name('app.config.update');

            Route::get('/app/roles', [\Monsterz\Paagez\Controllers\App\RolesController::class, 'index'])->name('app.roles.index');
            Route::get('/app/roles/create', [\Monsterz\Paagez\Controllers\App\RolesController::class, 'create'])->name('app.roles.create');
            Route::post('/app/roles', [\Monsterz\Paagez\Controllers\App\RolesController::class, 'store'])->name('app.roles.store');
            Route::get('/app/roles/{roles}/edit', [\Monsterz\Paagez\Controllers\App\RolesController::class, 'edit'])->name('app.roles.edit');
            Route::put('/app/roles/{roles}/edit', [\Monsterz\Paagez\Controllers\App\RolesController::class, 'update'])->name('app.roles.update');
            Route::delete('/app/roles/{roles}/destroy', [\Monsterz\Paagez\Controllers\App\RolesController::class, 'destroy'])->name('app.roles.destroy');

            Route::get('/app/users', [\Monsterz\Paagez\Controllers\App\UsersController::class, 'index'])->name('app.users.index');
            Route::get('/app/users/create', [\Monsterz\Paagez\Controllers\App\UsersController::class, 'create'])->name('app.users.create');
            Route::post('/app/users', [\Monsterz\Paagez\Controllers\App\UsersController::class, 'store'])->name('app.users.store');
            Route::get('/app/users/{users}/edit', [\Monsterz\Paagez\Controllers\App\UsersController::class, 'edit'])->name('app.users.edit');
            Route::put('/app/users/{users}/edit', [\Monsterz\Paagez\Controllers\App\UsersController::class, 'update'])->name('app.users.update');
            Route::delete('/app/users/{users}/destroy', [\Monsterz\Paagez\Controllers\App\UsersController::class, 'destroy'])->name('app.users.destroy');

            Route::get('/app/modules', [\Monsterz\Paagez\Controllers\App\ModulesController::class, 'index'])->name('app.modules.index');
            Route::get('/app/modules/{module}/show', [\Monsterz\Paagez\Controllers\App\ModulesController::class, 'show'])->name('app.modules.show');
            Route::get('/app/modules/{module}/change-status', [\Monsterz\Paagez\Controllers\App\ModulesController::class, 'changeStatus'])->name('app.modules.change-status');

            Route::get('/app/rest', [\Monsterz\Paagez\Controllers\App\RestController::class, 'index'])->name('app.rest.index');
            Route::get('/app/rest/create', [\Monsterz\Paagez\Controllers\App\RestController::class, 'create'])->name('app.rest.create');
            Route::post('/app/rest', [\Monsterz\Paagez\Controllers\App\RestController::class, 'store'])->name('app.rest.store');
            Route::delete('/app/rest/{users}/revoke', [\Monsterz\Paagez\Controllers\App\RestController::class, 'revoke'])->name('app.rest.revoke');
            
            Route::get('/app/email', [\Monsterz\Paagez\Controllers\App\EmailController::class, 'index'])->name('app.email');
            Route::post('/app/email', [\Monsterz\Paagez\Controllers\App\EmailController::class, 'update'])->name('app.email.update');
            Route::get('/app/email/reset', [\Monsterz\Paagez\Controllers\App\EmailController::class, 'reset'])->name('app.email.reset');
            Route::post('/app/email/test', [\Monsterz\Paagez\Controllers\App\EmailController::class, 'test'])->name('app.email.test');
        });
    });
});