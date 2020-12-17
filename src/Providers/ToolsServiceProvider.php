<?php namespace QFrame\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use QFrame\Console\ApiDocGenerator;

class ToolsServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath($raw = __DIR__.'/../config/apidoc.php') ?: $raw;
        $template = realpath($raw = __DIR__.'/../templates/index.adoc') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('apidoc.php')]);

            if (!is_dir(resource_path('docs'))) {
                mkdir(resource_path('docs'));
                $this->publishes([$template=>resource_path('docs/index.adocdocker')]);
            }

            if (!is_dir(app_path('Support'))) {
                mkdir(app_path('Support'));
            }
        }

        $this->mergeConfigFrom($source, 'apidoc');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.apidoc', function () {
            return new ApiDocGenerator();
        });

        $this->commands(['command.apidoc']);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['command.apidoc'];
    }
}