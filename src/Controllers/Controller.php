<?php

namespace lz\admin\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use lz\admin\Traits\ResTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, ResTrait;

    /**
     * 处理试图渲染
     * @param $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view($view, $data = [])
    {
        $view = '/' . $view;
        return view($view, $data);
    }

    /**
     * 分页查询数据格式化
     * @param $data
     */
    public function layerPaginateDataFormat(&$data)
    {
    }

    /**
     * @param $query
     * @return array
     */
    public function layerPaginate($query)
    {
        /*** @var $query \Illuminate\Database\Query\Builder ** */
        $limit = request('limit', 20);
        $result = $query->paginate($limit);
        $total = $result->total();
        $data = $result->items();
        $this->layerPaginateDataFormat($data);
        return ['code' => 0, 'count' => $total, 'data' => $data];
    }

}
