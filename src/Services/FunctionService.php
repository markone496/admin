<?php


namespace lz\admin\Services;


use lz\admin\Models\FunctionModel;
use lz\admin\Traits\ResTrait;

class FunctionService
{

    use ResTrait;

    /**
     * 刷新缓存
     * @param $function_id
     * @return bool
     */
    public static function refreshCache($function_id = null)
    {
        //刷新权限
        $key = CacheKeyService::SYS_FUNCTION;
        $query = FunctionModel::query()->select([
            'id',
            'title',
            'route',
            'menu_id',
            'menu_ids'
        ]);
        if (!empty($function_id)) {
            $data = $query->where('id', $function_id)->first()->toArray();
            //刷新菜单权限
            $menu_func_key = CacheKeyService::SYS_MENU_FUNCTION . $data['menu_id'];
            RedisService::hset($menu_func_key, $data['id'], $data);
            //刷新权限
            RedisService::hset($key, $data['id'], $data);
        } else {
            $data = $query->get()->toArray();
            RedisService::del($key);
            $menu_func_keys = RedisService::keys(CacheKeyService::SYS_MENU_FUNCTION . '*');
            foreach ($menu_func_keys as $menu_func) {
                $menu_func_key = str_replace(env('REDIS_PREFIX'), '', $menu_func);
                RedisService::del($menu_func_key);
            }
            foreach ($data as $datum) {
                //刷新菜单权限
                $menu_func_key = CacheKeyService::SYS_MENU_FUNCTION . $datum['menu_id'];
                RedisService::hset($menu_func_key, $datum['id'], $datum);
                //刷新权限
                RedisService::hset($key, $datum['id'], $datum);
            }
        }
        return true;
    }

    /**
     * 删除缓存
     * @param $menu_id
     * @param $function_ids
     * @return bool
     */
    public static function deleteCache($menu_id, $function_ids = [])
    {
        $menu_func_key = CacheKeyService::SYS_MENU_FUNCTION . $menu_id;
        if (empty($function_ids)) {
            $function_ids = RedisService::hKeys($menu_func_key);
        }
        $key = CacheKeyService::SYS_FUNCTION;
        foreach ($function_ids as $function_id) {
            RedisService::hdel($menu_func_key, $function_id);
            RedisService::hdel($key, $function_id);
        }
        return true;
    }

    /**
     * 获取菜单权限
     * @param $menu_id
     * @return array
     */
    public static function getFunctionsByMenuId($menu_id)
    {
        $key = CacheKeyService::SYS_MENU_FUNCTION . $menu_id;
        return (array)RedisService::hgetAll($key);
    }

    /**
     * 获取权限缓存
     * @param $function_id
     * @return mixed
     */
    public static function getFunctionById($function_id)
    {
        $key = CacheKeyService::SYS_FUNCTION;
        return RedisService::hget($key, $function_id);
    }
}
