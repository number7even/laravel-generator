<?php

namespace Number7even\Generator;

use Illuminate\Support\ServiceProvider;
use Number7even\Generator\Commands\API\APIControllerGeneratorCommand;
use Number7even\Generator\Commands\API\APIGeneratorCommand;
use Number7even\Generator\Commands\API\APIRequestsGeneratorCommand;
use Number7even\Generator\Commands\API\TestsGeneratorCommand;
use Number7even\Generator\Commands\APIScaffoldGeneratorCommand;
use Number7even\Generator\Commands\Common\MigrationGeneratorCommand;
use Number7even\Generator\Commands\Common\ModelGeneratorCommand;
use Number7even\Generator\Commands\Common\RepositoryGeneratorCommand;
use Number7even\Generator\Commands\Publish\GeneratorPublishCommand;
use Number7even\Generator\Commands\Publish\LayoutPublishCommand;
use Number7even\Generator\Commands\Publish\PublishTemplateCommand;
use Number7even\Generator\Commands\Publish\VueJsLayoutPublishCommand;
use Number7even\Generator\Commands\RollbackGeneratorCommand;
use Number7even\Generator\Commands\Scaffold\ControllerGeneratorCommand;
use Number7even\Generator\Commands\Scaffold\RequestsGeneratorCommand;
use Number7even\Generator\Commands\Scaffold\ScaffoldGeneratorCommand;
use Number7even\Generator\Commands\Scaffold\ViewsGeneratorCommand;
use Number7even\Generator\Commands\VueJs\VueJsGeneratorCommand;

class Number7evenGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/laravel_generator.php';

        $this->publishes([
            $configPath => config_path('Number7even/laravel_generator.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Number7even.publish', function ($app) {
            return new GeneratorPublishCommand();
        });

        $this->app->singleton('Number7even.api', function ($app) {
            return new APIGeneratorCommand();
        });

        $this->app->singleton('Number7even.scaffold', function ($app) {
            return new ScaffoldGeneratorCommand();
        });

        $this->app->singleton('Number7even.publish.layout', function ($app) {
            return new LayoutPublishCommand();
        });

        $this->app->singleton('Number7even.publish.templates', function ($app) {
            return new PublishTemplateCommand();
        });

        $this->app->singleton('Number7even.api_scaffold', function ($app) {
            return new APIScaffoldGeneratorCommand();
        });

        $this->app->singleton('Number7even.migration', function ($app) {
            return new MigrationGeneratorCommand();
        });

        $this->app->singleton('Number7even.model', function ($app) {
            return new ModelGeneratorCommand();
        });

        $this->app->singleton('Number7even.repository', function ($app) {
            return new RepositoryGeneratorCommand();
        });

        $this->app->singleton('Number7even.api.controller', function ($app) {
            return new APIControllerGeneratorCommand();
        });

        $this->app->singleton('Number7even.api.requests', function ($app) {
            return new APIRequestsGeneratorCommand();
        });

        $this->app->singleton('Number7even.api.tests', function ($app) {
            return new TestsGeneratorCommand();
        });

        $this->app->singleton('Number7even.scaffold.controller', function ($app) {
            return new ControllerGeneratorCommand();
        });

        $this->app->singleton('Number7even.scaffold.requests', function ($app) {
            return new RequestsGeneratorCommand();
        });

        $this->app->singleton('Number7even.scaffold.views', function ($app) {
            return new ViewsGeneratorCommand();
        });

        $this->app->singleton('Number7even.rollback', function ($app) {
            return new RollbackGeneratorCommand();
        });

        $this->app->singleton('Number7even.vuejs', function ($app) {
            return new VueJsGeneratorCommand();
        });
        $this->app->singleton('Number7even.publish.vuejs', function ($app) {
            return new VueJsLayoutPublishCommand();
        });

        $this->commands([
            'Number7even.publish',
            'Number7even.api',
            'Number7even.scaffold',
            'Number7even.api_scaffold',
            'Number7even.publish.layout',
            'Number7even.publish.templates',
            'Number7even.migration',
            'Number7even.model',
            'Number7even.repository',
            'Number7even.api.controller',
            'Number7even.api.requests',
            'Number7even.api.tests',
            'Number7even.scaffold.controller',
            'Number7even.scaffold.requests',
            'Number7even.scaffold.views',
            'Number7even.rollback',
            'Number7even.vuejs',
            'Number7even.publish.vuejs',
        ]);
    }
}
