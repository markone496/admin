<?php


namespace lz\admin\Controllers;



use lz\admin\Models\FunctionModel;
use lz\admin\Models\MenuModel;
use lz\admin\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SysMenuController extends Controller
{

    /**
     * 菜单管理页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexView()
    {
        return $this->view('lzadmin/menu/index');
    }

    /**
     * 获取菜单列表
     * @return array
     */
    public function getList()
    {
        $data = MenuService::getAll();
        if (empty($data)) {
            return $this->error('暂无数据');
        }
        $data = customArrFormatTree($data, 0);
        return $this->success($data);
    }

    /**
     * 新增菜单页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function addView(Request $request)
    {
        $id = $request->input('id', 0);
        return $this->view('lzadmin/menu/edit', compact('id'));
    }

    /**
     * 创建菜单
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        $parent_id = $request->input('parent_id');
        $title = $request->input('title');
        $icon = $request->input('icon');
        $route = $request->input('route');
        $sort = $request->input('sort');
        $is_hide = ($request->input('is_hide') === 'on' ? 1 : 0);
        $parent_ids = [];
        if ($parent_id) {
            $parent_ids = MenuModel::query()->where('id', $parent_id)->value('parent_ids');
            array_unshift($parent_ids, $parent_id);
        }
        if (count($parent_ids) >= 3) {
            return $this->error('最多创建三级菜单');
        }
        $model = new MenuModel;
        $model->parent_id = $parent_id;
        $model->parent_ids = $parent_ids;
        $model->title = $title;
        $model->icon = $icon;
        $model->route = $route;
        $model->sort = $sort;
        $model->is_hide = $is_hide;
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        //更新缓存
        MenuService::refreshCache($model->id);
        return $this->success($model);
    }

    /**
     * 修改菜单
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');
        $model = MenuModel::find($id);
        $model->$field = $value;
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        //更新缓存
        MenuService::refreshCache($id);
        return $this->success([
            'id' => $model->id,
            $field => $model->$field
        ]);
    }

    /**
     * 删除菜单
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        //检查该菜单下是否有子菜单
        $checkHasChild = MenuModel::query()->where('parent_id', $id)->exists();
        if ($checkHasChild) {
            return $this->error('请先删除子菜单');
        }
        DB::beginTransaction();
        try {
            //删除菜单
            MenuModel::query()->where('id', $id)->delete();
            //删除权限
            FunctionModel::query()->where('menu_id', $id)->delete();
            DB::commit();
            //清除缓存
            MenuService::deleteCache($id);
            return $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }
}
