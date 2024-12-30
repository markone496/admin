<?php


namespace lz\admin\Controllers;



use lz\admin\Models\ModelModel;
use lz\admin\Services\ModelService;
use lz\admin\Services\OptionService;
use Illuminate\Http\Request;

class SysModelController extends Controller
{

    const OPTION_JOIN = [
        ['value' => 'left', 'title' => '左连'],
        ['value' => 'right', 'title' => '右连'],
        ['value' => 'inner', 'title' => '内连'],
    ];

    const OPTION_SWITCH = [
        ['value' => 1, 'title' => '是'],
    ];

    const OPTION_EDIT = [
        ['value' => 1, 'title' => '隐藏'],
        ['value' => 2, 'title' => '只读'],
    ];

    const OPTION_FIXED = [
        ['value' => 'left', 'title' => '左'],
        ['value' => 'right', 'title' => '右'],
    ];
    const OPTION_ALIGN = [
        ['value' => 'center', 'title' => '居中'],
    ];
    const OPTION_TYPE = [
        ['value' => 'text', 'title' => '文本'],
        ['value' => 'number', 'title' => '数字'],
        ['value' => 'datetime', 'title' => '日期时间'],
        ['value' => 'date', 'title' => '日期'],
        ['value' => 'time', 'title' => '时间'],
        ['value' => 'year', 'title' => '年'],
        ['value' => 'month', 'title' => '月'],
    ];

    const OPTION_CATEGORY = [
        ['value' => 'input', 'title' => '单行文本'],
        ['value' => 'textarea', 'title' => '多行文本'],
        ['value' => 'select', 'title' => '下拉框'],
        ['value' => 'radio', 'title' => '单选框'],
        ['value' => 'checkbox', 'title' => '复选框'],
        ['value' => 'layDate', 'title' => '日期时间'],
        ['value' => 'imageUpload', 'title' => '单图上传'],
        ['value' => 'imageUploadMultiple', 'title' => '多图上传'],
        ['value' => 'editor', 'title' => '富文本'],
        ['value' => 'file', 'title' => '文件上传'],
    ];

    const OPTION_SHOW_TYPE = [
        ['value' => 'url', 'title' => '链接'],
        ['value' => 'image', 'title' => '单图'],
        ['value' => 'imageMultiple', 'title' => '多图'],
    ];

    const OPTION_BTN_COLOR = [
        ['value' => 'layui-bg-blue', 'title' => '蓝色'],
        ['value' => 'layui-bg-orange', 'title' => '橙色'],
        ['value' => 'layui-bg-red', 'title' => '红色'],
        ['value' => 'layui-bg-purple', 'title' => '紫色'],
        ['value' => 'layui-btn-primary', 'title' => '白色'],
    ];

    /**
     * 获取配置
     * @return array
     */
    public function getConfig()
    {
        return [
            'table_config' => [
                'title' => '表',
                'data' => [
                    ['title' => '表名', 'field' => 'table', 'width' => 200, "option" => []],
                    ['title' => '主键', 'field' => 'primary_key', 'width' => 100, "option" => []],
                    ['title' => '连接类型', 'field' => 'join', 'width' => 100, "option" => self::OPTION_JOIN],
                    ['title' => '外键', 'field' => 'field_1', 'width' => 100, "option" => []],
                    ['title' => '关联对象', 'field' => 'field_2', 'width' => 350, "option" => []],
                    ['title' => '同步IO', 'field' => 'synch_io', 'width' => 80, "option" => self::OPTION_SWITCH],
                ],
            ],
            'cols_config' => [
                'title' => '列表',
                'data' => [
                    ['title' => '表名', 'field' => 'table', 'width' => 200, "option" => []],
                    ['title' => '字段', 'field' => 'field', 'width' => 120, "option" => []],
                    ['title' => '别名', 'field' => 'alias', 'width' => 120, "option" => []],
                    ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
                    ['title' => '宽度', 'field' => 'width', 'width' => 80, "option" => [], 'value' => 120],
                    ['title' => '固定', 'field' => 'fixed', 'width' => 80, "option" => self::OPTION_FIXED],
                    ['title' => '对齐', 'field' => 'align', 'width' => 80, "option" => self::OPTION_ALIGN, 'value' => 'center'],
                    ['title' => '排序', 'field' => 'sort', 'width' => 80, "option" => self::OPTION_SWITCH],
                    ['title' => '选项', 'field' => 'option', 'width' => 120, "option" => OptionService::all()],
                    ['title' => '显示', 'field' => 'show_type', 'width' => 80, "option" => self::OPTION_SHOW_TYPE],
                ],
            ],
            'toolbar_config' => [
                'title' => '顶部按钮',
                'data' => [
                    ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
                    ['title' => '事件名', 'field' => 'event', 'width' => 100, "option" => []],
                    ['title' => '颜色', 'field' => 'color', 'width' => 120, "option" => self::OPTION_BTN_COLOR],
                    ['title' => '权限ID', 'field' => 'function_id', 'width' => 80],
                ]
            ],
            'tool_config' => [
                'title' => '行按钮',
                'data' => [
                    ['title' => '父事件', 'field' => 'bind', 'width' => 100],
                    ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
                    ['title' => '事件名', 'field' => 'event', 'width' => 100, "option" => []],
                    ['title' => '颜色', 'field' => 'color', 'width' => 120, "option" => self::OPTION_BTN_COLOR],
                    ['title' => '权限ID', 'field' => 'function_id', 'width' => 80],
                ]
            ],
            'search_config' => [
                'title' => '搜索',
                'data' => [
                    ['title' => '表名', 'field' => 'table', 'width' => 200, "option" => []],
                    ['title' => '字段', 'field' => 'field', 'width' => 120, "option" => []],
                    ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
                    ['title' => '分类', 'field' => 'category', 'width' => 120, "option" => self::OPTION_CATEGORY, 'value' => 'input'],
                    ['title' => '选项', 'field' => 'option', 'width' => 120, "option" => OptionService::all()],
                    ['title' => '类型', 'field' => 'type', 'width' => 120, "option" => self::OPTION_TYPE],
                    ['title' => '范围', 'field' => 'range', 'width' => 80, "option" => self::OPTION_SWITCH],
                    ['title' => '常用搜索', 'field' => 'show_const', 'width' => 100, "option" => self::OPTION_SWITCH, 'value' => 1],
                ]
            ],
            'form_config' => [
                'title' => '表单',
                'data' => [
                    ['title' => '表名', 'field' => 'table', 'width' => 200, "option" => []],
                    ['title' => '字段', 'field' => 'field', 'width' => 120, "option" => []],
                    ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
                    ['title' => '分类', 'field' => 'category', 'width' => 120, "option" => self::OPTION_CATEGORY, 'value' => 'input'],
                    ['title' => '必填', 'field' => 'required', 'width' => 80, "option" => self::OPTION_SWITCH],
                    ['title' => '默认值', 'field' => 'value', 'width' => 80, "option" => []],
                    ['title' => '选项', 'field' => 'option', 'width' => 120, "option" => OptionService::all()],
                    ['title' => '类型', 'field' => 'type', 'width' => 120, "option" => self::OPTION_TYPE],
                    ['title' => '编辑状态', 'field' => 'ban_edit', 'width' => 80, "option" => self::OPTION_EDIT],
                    ['title' => '自定义', 'field' => 'custom_class', 'width' => 80, "option" => []],
                ]
            ],
            'info_config' => [
                'title' => '详情',
                'data' => [
                    ['title' => '表名', 'field' => 'table', 'width' => 200, "option" => []],
                    ['title' => '字段', 'field' => 'field', 'width' => 120, "option" => []],
                    ['title' => '标题', 'field' => 'title', 'width' => 100, "option" => []],
                    ['title' => '分类', 'field' => 'category', 'width' => 120, "option" => self::OPTION_CATEGORY, 'value' => 'input'],
                    ['title' => '选项', 'field' => 'option', 'width' => 120, "option" => OptionService::all()],
                    ['title' => '类型', 'field' => 'type', 'width' => 120, "option" => self::OPTION_TYPE],
                    ['title' => '自定义', 'field' => 'custom_class', 'width' => 80, "option" => []],
                ]
            ],
        ];
    }

    public function indexView()
    {
        return $this->view('lzadmin/model/index');
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
        $query = ModelModel::query();
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
        $model = new ModelModel();
        $model->title = $title;
        $model->table_config = [];
        $model->cols_config = [];
        $model->search_config = [];
        $model->form_config = [];
        $model->toolbar_config = [];
        $model->tool_config = [];
        $model->info_config = [];
        $model->choose_type = '';
        $result = $model->save();
        if (!$result) {
            return $this->error();
        }
        ModelService::refreshCache($model->id);
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
        $model = ModelModel::find($id);
        if (empty($model)) {
            return $this->error('模型不存在');
        }
        $model->$field = $value;
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        ModelService::refreshCache($id);
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
        $model = ModelModel::find($id);
        if (empty($model)) {
            return $this->error('模型不存在');
        }
        $result = $model->delete();
        if (!$result) {
            return $this->error();
        }
        ModelService::deleteCache($id);
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
        $model = ModelModel::find($id);
        if (empty($model)) {
            return $this->error('模型不存在');
        }
        $models = [];
        $config = $this->getConfig();
        foreach ($config as $field => $item) {
            $temp = [];
            foreach ($model->$field as $item1) {
                $arr = [];
                foreach ($item['data'] as $f) {
                    $arr[$f['field']] = $item1[$f['field']] ?? '';
                }
                $temp[] = $arr;
            }
            $models[$field] = $temp;
        }
        $config = $this->getConfig();
        $mod = $model;
        return $this->view('lzadmin/model/config', compact('config', 'models', 'mod'));
    }

    /**
     * 修改模型配置
     * @param Request $request
     * @return array
     */
    public function updateConfig(Request $request)
    {
        $id = (int)$request->input('id');
        $model = ModelModel::find($id);
        if (empty($model)) {
            return $this->error('模型不存在');
        }
        $model->choose_type = $request->input('choose_type');
        $config = $this->getConfig();
        foreach ($config as $field => $item) {
            $data = [];
            $values = (array)$request->input($field);
            foreach ($values as $key => $vals) {
                foreach ($vals as $index => $val) {
                    $data[$index][$key] = $val ?? '';
                }
            }
            $model->$field = $data;
        }
        $result = $model->update();
        if (!$result) {
            return $this->error();
        }
        ModelService::refreshCache($id);
        return $this->success();
    }
}
