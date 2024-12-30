<?php


namespace lz\admin\Services;


use lz\admin\Traits\ResTrait;

class CacheKeyService
{

    use ResTrait;

    const SYS_ACCESS_MENU = 'sys:access_menu';//系统可访问菜单

    const SYS_MENU = 'sys:menu';//系统菜单

    const SYS_FUNCTION = 'sys:function';//系统权限

    const SYS_ROLE = 'sys:role';//系统角色

    const SYS_USER = 'sys:user';//系统用户

    const SYS_CONFIG = 'sys:config';//系统配置

    const SYS_MENU_FUNCTION = 'sys:menu-function:';//系统菜单权限

    const SYS_MODEL = 'sys:model';//模型

    const SYS_OPTION = 'sys:option';//选项

    const SYS_TABLE = 'sys:table';//表

    const SYS_TABLE_FIELD = 'sys:table-field:';//表字段

    const SYS_UPLOAD_SIGN = 'sys:upload-sign';//文件上传token

    //登录密码输入错误
    public static function getLoginPasswordErrorTotal($account)
    {
        return 'password-error:' . date('Y-m-d') . ':' . $account;
    }

}
