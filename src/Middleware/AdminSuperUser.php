<?php

namespace lz\admin\Middleware;

use lz\admin\Services\UserService;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSuperUser
{

    public function handle(Request $request, Closure $next)
    {
        $method = $request->method();
        if(!UserService::isSuperUser()){
            if ($method === 'GET') {
                return redirect('403');
            } else {
                return new JsonResponse(['code' => 1, 'msg' => '无权操作'], 200);
            }
        }
        return $next($request);
    }
}
