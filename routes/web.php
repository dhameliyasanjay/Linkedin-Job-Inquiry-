<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LocationApiController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [JobController::class, 'index'])->name('home');

// Resource Routes for Positions and Jobs
Route::resource('positions', PositionController::class);
Route::resource('jobs', JobController::class);

// Reports / Analytics
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

// Location API — Indian states and cities
Route::get('api/indian-states', [LocationApiController::class, 'indianStates'])->name('api.indian-states');
Route::get('api/indian-cities', [LocationApiController::class, 'indianCities'])->name('api.indian-cities');
Route::get('api/indian-cities/{state}', [LocationApiController::class, 'indianCitiesByState'])->name('api.indian-cities-by-state');

// Safe web-based migration runner
Route::get('run-migrations', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return 'Migrations run successfully. Output:<br><pre>' . Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return 'Error during migrations: ' . $e->getMessage();
    }
});
