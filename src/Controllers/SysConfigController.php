<?php

namespace lz\admin\Controllers;


use lz\admin\Models\ConfigModel;
use lz\admin\Services\ConfigService;
use lz\admin\Services\ModelService;
use Illuminate\Http\Request;

class SysConfigController extends BaseModelController
{

    /**
     * 路由
     * @var string
     */
    public $route = '/sys/config/';

    /**
     * 模型
     * @return \lz\admin\Models\BaseModel|array|\Illuminate\Database\Eloquent\Builder
     */
    public function getModel()
    {
        return [
            'title' => '系统配置',
            'choose_type' => '',
            'table_config' => [
                ['table' => 'sys_config', 'primary_key' => 'id', 'join' => '', 'field_1' => '', 'field_2' => '', 'synch_io' => '']
            ],
            'cols_config' => [
                ['table' => 'sys_config', 'field' => 'id', 'alias' => '', 'title' => 'ID', 'width' => '80', 'fixed' => '', 'align' => 'center', 'sort' => '1', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_config', 'field' => 'index_key', 'alias' => '', 'title' => '标识', 'width' => '200', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_config', 'field' => 'title', 'alias' => '', 'title' => '标题', 'width' => '200', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_config', 'field' => 'model_id', 'alias' => '', 'title' => '模型ID', 'width' => '200', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_config', 'field' => 'data', 'alias' => '', 'title' => '配置', 'width' => '300', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_config', 'field' => 'created_at', 'alias' => '', 'title' => '创建时间', 'width' => '170', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_config', 'field' => 'updated_at', 'alias' => '', 'title' => '修改时间', 'width' => '170', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => '']
            ],
            'toolbar_config' => [
                ['title' => '新增', 'event' => 'create', 'color' => '', 'function_id' => '']
            ],
            'tool_config' => [
                ['bind' => '', 'title' => '编辑', 'event' => 'update', 'color' => '', 'function_id' => ''],
                ['bind' => '', 'title' => '删除', 'event' => 'delete', 'color' => 'layui-bg-red', 'function_id' => '']
            ],
            'search_config' => [
                ['table' => 'sys_config', 'field' => 'index_key', 'title' => '标识', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1'],
                ['table' => 'sys_config', 'field' => 'title', 'title' => '标题', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1']
            ],
            'form_config' => [
                ['table' => 'sys_config', 'field' => 'index_key', 'title' => '标识', 'category' => 'input', 'required' => '1', 'value' => '', 'option' => '', 'type' => 'text', 'ban_edit' => ''],
                ['table' => 'sys_config', 'field' => 'title', 'title' => '标题', 'category' => 'input', 'required' => '1', 'value' => '', 'option' => '', 'type' => 'text', 'ban_edit' => ''],
                ['table' => 'sys_config', 'field' => 'model_id', 'title' => '模型ID', 'category' => 'input', 'required' => '1', 'value' => '', 'option' => '', 'type' => 'number', 'ban_edit' => ''],
            ]
        ];
    }

}
