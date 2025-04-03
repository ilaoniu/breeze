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
                '@ant-design/colors' => '^7.2.0',
                '@ctrl/tinycolor' => '^3.6.1',
                '@floating-ui/dom' => '^1.6.13',
                '@tailwindcss/vite' => '4.0.9',
                '@tiptap/core' => '^2.11.5',
                '@tiptap/extension-blockquote' => '^2.11.5',
                '@tiptap/extension-bold' => '^2.11.5',
                '@tiptap/extension-bubble-menu' => '^2.11.5',
                '@tiptap/extension-bullet-list' => '^2.11.5',
                '@tiptap/extension-character-count' => '^2.11.5',
                '@tiptap/extension-code' => '^2.11.5',
                '@tiptap/extension-code-block' => '^2.11.5',
                '@tiptap/extension-code-block-lowlight' => '^2.11.5',
                '@tiptap/extension-color' => '^2.11.5',
                '@tiptap/extension-document' => '^2.11.5',
                '@tiptap/extension-dropcursor' => '^2.11.5',
                '@tiptap/extension-gapcursor' => '^2.11.5',
                '@tiptap/extension-hard-break' => '^2.11.5',
                '@tiptap/extension-heading' => '^2.11.5',
                '@tiptap/extension-highlight' => '^2.11.5',
                '@tiptap/extension-history' => '^2.11.5',
                '@tiptap/extension-horizontal-rule' => '^2.11.5',
                '@tiptap/extension-image' => '^2.11.5',
                '@tiptap/extension-italic' => '^2.11.5',
                '@tiptap/extension-link' => '^2.11.5',
                '@tiptap/extension-list-item' => '^2.11.5',
                '@tiptap/extension-ordered-list' => '^2.11.5',
                '@tiptap/extension-paragraph' => '^2.11.5',
                '@tiptap/extension-placeholder' => '^2.11.5',
                '@tiptap/extension-strike' => '^2.11.5',
                '@tiptap/extension-table' => '^2.11.5',
                '@tiptap/extension-table-cell' => '^2.11.5',
                '@tiptap/extension-table-header' => '^2.11.5',
                '@tiptap/extension-table-row' => '^2.11.5',
                '@tiptap/extension-text' => '^2.11.5',
                '@tiptap/extension-text-align' => '^2.11.5',
                '@tiptap/extension-text-style' => '^2.11.5',
                '@tiptap/extension-underline' => '^2.11.5',
                '@tiptap/pm' => '^2.11.5',
                '@tiptap/vue-3' => '^2.11.5',
                '@vitejs/plugin-vue' => '^5.2.1',
                '@vue/server-renderer' => '^3.5.13',
                'async-validator' => '^4.2.5',
                'axios' => '^1.8.1',
                'date-fns' => '^2.30.0',
                'date-fns-tz' => '^2.0.1',
                'deepmerge' => '^4.3.1',
                'dnd-core' => '^16.0.1',
                'highlight.js' => '^11.11.1',
                'laravel-vite-plugin' => '^1.2.0',
                'lodash-es' => '^4.17.21',
                'lowlight' => '^3.3.0',
                'qs' => '^6.14.0',
                'react-dnd-html5-backend' => '^16.0.1',
                'react-dnd-touch-backend' => '^16.0.1',
                'tailwindcss' => '4.0.9',
                'treemate' => '^0.3.11',
                'vite' => "^6.2.0",
                'vite-plugin-watch' => '^0.2.0',
                'vue' => '^3.5.13',
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
