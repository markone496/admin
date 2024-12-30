<?php

namespace lz\admin\Controllers;


use lz\admin\Models\FunctionModel;
use lz\admin\Models\MenuModel;
use lz\admin\Services\FunctionService;
use Illuminate\Http\Request;

class SysFunctionController extends Controller
{

    public function getList(Request $request)
    {
        $menu_id = $request->input('menu_id');
        $query = FunctionModel::query();
        $query->where('menu_id', $menu_id);
        return $this->layerPaginate($query);
    }

    public function editView(Request $request)
    {
        $id = $request->input('id', 0);
        $model = null;
        if (!empty($id)) {
            $model = FunctionModel::find($id);
            $model->route = implode("\n", $model->route);
        }
        return $this->view('lzadmin/function/edit', compact('model'));
    }

    public function create(Request $request)
    {
        $menu_id = $request->input('menu_id');
        $title = $request->input('title');
        $route = $request->input('route');
        $route = explode("\n", $route);
        $menuModel = MenuModel::find($menu_id);
        if (empty($menuModel)) {
            return $this->error('菜单已删除');
        }
        $menu_ids = (array)$menuModel->parent_ids;
        array_unshift($menu_ids, $menu_id);
        $model = new FunctionModel;
        $model->title = $title;
        $model->route = $route;
        $model->menu_id = $menu_id;
        $model->menu_ids = $menu_ids;
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        FunctionService::refreshCache($model->id);
        return $this->success($model);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        $title = $request->input('title');
        $route = $request->input('route');
        $route = explode("\n", $route);
        $model = FunctionModel::find($id);
        if (empty($model)) {
            return $this->error('权限已删除');
        }
        $model->title = $title;
        $model->route = $route;
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        FunctionService::refreshCache($model->id);
        return $this->success($model);
    }

    public function delete(Request $request)
    {
        $menu_id = $request->input('menu_id');
        $ids = $request->input('ids');
        $result = FunctionModel::query()->where('menu_id', $menu_id)->whereIn('id', $ids)->delete();
        if (!$result) {
            return $this->error();
        }
        FunctionService::deleteCache($menu_id, $ids);
        return $this->success();
    }

}
