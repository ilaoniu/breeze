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
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^0.6.9', 'tightenco/ziggy:^1.6'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@ant-design/colors' => '^7.0.0',
                '@ctrl/tinycolor' => '^3.6.0',
                '@floating-ui/dom' => '^1.4.5',
                '@tiptap/core' => '^2.0.4',
                '@tiptap/extension-blockquote' => '^2.0.4',
                '@tiptap/extension-bold' => '^2.0.4',
                '@tiptap/extension-bubble-menu' => '^2.0.4',
                '@tiptap/extension-bullet-list' => '^2.0.4',
                '@tiptap/extension-character-count' => '^2.0.4',
                '@tiptap/extension-code' => '^2.0.4',
                '@tiptap/extension-code-block' => '^2.0.4',
                '@tiptap/extension-document' => '^2.0.4',
                '@tiptap/extension-dropcursor' => '^2.0.4',
                '@tiptap/extension-gapcursor' => '^2.0.4',
                '@tiptap/extension-heading' => '^2.0.4',
                '@tiptap/extension-history' => '^2.0.4',
                '@tiptap/extension-horizontal-rule' => '^2.0.4',
                '@tiptap/extension-image' => '^2.0.4',
                '@tiptap/extension-italic' => '^2.0.4',
                '@tiptap/extension-link' => '^2.0.4',
                '@tiptap/extension-list-item' => '^2.0.4',
                '@tiptap/extension-ordered-list' => '^2.0.4',
                '@tiptap/extension-paragraph' => '^2.0.4',
                '@tiptap/extension-placeholder' => '^2.0.4',
                '@tiptap/extension-strike' => '^2.0.4',
                '@tiptap/extension-table' => '^2.0.4',
                '@tiptap/extension-table-cell' => '^2.0.4',
                '@tiptap/extension-table-header' => '^2.0.4',
                '@tiptap/extension-table-row' => '^2.0.4',
                '@tiptap/extension-task-item' => '^2.0.4',
                '@tiptap/extension-task-list' => '^2.0.4',
                '@tiptap/extension-text' => '^2.0.4',
                '@tiptap/extension-text-align' => '^2.0.4',
                '@tiptap/extension-underline' => '^2.0.4',
                '@tiptap/pm' => '^2.0.4',
                '@tiptap/vue-3' => '^2.0.4',
                '@vitejs/plugin-vue' => '^4.2.3',
                '@vue/server-renderer' => '^3.3.4',
                'async-validator' => '^4.2.5',
                'autoprefixer' => '^10.4.14',
                'date-fns' => '^2.30.0',
                'deepmerge' => '^4.3.1',
                'dnd-core' => '^16.0.1',
                'laravel-vite-plugin' => '^0.7.8',
                'lodash-es' => '^4.17.21',
                'postcss' => '^8.4.27',
                'qs' => '^6.11.2',
                'react-dnd-html5-backend' => '^16.0.1',
                'react-dnd-touch-backend' => '^16.0.1',
                'sass' => '^1.64.1',
                'tailwindcss' => '^3.3.3',
                'treemate' => '^0.3.11',
                'video.js' => '^8.3.0',
                'vite-plugin-watch' => '^0.2.0',
                'vue' => '^3.3.4',
                'ziggy-js' => '^1.6.0',
            ] + $packages;
        });

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');
        $this->installMiddlewareAfter('\App\Http\Middleware\HandleInertiaRequests::class', '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class');

        copy(__DIR__.'/../../stubs/inertia/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        // Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        $files = new Filesystem;
        $files->delete(base_path('resources/css/app.css'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/inertia/resources/css/app.scss', resource_path('css/app.scss'));
        copy(__DIR__.'/../../stubs/inertia/postcss.config.cjs', base_path('postcss.config.cjs'));
        copy(__DIR__.'/../../stubs/inertia/tailwind.config.cjs', base_path('tailwind.config.cjs'));
        copy(__DIR__.'/../../stubs/inertia/vite.config.js', base_path('vite.config.js'));

        copy(__DIR__.'/../../stubs/inertia/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__.'/../../stubs/inertia/resources/js/app.js', resource_path('js/app.js'));
        copy(__DIR__.'/../../stubs/inertia/resources/js/inertia.js', resource_path('js/inertia.js'));

        // ssr
        copy(__DIR__.'/../../stubs/inertia/resources/js/ssr.js', resource_path('js/ssr.js'));
        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
        $this->replaceInFile('/public/storage', '/resources/js/ziggy'.PHP_EOL.'/public/storage', base_path('.gitignore'));

        $this->components->info('Installing Node dependencies.');

        $this->runCommands(['pnpm install']);

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }
}
