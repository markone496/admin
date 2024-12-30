<?php


namespace lz\admin\Services;


use lz\admin\Models\RoleModel;
use lz\admin\Traits\ResTrait;

class RoleService
{

    use ResTrait;

    /**
     * 刷新缓存
     * @param $id
     * @return bool
     */
    public static function refreshCache($id = null)
    {
        $key = CacheKeyService::SYS_ROLE;
        $query = RoleModel::query();
        $query->select([
            'id',
            'role_name',
            'function_ids'
        ]);
        if (!empty($id)) {
            $query->where('id', $id);
            $data = $query->first()->toArray();
            RedisService::hset($key, $id, $data);
        } else {
            RedisService::del($key);
            $data = $query->get()->toArray();
            foreach ($data as $datum) {
                RedisService::hset($key, $datum['id'], $datum);
            }
        }
        return true;
    }

    /**
     * 删除缓存
     * @param $role_ids
     * @return bool
     */
    public static function deleteCache($role_ids)
    {
        foreach ($role_ids as $id) {
            $key = CacheKeyService::SYS_ROLE;
            RedisService::hdel($key, $id);
        }
        return true;
    }

    /**
     * 获取缓存
     * @param $role_id
     * @return mixed
     */
    public static function getRoleById($role_id)
    {
        $key = CacheKeyService::SYS_ROLE;
        return RedisService::hget($key, $role_id);
    }


    /**
     * 获取角色选项
     * @return array
     */
    public static function getRoleOption()
    {
        $key = CacheKeyService::SYS_ROLE;
        $roles = RedisService::hgetAll($key);
        $data = [];
        foreach ($roles as $role) {
            $data[] = [
                'value' => (int)$role['id'],
                'title' => $role['role_name']
            ];
        }
        return $data;
    }

}
