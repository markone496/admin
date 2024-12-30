<?php

namespace lz\admin\Middleware;

use lz\admin\Services\UserService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminLogin
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
            $user_id = $request->session()->get("user_id");
            if (!is_numeric($user_id)) {
                throw new \Exception('登录过期');
            }
            if (!UserService::isSuperUser() && UserService::isDisable($user_id)) {
                throw new \Exception('账号已被禁用');
            }
            return $next($request);
        } catch (\Exception $exception) {
            $method = $request->method();
            if ($method === 'GET') {
                return redirect('loginOut');
            } else {
                return new JsonResponse(['code' => -1, 'msg' => $exception->getMessage()], 200);
            }
        }

    }
}
