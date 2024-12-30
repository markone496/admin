<?php

namespace lz\admin\Console;

use Illuminate\Console\Command;
use lz\admin\Services\ConfigService;
use lz\admin\Services\FunctionService;
use lz\admin\Services\MenuService;
use lz\admin\Services\ModelService;
use lz\admin\Services\OptionService;
use lz\admin\Services\RoleService;
use lz\admin\Services\UserService;


class RefreshCommand extends Command
{

    protected $signature = 'lzadmin:refresh';
    protected $description = '刷新系统配置缓存';

    public function handle()
    {
        try {
            ConfigService::refreshCache();
            MenuService::refreshCache();
            FunctionService::refreshCache();
            RoleService::refreshCache();
            UserService::refreshCache();
            ModelService::refreshCache();
            OptionService::refreshCache();
            OptionService::refreshCache();
            ConfigService::tableRefreshCache();
            $this->info('刷新成功');
        } catch (\Exception $e) {
            $this->error('刷新失败： ' . $e->getMessage());
        }
    }
}
