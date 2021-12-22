<?php

namespace Kukuhkkh\LaravelCommand;

use Illuminate\Support\ServiceProvider;
use Kukuhkkh\LaravelCommand\Commands\CreateRepositoryCommand;
use Kukuhkkh\LaravelCommand\Commands\CreateTraitCommand;
use Kukuhkkh\LaravelCommand\Commands\CreateServiceCommand;
use Kukuhkkh\LaravelCommand\Commands\CreateBladeCommand;
use Kukuhkkh\LaravelCommand\Commands\ClearLogCommand;

use Kukuhkkh\LaravelCommand\Commands\CreateModuleRepositoryCommand;
use Kukuhkkh\LaravelCommand\Commands\CreateModuleTraitCommand;
use Kukuhkkh\LaravelCommand\Commands\CreateModuleServiceCommand;
use Kukuhkkh\LaravelCommand\Commands\CreateModuleBladeCommand;



class LaravelCommandProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            CreateRepositoryCommand::class,
            CreateTraitCommand::class,
            CreateServiceCommand::class,
            CreateBladeCommand::class,
            ClearLogCommand::class,
            
            
            // For nWidart/laravel-modules:
            CreateModuleRepositoryCommand::class,
            CreateModuleTraitCommand::class,
            CreateModuleServiceCommand::class,
            CreateModuleBladeCommand::class
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
