<?php

namespace lz\admin;

use Illuminate\Support\ServiceProvider;
use lz\admin\Console\DbCommand;
use lz\admin\Console\RefreshCommand;

class LzAdminProvider extends ServiceProvider
{

    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/config/admin.php' => config_path('admin.php'),
            __DIR__ . '/config/captcha.php' => config_path('captcha.php'),
        ], 'config');

        // 发布资源文件
        $this->publishes([
            __DIR__ . '/assets' => public_path('assets'),
        ], 'assets');

        // 发布视图文件
        $this->loadViewsFrom(__DIR__ . '/Views', 'lzadmin');
        $this->publishes([
            __DIR__ . '/Views' => resource_path('views'),
        ]);

        // 加载路由
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

    }

    public function register()
    {

        // 绑定配置文件
        $this->mergeConfigFrom(
            __DIR__ . '/config/admin.php', 'admin'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/config/captcha.php', 'captcha'
        );

        // 注册命令
        $this->commands([
            DbCommand::class,
            RefreshCommand::class,
        ]);
    }
}
