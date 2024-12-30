<?php


namespace lz\admin\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SubTableController extends BaseModelController
{

    public function __construct(Request $request)
    {
        $this->model_id = $request->route('id');
        $this->route = '/subTable/' . $this->model_id . '/';
    }

    /**
     * 查询QUERY
     * @param $tableConfig
     * @param $colsConfig
     * @param $month
     * @return \Illuminate\Database\Query\Builder
     */
    public function querySubTable($tableConfig, $colsConfig, $month)
    {
        if (empty($month)) {
            $month = date('Ym');
        } else {
            $month = date('Ym', strtotime($month));
        }
        $table_name = $tableConfig[0]['table'];
        $lastUnderscorePosition = strrpos($table_name, '_');
        if ($lastUnderscorePosition !== false) {
            // 截取字符串，保留最后一个下划线之前的部分
            $table_name = substr($table_name, 0, $lastUnderscorePosition);
        }
        $table_name = $table_name . '_' . $month;
        $query = DB::table($table_name);
        $select = [];
        foreach ($colsConfig as $cols) {
            $select[] = $cols['field'];
        }
        $query->select($select);
        return $query;
    }

    public function getList(Request $request)
    {
        $month = $request->input('month');
        $model = $this->getModel();
        try {
            $query = $this->querySubTable($model['table_config'], $model['cols_config'], $month);
            //排序
            $order_by_field = $request->input('order_by_field');
            $order_by_type = $request->input('order_by_type');
            if ($order_by_field) {
                $query->orderBy($order_by_field, $order_by_type);
            } else {
                $query->orderBy('id', 'DESC');
            }
            //搜索
            foreach ($model['search_config'] as $item) {
                $field = $item['field'];
                if ($field == 'month') {
                    continue;
                }
                $value = $request->input($field);
                if (isset($value)) {
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
        } catch (\Throwable $exception) {
            return $this->error($exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function indexView()
    {
        $view = 'lzadmin/layouts/subTableIndex';
        $data = $this->compact();
        $data['month'] = date('Y-m');
        return view($view, $data);
    }
}
