<?php

namespace lz\admin\Controllers;


use lz\admin\Models\ConfigModel;
use lz\admin\Services\ConfigService;
use lz\admin\Services\ModelService;
use Illuminate\Http\Request;

class SysConfigDataController extends BaseModelController
{

    /**
     * 路由
     * @var string
     */
    public $route = '/sys/configData/';

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
                ['bind' => '', 'title' => '详情', 'event' => 'info', 'color' => 'layui-btn-primary', 'function_id' => '12'],
                ['bind' => '', 'title' => '编辑', 'event' => 'update', 'color' => '', 'function_id' => '13']
            ],
            'search_config' => [
                ['table' => 'sys_config', 'field' => 'index_key', 'title' => '标识', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1'],
                ['table' => 'sys_config', 'field' => 'title', 'title' => '标题', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1']
            ],
        ];
    }

    /**
     * 配置详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editView(Request $request)
    {
        $primary_key = $request->input('primary_key');
        $model = ConfigModel::query()->where('id', $primary_key)->first();
        $editForm = ModelService::getModelForm($model->model_id, $model->data);
        return $this->view('lzadmin/config/index', compact('editForm', 'model'));
    }

    /**
     * 修改配置
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $primary_key = $request->input('primary_key');
        $data = [];
        $config = ConfigModel::query()->where('id', $primary_key)->first();
        $model = ModelService::getModelById($config->model_id);
        foreach ($model['form_config'] as $item) {
            $field = $item['field'];
            $value = $request->input($field, '');
            if (!empty($item['required'])) {
                if (!isset($value)) {
                    return $this->error('【' . $item['title'] . '】参数必填');
                }
            }
            $data[$field] = $value;
        }
        $result = ConfigModel::query()->where('id', $primary_key)->update(['data' => $data]);
        if (!$result) {
            return $this->error();
        }
        ConfigService::refreshCache($config->index_key);
        return self::success([
            'data' => json_encode($data)
        ]);
    }

}
