<?php

namespace lz\admin\Middleware;

use lz\admin\Services\UserService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuth
{
    /**
     * 判断登录
     * @param Request $request
     * @param Closure $next
     * @return JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            //判断是否是超管
            if (UserService::isSuperUser()) {
                return $next($request);
            }
            $routes = UserService::getLoginUserRoutes();
            $route = $request->path();
            if (!in_array($route, $routes)) {
                throw new \Exception();
            }
            return $next($request);
        } catch (\Exception $exception) {
            $method = $request->method();
            if ($method === 'GET') {
                return response()->view('errors.403', [], 403);
            } else {
                return new JsonResponse(['code' => 1, 'msg' => '无权访问'], 200);
            }
        }

    }
}
