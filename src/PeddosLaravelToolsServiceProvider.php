<?php

namespace Jaffran\PeddosLaravelTools;

use Illuminate\Support\ServiceProvider;
use Jaffran\PeddosLaravelTools\Commands\GenerateActionCommand;
use Jaffran\PeddosLaravelTools\Commands\GenerateCrudCommand;
use Jaffran\PeddosLaravelTools\Commands\GenerateEnumCommand;
use Jaffran\PeddosLaravelTools\Commands\GenerateQueryCommand;
use Jaffran\PeddosLaravelTools\Commands\UpdatePermissionRoleCommand;

class PeddosLaravelToolsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // config
        $this->publishes([__DIR__ . '/../config/peddoslaraveltools.php' => config_path('peddoslaraveltools.php')], 'peddos-laravel-tools-config');

        // migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'peddos-laravel-tools-migration');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrudCommand::class,
                UpdatePermissionRoleCommand::class,
                GenerateQueryCommand::class,
                GenerateActionCommand::class,
                GenerateEnumCommand::class,
            ]);
        }
    }
}
