<?php

Route::get('/setup', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'index'])->name('setup');
Route::post('/setup', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'install'])->name('setup.submit');
Route::group(['middleware' => ['setup']],function(){
    Route::group(['middleware' => ['guest']],function(){
		Route::get('/login', [\Monsterz\Paagez\Controllers\Auth\LoginController::class, 'index'])->name('login');
		Route::post('/login', [\Monsterz\Paagez\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
    });
    Route::group(['middleware' => ['admin','role:admin|author']],function(){

		Route::get('/', [\Monsterz\Paagez\Controllers\Dashboard\DashboardController::class, 'index'])->name('index');
		Route::get('/logout', [\Monsterz\Paagez\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

        Route::group(['middleware' => ['role:admin']],function(){
            Route::get('/update', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'update'])->name('update');
            
            Route::post('/update/package', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updatePackage'])->name('update.package');
            Route::post('/update/database', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updateDatabase'])->name('update.database');
            Route::post('/update/module', [\Monsterz\Paagez\Controllers\Setup\SetupController::class, 'updateModule'])->name('update.module');
        });
    });
});