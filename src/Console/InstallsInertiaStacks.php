<?php

namespace ILaoniu\Breeze\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsInertiaStacks
{
    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaVueStack()
    {
        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^2.0', 'tightenco/ziggy:^2.4'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@ant-design/colors' => '^7.2.1',
                '@ctrl/tinycolor' => '^3.6.1',
                '@floating-ui/dom' => '^1.7.6',
                '@inertiajs/vue3' => '^2.3.17',
                '@tailwindcss/vite' => '4.2.1',
                '@tiptap/core' => '^3.20.1',
                '@tiptap/extension-blockquote' => '^3.20.1',
                '@tiptap/extension-bold' => '^3.20.1',
                '@tiptap/extension-code' => '^3.20.1',
                '@tiptap/extension-code-block' => '^3.20.1',
                '@tiptap/extension-code-block-lowlight' => '^3.20.1',
                '@tiptap/extension-document' => '^3.20.1',
                '@tiptap/extension-hard-break' => '^3.20.1',
                '@tiptap/extension-heading' => '^3.20.1',
                '@tiptap/extension-highlight' => '^3.20.1',
                '@tiptap/extension-horizontal-rule' => '^3.20.1',
                '@tiptap/extension-image' => '^3.20.1',
                '@tiptap/extension-italic' => '^3.20.1',
                '@tiptap/extension-link' => '^3.20.1',
                '@tiptap/extension-list' => '^3.20.1',
                '@tiptap/extension-paragraph' => '^3.20.1',
                '@tiptap/extension-strike' => '^3.20.1',
                '@tiptap/extension-table' => '^3.20.1',
                '@tiptap/extension-text' => '^3.20.1',
                '@tiptap/extension-text-align' => '^3.20.1',
                '@tiptap/extension-text-style' => '^3.20.1',
                '@tiptap/extension-underline' => '^3.20.1',
                '@tiptap/extensions' => '^3.20.1',
                '@tiptap/pm' => '^3.20.1',
                '@tiptap/vue-3' => '^3.20.1',
                '@vitejs/plugin-vue' => '^6.0.4',
                '@vue/server-renderer' => '^3.5.29',
                'async-validator' => '^4.2.5',
                'axios' => '^1.13.6',
                'date-fns' => '^2.30.0',
                'date-fns-tz' => '^2.0.1',
                'dnd-core' => '^16.0.1',
                'highlight.js' => '^11.11.1',
                'laravel-vite-plugin' => '^2.1.0',
                'lodash-es' => '^4.17.23',
                'lowlight' => '^3.3.0',
                'qs' => '^6.15.0',
                'react-dnd-html5-backend' => '^16.0.1',
                'react-dnd-touch-backend' => '^16.0.1',
                'tailwindcss' => '^4.2.1',
                'treemate' => '^0.3.11',
                'vite' => "^7.3.1",
                'vite-plugin-watch' => '^0.2.0',
                'vue' => '^3.5.29',
            ] + $packages;
        });

        // Middleware...
        $this->installMiddleware([
            '\App\Http\Middleware\HandleInertiaRequests::class',
            '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class',
        ]);

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__.'/../../stubs/inertia/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));
        // Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        $files = new Filesystem;
        $files->delete(base_path('resources/css/app.css'));

        // Tailwind / Vite...
        @unlink(base_path('postcss.config.js'));
        @unlink(base_path('tailwind.config.js'));

        copy(__DIR__.'/../../stubs/inertia/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/inertia/vite.config.js', base_path('vite.config.js'));

        copy(__DIR__.'/../../stubs/inertia/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__.'/../../stubs/inertia/resources/js/app.js', resource_path('js/app.js'));
        copy(__DIR__.'/../../stubs/inertia/resources/js/inertia.js', resource_path('js/inertia.js'));

        // ssr
        copy(__DIR__.'/../../stubs/inertia/resources/js/ssr.js', resource_path('js/ssr.js'));
        $this->replaceInFile('"dev": "vite"', '"dev": "php artisan ziggy:generate ./resources/js/ziggy/ziggy.js && vite"', base_path('package.json'));
        $this->replaceInFile('vite build', 'php artisan ziggy:generate ./resources/js/ziggy/ziggy.js && vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
        $this->replaceInFile('/public/storage', '/resources/js/ziggy'.PHP_EOL.'/public/storage', base_path('.gitignore'));

        $this->components->info('Installing and building Node dependencies.');

        $this->runCommands(['pnpm install']);

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }
}
