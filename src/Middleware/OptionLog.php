<?php

namespace lz\admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use lz\admin\Models\LogModel;

class OptionLog
{
    /**
     * 请求日志
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->path();
        if (!in_array($route, ['sys/log', 'sys/log/list'])) {
            $params = $request->all();
            $model = new LogModel();
            $model->user_id = $request->session()->get("user_id", 0);
            $model->ip = $request->ip();
            $model->route = $route;
            $model->params = json_encode($params, true);
            $model->save();
        }
        return $next($request);
    }
}
