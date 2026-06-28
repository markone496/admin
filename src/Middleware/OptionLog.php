<?php

namespace lz\admin\Middleware;

use Closure;
use Illuminate\Http\Request;
use lz\admin\Models\LogModel;

class OptionLog
{

    /**
     * 获取客户端真实 IP（万能方法）
     */
    protected function getClientIp(Request $request)
    {
        // 按优先级顺序尝试获取
        $sources = [
            'CF-Connecting-IP',      // Cloudflare
            'HTTP_CF_CONNECTING_IP', // Cloudflare (备用)
            'X-Forwarded-For',       // 标准代理转发
            'X-Real-IP',             // Nginx 代理
            'HTTP_X_FORWARDED_FOR',  // Apache 等
            'HTTP_X_REAL_IP',        // Apache 等
            'HTTP_CLIENT_IP',        // 客户端 IP
            'REMOTE_ADDR',           // 最终保底
        ];

        foreach ($sources as $source) {
            if (strpos($source, 'HTTP_') === 0) {
                $ip = $request->server($source);
            } else {
                $ip = $request->header($source);
            }

            if ($ip) {
                // 处理多个 IP 情况
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                // 验证 IP 有效性
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }

        // 如果使用 Laravel 内置方法
        return $request->ip();
    }

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
            $ip = $this->getClientIp($request);
            $params = $request->all();
            $model = new LogModel();
            $model->user_id = $request->session()->get("user_id", 0);
            $model->ip = $ip;
            $model->route = $route;
            $model->params = json_encode($params, true);
            $model->save();
        }
        return $next($request);
    }
}
