<?php

namespace EUR\RSM\DatabaseViewExport;

use EUR\RSM\DatabaseViewExport\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DatabaseViewExportServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'database-view-export');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->app->booted(fn() => $this->routes());
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware('nova')
            ->prefix('nova-vendor/database-view-export')
            ->group(function (): void {
                Route::get('/export/{key}', [ExportController::class, 'export'])
                    ->name('database-view-export.export');
            });
    }
}
