<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return 'Run migrations with Database seeder (php artisan migrate:fresh --seed) to populate the required cities list to get started. Please make sure you have started you runner as well as seeder uses queue.';
});

Route::get('update', function() {
   \App\Jobs\UpdateCitiesForecastsJob::dispatch();
});
