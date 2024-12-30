<?php


namespace lz\admin\Controllers;


use lz\admin\Services\FormService;
use lz\admin\Services\ModelService;
use lz\admin\Services\OptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class BaseModelController extends Controller
{
    /**
     * 模型ID
     * @var int
     */
    public $model_id = 0;


    /**
     * 路由
     * @var string
     */
    public $route = '';


    /**
     * 获取模型数据
     * @return \lz\admin\Models\BaseModel|\Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function getModel()
    {
        if ($this->model_id < 0) {
            throw new \Exception("请先设置模型ID");
        }
        $model = ModelService::getModelById($this->model_id);
        if (empty($model)) {
            throw new \Exception("模型不存在");
        }
        return $model;
    }

    /**
     * 顶部按钮
     * @param $toolbar_config
     * @return array
     */
    protected function toolbar($toolbar_config)
    {
        $data = [];
        foreach ($toolbar_config as $item) {
            //todo 权限过滤
            $function_id = (int)$item['function_id'];
            if (!empty($function_id) && !isAuth($function_id)) {
                continue;
            }
            $data[] = [
                'title' => $item['title'],
                'event' => $item['event'],
                'color' => $item['color']
            ];
        }
        return $data;
    }

    /**
     * 行按钮
     * @param $tool_config
     * @return array
     */
    protected function tool($tool_config)
    {
        $data = [];
        foreach ($tool_config as $item) {
            //todo 权限过滤
            $function_id = (int)$item['function_id'];
            if (!empty($function_id) && !isAuth($function_id)) {
                continue;
            }
            if (empty($item['bind'])) {
                $data[$item['event']] = [
                    'title' => $item['title'],
                    'event' => $item['event'],
                    'color' => $item['color'],
                    'data' => []
                ];
            } else {
                if (empty($data[$item['bind']])) {
                    continue;
                }
                //追加分割线
                if (!empty($data[$item['bind']]['data'])) {
                    $data[$item['bind']]['data'][] = [
                        'type' => '-'
                    ];
                }
                $data[$item['bind']]['data'][] = [
                    'title' => $item['title'],
                    'event' => $item['event']
                ];
            }
        }
        return $data;
    }

    /**
     * 列表字段
     * @param $colsConfig
     * @param $tool
     * @param $choose_type
     * @return array
     */
    public function cols($colsConfig, $tool, $choose_type)
    {
        $config = [];
        switch ($choose_type) {
            case 'checkbox':
                $config[] = [
                    'type' => 'checkbox',
                    'fixed' => 'left'
                ];
                break;
            case 'radio':
                $config[] = [
                    'type' => 'radio',
                    'fixed' => 'left'
                ];
                break;
            default:
                break;
        }
        foreach ($colsConfig as $item) {
            $field = empty($item['alias']) ? $item['field'] : $item['alias'];
            $temp = [
                'field' => $field,
                'title' => $item['title'],
                'width' => empty($item['width']) ? 100 : $item['width'],
                'align' => $item['align'],
                'fixed' => $item['fixed'],
                'sort' => $item['sort']
            ];
            if (!empty($item['show_type'])) {
                $temp['escape'] = false;
            }
            if (!empty($item['option'])) {
                $temp['escape'] = false;
            }
            $config[] = $temp;
        }
        if (!empty($tool)) {
            $config[] = [
                'title' => '#',
                'width' => count($tool) * 90,
                'align' => 'center',
                'toolbar' => '#toolbar',
                'fixed' => 'right'
            ];
        }
        return $config;
    }

    /**
     * 搜索表单
     * @param $searchConfig
     * @return array
     */
    private function search($searchConfig)
    {
        $constForm = $otherForm = [];
        foreach ($searchConfig as $item) {
            $type = $item['type'] ?? null;
            $range = $item['range'] ?? false;
            $option = [];
            if (!empty($item['option'])) {
                $option = OptionService::getOptionById($item['option']);
            }
            $html = FormService::formRender($item['category'], $item['title'], $item['field'], true, null, false, $type, $range, $option, '', '');
            $otherForm[] = $html;
            if (!empty($item['show_const'])) {
                $constForm[] = str_replace('layui-input-block', 'layui-input-inline', $html);
            }
        }
        return [
            'const' => $constForm,
            'other' => $otherForm,
        ];
    }

    /**
     * 初始化数据
     * @return array
     * @throws \Exception
     */
    protected function compact()
    {
        $model = $this->getModel();
        //模型名称
        $title = $model['title'];
        //顶部按钮
        $toolbar = $this->toolbar($model['toolbar_config']);
        //行按钮
        $tool = $this->tool($model['tool_config']);
        //列
        $cols = $this->cols($model['cols_config'], $tool, $model['choose_type']);
        //搜索
        $search = $this->search($model['search_config']);
        //主键
        $primary_key = $model['table_config'][0]['primary_key'];
        //路由
        $route = $this->route;
        return compact('title', 'route', 'search', 'cols', 'toolbar', 'tool', 'primary_key');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function indexView()
    {
        $view = 'lzadmin/layouts/index';
        $config = config('admin')['model_script'];
        if (!empty($config[$this->model_id])) {
            $view = $config[$this->model_id];
        }
        return view($view, $this->compact());
    }

    /**
     * 详情页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function infoView(Request $request)
    {
        $model = $this->getModel();
        $primary_key = $request->input('primary_key');
        $infoConfig = $model['info_config'];
        $data = [];
        $tables = [];
        foreach ($infoConfig as $item) {
            if (empty($tables[$item['table']])) {
                $tables[$item['table']] = [];
            }
            $tables[$item['table']][] = $item['field'];
        }
        foreach ($model['table_config'] as $index => $item) {
            if (!empty($tables[$item['table']])) {
                $field = $index ? $item['field_1'] : $item['primary_key'];
                $arr = (array)DB::table($item['table'])->where($field, $primary_key)->select($tables[$item['table']])->first();
                $data = array_merge($data, $arr);
            }
        }
        foreach ($infoConfig as $key => &$item) {
            $item['ban_edit'] = 2;
            $item['required'] = '';
            if (isset($data[$item['field']])) {
                $value = $data[$item['field']];
                if (in_array($item['category'], ['checkbox', 'imageUploadMultiple'])) {
                    $value = json_decode($value);
                }
                $infoConfig[$key]['value'] = $value;
            }
        }
        $editForm = $this->edit($infoConfig, true);
        return $this->view('/lzadmin/sys/edit', compact('editForm'));
    }


    /**
     * 查询QUERY
     * @param $tableConfig
     * @param $colsConfig
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query($tableConfig, $colsConfig)
    {
        $query = DB::table($tableConfig[0]['table']);
        $arr_count = count($tableConfig);
        if ($arr_count > 1) {
            for ($i = 1; $i < $arr_count; $i++) {
                $item = $tableConfig[$i];
                switch ($item['join']) {
                    case 'left':
                        $query->leftJoin($item['table'], $item['table'] . '.' . $item['field_1'], $item['field_2']);
                        break;
                    case 'right':
                        $query->rightJoin($item['table'], $item['table'] . '.' . $item['field_1'], $item['field_2']);
                        break;
                    case 'inner':
                        $query->join($item['table'], $item['table'] . '.' . $item['field_1'], $item['field_2']);
                        break;
                    default:
                        break;
                }

            }
        }
        $select = [];
        foreach ($colsConfig as $cols) {
            $field = $cols['table'] . '.' . $cols['field'];
            if (!empty($cols['alias'])) {
                $field .= ' as ' . $cols['alias'];
            }
            $select[] = $field;
        }
        $query->select($select);
        return $query;
    }

    /**
     * 获取排序字段
     * @param $field
     * @param $cols
     * @return string
     */
    private function getOrderByField($field, $cols)
    {
        $orderByField = '';
        foreach ($cols as $col) {
            if ($field == $col['alias']) {
                $orderByField = $col['table'] . '.' . $col['field'];
                break;
            }
        }
        if (!empty($orderByField)) {
            return $orderByField;
        }
        foreach ($cols as $col) {
            if ($field == $col['field']) {
                $orderByField = $col['table'] . '.' . $col['field'];
                break;
            }
        }
        return $orderByField;
    }

    /**
     * 查询
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getList(Request $request)
    {
        $model = $this->getModel();
        $query = $this->query($model['table_config'], $model['cols_config']);
        //排序
        $order_by_field = $request->input('order_by_field');
        $order_by_type = $request->input('order_by_type');
        if ($order_by_field) {
            $order_by_field = $this->getOrderByField($order_by_field, $model['cols_config']);
            $query->orderBy($order_by_field, $order_by_type);
        } else {
            $primary_table = $model['table_config'][0];
            $query->orderBy($primary_table['table'] . '.' . $primary_table['primary_key'], 'DESC');
        }
        //搜索
        foreach ($model['search_config'] as $item) {
            $field = $item['field'];
            $value = $request->input($field);
            if (isset($value)) {
                $table = $item['table'];
                $field = $table . '.' . $field;
                if ($item['category'] == 'checkbox') {
                    $query->whereIn($field, $value);
                } else {
                    if (!empty($item['range'])) {//范围搜索
                        $value = explode(' - ', $value);
                        $query->whereBetween($field, $value);
                    } else {
                        $query->where($field, $value);
                    }
                }
            }
        }
        return $this->layerPaginate($query);
    }

    /**
     * 格式化列表数据
     * @param $data
     * @throws \Exception
     */
    public function layerPaginateDataFormat(&$data)
    {
        $model = $this->getModel();
        foreach ($data as &$datum) {
            $this->modelFormat($datum, $model);
        }
    }

    /**
     * 格式化数据
     * @param $datum
     * @param $model
     * @return array
     */
    protected function modelFormat(&$datum, $model)
    {
        $options = [];
        $optionFields = [];
        $formatTypeFields = [];
        foreach ($model['cols_config'] as $cols) {
            $field = empty($cols['alias']) ? $cols['field'] : $cols['alias'];
            $option = (int)$cols['option'];
            if (!empty($option)) {
                $optionFields[$field] = $option;
                if (empty($options[$option])) {
                    $options[$option] = OptionService::getOptionById($option);
                }
                continue;
            }
            if (!empty($cols['show_type'])) {
                $formatTypeFields[$field] = $cols['show_type'];
            }
        }
        if (!empty($optionFields) || !empty($formatTypeFields)) {
            foreach ($optionFields as $field => $option) {
                if (!empty($options[$option])) {
                    $value = json_decode($datum->$field);
                    if (is_array($value)) {
                        $temp_arr = [];
                        foreach ($value as $val) {
                            if (!empty($options[$option][$val])) {
                                $obj = $options[$option][$val];
                                $temp_arr[] = "<span style='margin-top: 5px' class='layui-badge {$obj['color']}'>{$obj['title']}</span>";
                            }
                        }
                        $datum->$field = implode(' ', $temp_arr);
                    } else {
                        $value = $datum->$field;
                        if ($value !== null && $value !== '') {
                            if (!empty($options[$option][$value])) {
                                $obj = $options[$option][$value];
                                $datum->$field = "<span style='margin-top: 5px' class='layui-badge {$obj['color']}'>{$obj['title']}</span>";
                            }
                        }
                    }
                }
            }
            foreach ($formatTypeFields as $field => $type) {
                if (empty($datum->$field)) {
                    continue;
                }
                switch ($type) {
                    case 'url':
                        $datum->$field = "<a target='_blank' style='color: #1e9fff!important' href='{$datum->$field}'>{$datum->$field}</a>";
                        break;
                    case 'image':
                        $datum->$field = "<div class='image-show-box'><img src='{$datum->$field}'></div>";
                        break;
                    case 'imageMultiple':
                        $images = (array)json_decode($datum->$field);
                        $temp = "<div class='image-show-box'>";
                        foreach ($images as $image) {
                            $temp .= "<img src='{$image}'>";
                        }
                        $temp .= '</div>';
                        $datum->$field = $temp;
                        break;
                }
            }
        }
    }

    /**
     * 编辑表单渲染
     * @param $config
     * @param $edit_status
     * @return array
     */
    protected function edit($config, $edit_status)
    {
        $editForm = [];
        foreach ($config as $item) {
            $value = $item['value'] ?? null;
            $type = $item['type'] ?? null;
            $option = [];
            if (!empty($item['option'])) {
                $option = OptionService::getOptionById($item['option']);
            }
            $field = $item['table'] . "[{$item['field']}]";
            $ban_edit = ($edit_status && $item['ban_edit'] == 2 ? true : false);
            $editForm[] = FormService::formRender($item['category'], $item['title'], $field, true, $value, $item['required'], $type, false, $option, '', $ban_edit);
        }
        return $editForm;
    }

    /**
     * 表单页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function editView(Request $request)
    {
        $model = $this->getModel();
        $primary_key = $request->input('primary_key');
        $formConfig = $model['form_config'];
        $edit_status = false;
        if (!empty($primary_key)) {
            $edit_status = true;
            $data = [];
            $tables = [];
            foreach ($formConfig as $item) {
                if ($item['ban_edit'] == 1) {
                    continue;
                }
                if (empty($tables[$item['table']])) {
                    $tables[$item['table']] = [];
                }
                $tables[$item['table']][] = $item['field'];
            }
            foreach ($model['table_config'] as $index => $item) {
                if (!empty($tables[$item['table']])) {
                    $field = $index ? $item['field_1'] : $item['primary_key'];
                    $arr = (array)DB::table($item['table'])->where($field, $primary_key)->select($tables[$item['table']])->first();
                    $data = array_merge($data, $arr);
                }
            }
            foreach ($formConfig as $key => &$item) {
                if ($item['ban_edit'] == 1) {
                    unset($formConfig[$key]);
                    continue;
                }
                $formConfig[$key]['value'] = null;
                if (isset($data[$item['field']])) {
                    $value = $data[$item['field']];
                    if (in_array($item['category'], ['checkbox', 'imageUploadMultiple'])) {
                        $value = json_decode($value);
                    }
                    $formConfig[$key]['value'] = $value;
                }
            }
        }
        $editForm = $this->edit($formConfig, $edit_status);
        return $this->view('/lzadmin/sys/edit', compact('editForm'));
    }

    /**
     * 保存数据前验证参数
     * @param $table
     * @param $field
     * @param $value
     * @return bool|string 返回false通过 否则返回未通过信息
     */
    public function saveBeforeVerify($table, $field, $value)
    {
        return false;
    }

    /**
     * 保存数据前格式化值
     * @param $table
     * @param $field
     * @param $value
     * @return mixed
     */
    public function saveBeforeValueFormat($table, $field, $value)
    {
        return $value;
    }

    /**
     * 新增
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $model = $this->getModel();
        $models = [];
        foreach ($model['form_config'] as $item) {
            $table = $item['table'];
            $field = $item['field'];
            $tableValue = $request->input($table);
            $value = $tableValue[$field] ?? null;
            if (!empty($item['required'])) {
                if (!isset($value)) {
                    return $this->error('【' . $item['title'] . '】参数必填');
                }
                if ($item['category'] == 'editor' && $value == '<p><br></p>') {
                    return $this->error('【' . $item['title'] . '】参数必填');
                }
            }
            if ($verify_msg = $this->saveBeforeVerify($table, $field, $value)) {
                return $this->error('【' . $item['title'] . '】' . $verify_msg);
            }
            if (in_array($item['category'], ['checkbox', 'imageUploadMultiple'])) {
                $valueData = [];
                foreach ($value as $val) {
                    $valueData[] = $val;
                }
                $value = json_encode($valueData, true);
            }
            if (empty($models[$table])) {
                $models[$table] = [];
            }
            $models[$table][$field] = $this->saveBeforeValueFormat($table, $field, $value);
        }
        DB::beginTransaction();
        try {
            $primary_table_id = 0;
            foreach ($model['table_config'] as $index => $item) {
                if (!empty($models[$item['table']])) {
                    if ($index == 0) {//主表
                        $primary_table_id = DB::table($item['table'])->insertGetId($models[$item['table']]);
                        if (!$primary_table_id) {
                            throw new \Exception('数据保存失败');
                        }
                    } else {
                        //判断是否开启同步IO
                        if (empty($item['synch_io'])) {
                            continue;
                        }
                        $data = $models[$item['table']];
                        $data[$item['field_1']] = $primary_table_id;
                        $result = DB::table($item['table'])->insert($data);
                        if (!$result) {
                            throw new \Exception('数据保存失败');
                        }
                    }
                }
            }
            DB::commit();
            return $this->success($primary_table_id);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    /**
     * 修改
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $model = $this->getModel();
        $primary_key = $request->input('primary_key');
        $models = [];
        foreach ($model['form_config'] as $item) {
            if (!empty($item['ban_edit'])) {
                continue;
            }
            $table = $item['table'];
            $field = $item['field'];
            $tableValue = $request->input($table);
            $value = $tableValue[$field] ?? null;
            if (!empty($item['required'])) {
                if (!isset($value)) {
                    return $this->error('【' . $item['title'] . '】参数必填');
                }
                if ($item['category'] == 'editor' && $value == '<p><br></p>') {
                    return $this->error('【' . $item['title'] . '】参数必填');
                }
            }
            if (in_array($item['category'], ['checkbox', 'imageUploadMultiple'])) {
                $valueData = [];
                foreach ($value as $val) {
                    $valueData[] = $val;
                }
                $value = json_encode($valueData, true);
            }
            if (empty($models[$table])) {
                $models[$table] = [];
            }
            $models[$table][$field] = $value;
        }

        DB::beginTransaction();
        try {
            $primary_field = '';
            foreach ($model['table_config'] as $index => $item) {
                if ($index == 0) {
                    $primary_field = $item['table'] . '.' . $item['primary_key'];
                }
                if (!empty($models[$item['table']])) {
                    if ($index == 0) {//主表
                        $result = DB::table($item['table'])->where($item['primary_key'], $primary_key)->update($models[$item['table']]);
                        if (!is_int($result)) {
                            throw new \Exception('数据保存失败');
                        }
                    } else {
                        //判断是否开启同步IO
                        if (empty($item['synch_io'])) {
                            continue;
                        }
                        $result = DB::table($item['table'])->where($item['field_1'], $primary_key)->update($models[$item['table']]);
                        if (!is_int($result)) {
                            throw new \Exception('数据保存失败');
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
        $query = $this->query($model['table_config'], $model['cols_config']);
        $query->where($primary_field, $primary_key);
        $datum = $query->first();
        $this->modelFormat($datum, $model);
        return $this->success($datum);
    }

    /**
     * 根据主键获取模型
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     * @throws \Exception
     */
    protected function modelById($id)
    {
        $model = $this->getModel();
        $query = $this->query($model['table_config'], $model['cols_config']);
        $primary_table = $model['table_config'][0];
        $primary_field = $primary_table['table'] . '.' . $primary_table['primary_key'];
        $query->where($primary_field, $id);
        $datum = $query->first();
        $this->modelFormat($datum, $model);
        return $datum;
    }


    /**
     * 删除
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $model_delete = config('admin')['model_delete'];
        if (empty($model_delete)) {
            return $this->error('已关闭模型删除数据功能');
        }
        $model = $this->getModel();
        $primary_key = (array)$request->input('primary_key');
        if (empty($primary_key)) {
            return $this->error();
        }
        DB::beginTransaction();
        try {
            foreach ($model['table_config'] as $index => $item) {
                if ($index == 0) {//主表
                    $result = DB::table($item['table'])->whereIn($item['primary_key'], $primary_key)->delete();
                    if (!is_int($result)) {
                        throw new \Exception('数据删除失败');
                    }
                } else {
                    //判断是否开启同步IO
                    if (empty($item['synch_io'])) {
                        continue;
                    }
                    $result = DB::table($item['table'])->whereIn($item['field_1'], $primary_key)->delete();
                    if (!is_int($result)) {
                        throw new \Exception('数据删除失败');
                    }
                }
            }
            DB::commit();
            return $this->success();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->error($exception->getMessage());
        }
    }

    /**
     * 调用api
     * @param $path
     * @param $data
     * @return mixed|null
     */
    public static function httpApi($path, $data)
    {
        try {
            $url = env('API_URL');
//            $url = 'http://172.24.170.153:8400';
            $data['pwd'] = 'a1d2m3i4n5';
            $re = Http::post($url . $path, $data);
            $body = $re->body();
            if (!is_string($body)) {
                return null;
            }
            $arr = json_decode($re->body(), true);

            if (!is_array($arr) || !isset($arr['code']) || !isset($arr['msg'])) {
                return null;
            }

            return $arr;

        } catch (\Exception $e) {
            return null;
        }
    }

}
