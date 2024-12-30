<?php


namespace lz\admin\Services;


use lz\admin\Models\UserModel;
use lz\admin\Traits\ResTrait;

class UserService
{

    use ResTrait;


    /**
     * 检查是否是超管
     * @return bool
     */
    public static function isSuperUser()
    {
        return session('user_id') === 0;
    }

    /**
     * 加密密码
     * @param $password
     * @return string
     */
    public static function passwordEncryption($password)
    {
        return md5(md5($password) . $password);
    }

    /**
     * 刷新用户角色缓存
     * @param $user_id
     * @return bool
     */
    public static function refreshCache($user_id = null)
    {
        $key = CacheKeyService::SYS_USER;
        $query = UserModel::query()->select([
            'id',
            'account',
            'nickname',
            'role_id',
            'is_disable'
        ]);
        if (!empty($user_id)) {
            $data = $query->where('id', $user_id)->first()->toArray();
            RedisService::hset($key, $user_id, $data);
        } else {
            $data = $query->get()->toArray();
            RedisService::del($key);
            foreach ($data as $datum) {
                RedisService::hset($key, $datum['id'], $datum);
            }
        }
        return true;
    }

    /**
     * 获取用户信息
     * @param $user_id
     * @return mixed
     */
    public static function getUserInfoById($user_id)
    {
        $key = CacheKeyService::SYS_USER;
        return RedisService::hget($key, $user_id);
    }

    /**
     * 删除缓存
     * @param $user_ids
     * @return bool
     */
    public static function deleteCache($user_ids)
    {
        $key = CacheKeyService::SYS_USER;
        foreach ($user_ids as $user_id) {
            RedisService::hdel($key, $user_id);
        }
        return true;
    }

    /**
     * 判断用户是否被禁用
     * @param $user_id
     * @return bool
     */
    public static function isDisable($user_id)
    {
        $user = self::getUserInfoById($user_id);
        return $user['is_disable'] ?? true;
    }

    /**
     * 获取登录用户拥有的权限ID
     * @return array
     */
    public static function getLoginUserFunctionIds()
    {
        $user_id = session('user_id');
        $user = self::getUserInfoById($user_id);
        $role = RoleService::getRoleById($user['role_id']);
        return $role['function_ids'];
    }

    /**
     * 获取登录用户拥有的菜单ID
     * @return array
     */
    public static function getLoginUserMenuIds()
    {
        if (self::isSuperUser()) {
            return RedisService::hKeys(CacheKeyService::SYS_MENU);
        }
        $menu_ids = [];
        $function_ids = self::getLoginUserFunctionIds();
        foreach ($function_ids as $function_id) {
            $function = FunctionService::getFunctionById($function_id);
            $menu_ids = array_merge($menu_ids, $function['menu_ids']);
        }
        return array_unique($menu_ids);
    }

    /**
     * 获取登录用户拥有的路由
     * @return array
     */
    public static function getLoginUserRoutes()
    {
        $function_ids = self::getLoginUserFunctionIds();
        $routes = [];
        foreach ($function_ids as $function_id) {
            $function = FunctionService::getFunctionById($function_id);
            $routes = array_merge($routes, $function['route']);
        }
        return $routes;
    }

    /**
     * 判断当前用户是否有权限
     * @param $function_id
     * @return bool
     */
    public static function loginUserHasFunction($function_id)
    {
        if (self::isSuperUser()) {
            return true;
        }
        $function_ids = StaticDataService::getData('login-user-function', function () {
            return self::getLoginUserFunctionIds();
        });
        return in_array($function_id, $function_ids);
    }

}
