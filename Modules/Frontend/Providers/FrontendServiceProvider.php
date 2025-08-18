<?php

namespace Modules\Frontend\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Frontend\View\Components\FrontendAuthLayout;
use Modules\Frontend\View\Components\FrontendLayout;
use Modules\Frontend\View\Components\FrontendUser;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FrontendServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Frontend';

    protected string $nameLower = 'frontend';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        
        Blade::component('frontend-layout', FrontendLayout::class);
        Blade::component('frontend-auth-layout', FrontendAuthLayout::class);

        // $viewPath = module_path($this->name, 'Resources/views');
        // if (!is_dir($viewPath)) {
        //     mkdir($viewPath, 0755, true);
        // }
        
        // $this->loadViewsFrom($viewPath, 'frontend');
        // $this->loadViewsFrom(__DIR__.'/../Resources/views', 'frontend');
        $this->loadViewsFrom(module_path('Frontend', 'resources/views'), 'frontend');

        
        Route::get('frontend-section/{any}', function ($any) {
            // Define the path to the assets directory inside the module
            $path = module_path('frontend', 'resources/assets/' . $any);
    
            // Check if the requested file exists
            if (file_exists($path)) {
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                $mimeTypes = [
                    'scss'  => 'text/css',
                    'css'   => 'text/css',
                    'js'    => 'application/javascript',
                    'ttf'   => 'font/ttf',
                ];

                $mimeType = $mimeTypes[$extension] ?? mime_content_type($path); // Get the MIME type of the file

                return response()->file($path, [
                    'Content-Type' => $mimeType // Set the correct content type header
                ]);
            }
    
        })->where('any', '.*'); // This will allow access to any file inside the assets folder
        
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $path = module_path('Frontend', 'config/constant.php');
        $this->mergeConfigFrom($path, 'frontend.constant');
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path($this->name, 'resources/lang'), $this->nameLower);
        /*        
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
        */
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $relativeConfigPath = config('modules.paths.generator.config.path');
        $configPath = module_path($this->name, $relativeConfigPath);

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $relativePath = str_replace($configPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $configKey = $this->nameLower . '.' . str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $relativePath);
                    $key = ($relativePath === 'config.php') ? $this->nameLower : $configKey;

                    $this->publishes([$file->getPathname() => config_path($relativePath)], 'config');
                    $this->mergeConfigFrom($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        $componentNamespace = $this->module_namespace($this->name, $this->app_path(config('modules.paths.generator.component-class.path')));
        Blade::componentNamespace($componentNamespace, $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }
}
