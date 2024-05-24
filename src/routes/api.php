<?php

Route::post('/login', [\Monsterz\Paagez\Controllers\Auth\ApiController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function()
{
	Route::get('/profile', [\Monsterz\Paagez\Controllers\Auth\ApiController::class, 'profile'])->name('profile');
});