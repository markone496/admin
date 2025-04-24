<?php

namespace lz\admin\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class DbCommand extends Command
{

    protected $signature = 'lzadmin:db';
    protected $description = '执行后台系统数据导入';

    public function handle()
    {

        //todo 检查表是否存在
        $table_names = [
            'sys_config', 'sys_function', 'sys_log', 'sys_menu', 'sys_model', 'sys_option', 'sys_role', 'sys_user'
        ];
        foreach ($table_names as $table_name) {
            if (Schema::hasTable($table_name)) {
                $this->error("表{$table_name}存在，请先手动删除该表！");
                return;
            }
        }
        $path = base_path("vendor/lz/admin/src/admin.sql");
        if (!file_exists($path)) {
            $this->error('sql文件不存在');
            return;
        }
        $sql = file_get_contents($path);
        try {
            DB::unprepared($sql);
            $this->info('导入成功');
        } catch (\Exception $e) {
            $this->error('导入失败： ' . $e->getMessage());
        }
    }
}
