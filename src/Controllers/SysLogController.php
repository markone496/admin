<?php

namespace lz\admin\Controllers;



class SysLogController extends BaseModelController
{

    /**
     * 路由
     * @var string
     */
    public $route = '/sys/log/';

    /**
     * 模型
     * @return \lz\admin\Models\BaseModel|array|\Illuminate\Database\Eloquent\Builder
     */
    public function getModel()
    {
        return [
            'title' => '系统日志',
            'choose_type' => '',
            'table_config' => [
                ['table' => 'sys_log', 'primary_key' => 'created_at', 'join' => '', 'field_1' => '', 'field_2' => '', 'synch_io' => '']
            ],
            'cols_config' => [
                ['table' => 'sys_log', 'field' => 'ip', 'alias' => '', 'title' => '访问IP', 'width' => '120', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_log', 'field' => 'user_id', 'alias' => '', 'title' => '用户ID', 'width' => '100', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_log', 'field' => 'route', 'alias' => '', 'title' => '路由', 'width' => '200', 'fixed' => '', 'align' => '', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_log', 'field' => 'params', 'alias' => '', 'title' => '参数', 'width' => '400', 'fixed' => '', 'align' => '', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_log', 'field' => 'created_at', 'alias' => '', 'title' => '请求时间', 'width' => '170', 'fixed' => '', 'align' => 'center', 'sort' => '1', 'option' => '', 'show_type' => ''],
            ],
            'toolbar_config' => [
            ],
            'tool_config' => [
            ],
            'search_config' => [
                ['table' => 'sys_log', 'field' => 'user_id', 'title' => '用户ID', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1'],
                ['table' => 'sys_log', 'field' => 'route', 'title' => '路由', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1'],
                ['table' => 'sys_log', 'field' => 'created_at', 'title' => '请求时间', 'category' => 'layDate', 'option' => '', 'type' => 'datetime', 'range' => '1', 'show_const' => '1']
            ],
        ];
    }
}
