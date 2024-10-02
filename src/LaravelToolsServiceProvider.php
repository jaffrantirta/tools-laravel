<?php

namespace Jaffran\LaravelTools;

use Illuminate\Support\ServiceProvider;
use Jaffran\LaravelTools\Commands\GenerateActionCommand;
use Jaffran\LaravelTools\Commands\GenerateCrudCommand;
use Jaffran\LaravelTools\Commands\GenerateEnumCommand;
use Jaffran\LaravelTools\Commands\GenerateQueryCommand;
use Jaffran\LaravelTools\Commands\GeneratePolicyCommand;
use Jaffran\LaravelTools\Commands\UpdatePermissionRoleCommand;

class LaravelToolsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // config
        $this->publishes([__DIR__ . '/../config/jaffranlaraveltools.php' => config_path('LaravelTools.php')], 'jaffran-laravel-tools-config');

        // migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'jaffran-laravel-tools-migration');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrudCommand::class,
                UpdatePermissionRoleCommand::class,
                GenerateQueryCommand::class,
                GeneratePolicyCommand::class,
                GenerateActionCommand::class,
                GenerateEnumCommand::class,
            ]);
        }
    }
}
