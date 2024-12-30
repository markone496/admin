<?php

namespace lz\admin\Traits;

trait ResTrait
{

    /**
     * 成功
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return array
     */
    public static function success($data = [], $msg = '操作成功', $code = 0)
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 失败
     * @param array $data
     * @param string $msg
     * @param int $code
     * @return array
     */
    public static function error($msg = '请求失败', $code = 1, $data = [])
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 响应请求
     * @param $result
     * @return array
     */
    public static function result($result)
    {
        if ($result) {
            return self::success();
        }
        return self::error();
    }
}
