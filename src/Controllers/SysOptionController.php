<?php


namespace lz\admin\Controllers;



use lz\admin\Models\OptionModel;
use lz\admin\Services\OptionService;
use Illuminate\Http\Request;

class SysOptionController extends Controller
{

    const OPTION_COLOR = [
        ['value' => 'layui-bg-orange', 'title' => '橙'],
        ['value' => 'layui-bg-green', 'title' => '绿'],
        ['value' => 'layui-bg-cyan', 'title' => '青'],
        ['value' => 'layui-bg-blue', 'title' => '蓝'],
        ['value' => 'layui-bg-gray', 'title' => '灰'],
    ];

    /**
     * 获取配置
     * @return array
     */
    public function getOptionConfig()
    {
        return [
            ['title' => '值', 'field' => 'value', 'width' => 100, "option" => []],
            ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
            ['title' => '颜色', 'field' => 'color', 'width' => 100, "option" => self::OPTION_COLOR],
        ];
    }

    public function indexView()
    {
        return $this->view('lzadmin/option/index');
    }

    /**
     * 搜索
     * @param Request $request
     * @return array
     */
    public function getList(Request $request)
    {
        $order_by_field = $request->input('order_by_field');
        $order_by_type = $request->input('order_by_type');
        $query = OptionModel::query();
        $query->select([
            'id',
            'title'
        ]);
        if (!empty($order_by_type)) {
            $query->orderBy($order_by_field, $order_by_type);
        } else {
            $query->orderBy('id', 'DESC');
        }
        $title = $request->input('title');
        if (isset($title)) {
            $query->where('title', $title);
        }
        return $this->layerPaginate($query);
    }

    /**
     * 新增
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        $title = $request->input('title');
        $model = new OptionModel();
        $model->title = $title;
        $model->option_config = [];
        $model->action = '';
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        OptionService::refreshCache($model->id);
        return $this->success();
    }

    /**
     * 修改
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');
        $model = OptionModel::find($id);
        if (empty($model)) {
            return $this->error('模型不存在');
        }
        $model->$field = $value;
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        OptionService::refreshCache($id);
        return $this->success();
    }

    /**
     * 删除
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');
        $model = OptionModel::find($id);
        if (empty($model)) {
            return $this->error('选项不存在');
        }
        $result = $model->delete();
        if (!$result) {
            return $this->error();
        }
        OptionService::deleteCache($id);
        return $this->success();
    }

    /**
     * 模型配置页
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function config(Request $request)
    {
        $id = $request->input('id');
        $option = OptionModel::find($id);
        if (empty($option)) {
            return $this->error('数据不存在');
        }
        $config = $this->getOptionConfig();
        return $this->view('lzadmin/option/config', compact('config', 'option'));
    }

    /**
     * 修改模型配置
     * @param Request $request
     * @return array
     */
    public function updateConfig(Request $request)
    {
        $id = (int)$request->input('id');
        $action = $request->input('action');
        $option_config = (array)$request->input('option_config');
        $model = OptionModel::find($id);
        if (empty($model)) {
            return $this->error('模型不存在');
        }
        $model->action = $action;
        $data = [];
        foreach ($option_config as $key => $vals) {
            foreach ($vals as $index => $val) {
                $data[$index][$key] = $val ?? '';
            }
        }
        $model->option_config = $data;
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        OptionService::refreshCache($id);
        return $this->success();
    }
}
