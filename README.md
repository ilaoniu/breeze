## 1. 简介

用于快速集成 I-UI, 安装列表如下：

-   [I-UI](https://ui.ilaoniu.cn)
-   [Inertia](https://inertiajs.com/)
-   [Vue 3](https://vuejs.org/)
-   [Tailwind CSS 3](https://tailwindcss.com/)
-   [SASS](https://sass-lang.com/)
-   [Ziggy](https://github.com/tighten/ziggy)

## 2. 安装

```
composer require laravel/breeze --dev
```

## 3. 使用

运行安装命令集成 I-UI:

```
php artisan breeze:install
```

复制 I-UI 到项目:

```
cp -r ~/code/ui/resources/js/i-ui ./resources/js/i-ui
```

## 4. 注意事项

按需修改 Inertia 中间件，位置 `app/Http/Middleware/HandleInertiaRequests.php`:

```
.
.
.
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'app' => [
                'name' => 'Name',
                'preferNativeScrollbar' => (bool) ! preg_match('/Windows ((NT|XP)( \d\d?.\d)?)/i', $request->userAgent()),
                'currentRouteName' => \Route::currentRouteName(),
                'currentUrl' => \URL::full(),
            ],
            'auth' => [
                'user' => \Auth::user(),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ]
        ]);
    }
.
.
.
```

## 5. 打包 tree-shaking

可根据需要在 `package.json` 中添加 `sideEffects` 自动移除未使用代码。（一般不需要）

示例：

```
.
.
.
    "sideEffects": [
        './resources/js/bootstrap.js'
    ],
.
.
.
```
