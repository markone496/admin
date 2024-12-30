<?php

namespace lz\admin\Controllers;


use lz\admin\Models\RoleModel;
use lz\admin\Services\MenuService;
use lz\admin\Services\RoleService;
use Illuminate\Http\Request;


class SysRoleController extends BaseModelController
{

    /**
     * 路由
     * @var string
     */
    public $route = '/sys/role/';

    /**
     * 模型
     * @return \lz\admin\Models\BaseModel|array|\Illuminate\Database\Eloquent\Builder
     */
    public function getModel()
    {
        return [
            'title' => '系统角色',
            'choose_type' => '',
            'table_config' => [
                ['table' => 'sys_role', 'primary_key' => 'id', 'join' => '', 'field_1' => '', 'field_2' => '', 'synch_io' => '']
            ],
            'cols_config' => [
                ['table' => 'sys_role', 'field' => 'id', 'alias' => '', 'title' => 'ID', 'width' => '80', 'fixed' => '', 'align' => 'center', 'sort' => '1', 'option' => '', 'show_type' => ''],
                ['table' => 'sys_role', 'field' => 'role_name', 'alias' => '', 'title' => '角色名称', 'width' => '120', 'fixed' => '', 'align' => 'center', 'sort' => '', 'option' => '', 'show_type' => '']
            ],
            'toolbar_config' => [
                ['title' => '新增', 'event' => 'create', 'color' => '', 'function_id' => 3]
            ],
            'tool_config' => [
                ['bind' => '', 'title' => '复制', 'event' => 'copy', 'color' => 'layui-bg-purple', 'function_id' => 6],
                ['bind' => '', 'title' => '编辑', 'event' => 'update', 'color' => '', 'function_id' => 4],
                ['bind' => '', 'title' => '删除', 'event' => 'delete', 'color' => 'layui-bg-red', 'function_id' => 5]
            ],
            'search_config' => [
                ['table' => 'sys_role', 'field' => 'role_name', 'title' => '角色名称', 'category' => 'input', 'option' => '', 'type' => '', 'range' => '', 'show_const' => '1']
            ],
        ];
    }

    public function indexView()
    {
        return view('/lzadmin/role/index', $this->compact());
    }

    /**
     * 表单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function editView(Request $request)
    {
        $id = $request->input('primary_key', 0);
        $model = null;
        $function_id = [];
        if (!empty($id)) {
            $model = RoleModel::find($id);
            $function_id = $model->function_ids;
        }
        $menus = MenuService::getTreeDataHasFunction($function_id);
        return $this->view('/lzadmin/role/edit', compact('model', 'menus'));
    }

    /**
     * 新增
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        $role_name = $request->input('role_name');
        $function_id = (array)$request->input('function_id');
        $model = new RoleModel();
        asort($function_id);
        $valueData = [];
        foreach ($function_id as $val) {
            $valueData[] = (int)$val;
        }
        $function_id = $valueData;
        $model->function_ids = $function_id;
        $model->role_name = $role_name;
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        RoleService::refreshCache($model->id);
        return $this->success();
    }

    /**
     * 修改
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $id = $request->input('primary_key');
        $model = RoleModel::find($id);
        if (empty($model)) {
            return $this->error('数据不存在');
        }
        $role_name = $request->input('role_name');
        $function_id = (array)$request->input('function_id');
        asort($function_id);
        $valueData = [];
        foreach ($function_id as $val) {
            $valueData[] = (int)$val;
        }
        $function_id = $valueData;
        $model->function_ids = $function_id;
        $model->role_name = $role_name;
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        RoleService::refreshCache($model->id);
        return $this->success($model);
    }

    /**
     * 删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $ids = $request->input('primary_key');
        $result = RoleModel::query()->where('id', $ids)->delete();
        if (!$result) {
            return $this->error();
        }
        RoleService::deleteCache($ids);
        return $this->success();
    }

    /**
     * 复制
     * @param Request $request
     * @return array
     */
    public function copy(Request $request)
    {
        $id = $request->input('id');
        $role = RoleModel::find($id);
        if (empty($role)) {
            return $this->error('数据不存在');
        }
        $model = new RoleModel();
        $model->role_name = $role->role_name . '【复制】';
        $model->function_ids = $role->function_ids;
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        RoleService::refreshCache($model->id);
        return $this->success();
    }

}
