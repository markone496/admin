<?php


namespace lz\admin\Controllers;


use Illuminate\Http\Request;


class ModelController extends BaseModelController
{

    public function __construct(Request $request)
    {
        $this->model_id = $request->route('id');
        $this->route = '/model/' . $this->model_id . '/';
    }
}
