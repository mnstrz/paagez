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

        Route::get('/profile', [\Monsterz\Paagez\Controllers\Auth\ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [\Monsterz\Paagez\Controllers\Auth\ProfileController::class, 'update'])->name('profile.update');

        Route::get('/notifications', [\Monsterz\Paagez\Controllers\Notification\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/read/all', [\Monsterz\Paagez\Controllers\Notification\NotificationController::class, 'readAll'])->name('notifications.read.all');

        Route::group(['middleware' => ['role:admin']],function(){
            Route::get('/update', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'update'])->name('update');
            Route::post('/update/package', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updatePackage'])->name('update.package');
            Route::post('/update/database', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updateDatabase'])->name('update.database');
            Route::post('/update/module', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updateModule'])->name('update.module');

            Route::get('/config/app', [\Monsterz\Paagez\Controllers\Config\AppController::class, 'index'])->name('config.app');
            Route::post('/config/app', [\Monsterz\Paagez\Controllers\Config\AppController::class, 'update'])->name('config.app.update');

            Route::get('/config/roles', [\Monsterz\Paagez\Controllers\Config\RolesController::class, 'index'])->name('config.roles.index');
            Route::get('/config/roles/create', [\Monsterz\Paagez\Controllers\Config\RolesController::class, 'create'])->name('config.roles.create');
            Route::post('/config/roles', [\Monsterz\Paagez\Controllers\Config\RolesController::class, 'store'])->name('config.roles.store');
            Route::get('/config/roles/{roles}/edit', [\Monsterz\Paagez\Controllers\Config\RolesController::class, 'edit'])->name('config.roles.edit');
            Route::put('/config/roles/{roles}/edit', [\Monsterz\Paagez\Controllers\Config\RolesController::class, 'update'])->name('config.roles.update');
            Route::delete('/config/roles/{roles}/destroy', [\Monsterz\Paagez\Controllers\Config\RolesController::class, 'destroy'])->name('config.roles.destroy');

            Route::get('/config/users', [\Monsterz\Paagez\Controllers\Config\UsersController::class, 'index'])->name('config.users.index');
            Route::get('/config/users/create', [\Monsterz\Paagez\Controllers\Config\UsersController::class, 'create'])->name('config.users.create');
            Route::post('/config/users', [\Monsterz\Paagez\Controllers\Config\UsersController::class, 'store'])->name('config.users.store');
            Route::get('/config/users/{users}/edit', [\Monsterz\Paagez\Controllers\Config\UsersController::class, 'edit'])->name('config.users.edit');
            Route::put('/config/users/{users}/edit', [\Monsterz\Paagez\Controllers\Config\UsersController::class, 'update'])->name('config.users.update');
            Route::delete('/config/users/{users}/destroy', [\Monsterz\Paagez\Controllers\Config\UsersController::class, 'destroy'])->name('config.users.destroy');

            Route::get('/config/modules', [\Monsterz\Paagez\Controllers\Config\ModulesController::class, 'index'])->name('config.modules.index');
            Route::get('/config/modules/{module}/show', [\Monsterz\Paagez\Controllers\Config\ModulesController::class, 'show'])->name('config.modules.show');
            Route::get('/config/modules/{module}/change-status', [\Monsterz\Paagez\Controllers\Config\ModulesController::class, 'changeStatus'])->name('config.modules.change-status');

            Route::get('/config/rest', [\Monsterz\Paagez\Controllers\Config\RestController::class, 'index'])->name('config.rest.index');
            Route::get('/config/rest/create', [\Monsterz\Paagez\Controllers\Config\RestController::class, 'create'])->name('config.rest.create');
            Route::post('/config/rest', [\Monsterz\Paagez\Controllers\Config\RestController::class, 'store'])->name('config.rest.store');
            Route::delete('/config/rest/{users}/revoke', [\Monsterz\Paagez\Controllers\Config\RestController::class, 'revoke'])->name('config.rest.revoke');
            
            Route::get('/config/email', [\Monsterz\Paagez\Controllers\Config\EmailController::class, 'index'])->name('config.email');
            Route::post('/config/email', [\Monsterz\Paagez\Controllers\Config\EmailController::class, 'update'])->name('config.email.update');
            Route::get('/config/email/reset', [\Monsterz\Paagez\Controllers\Config\EmailController::class, 'reset'])->name('config.email.reset');
            Route::post('/config/email/test', [\Monsterz\Paagez\Controllers\Config\EmailController::class, 'test'])->name('config.email.test');
        });
    });
});