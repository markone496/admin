<?php

/**
 * 静态资源加载
 */
if (!function_exists('customAsset')) {
    function customAsset($path)
    {
        $assetPath = asset($path, request()->isSecure());
        $assetPath .= '?v=' . env('ASSET_VERSION');
        return $assetPath;
    }
}

/**
 * 递归获取树形结构
 */
if (!function_exists('customArrFormatTree')) {
    function customArrFormatTree(array $data, $parentId = 0, $pid_key = 'parent_id', $child_key = 'children', callable $call = null)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v[$pid_key] == $parentId) {
                $v[$child_key] = customArrFormatTree($data, $v['id'], $pid_key, $child_key, $call);
                $call && $call($v);
                $tree[] = $v;
            }
        }
        return $tree;
    }
}

/**
 * 解密
 */
if (!function_exists('customDecrypt')) {
    function customDecrypt($str)
    {
        // 密钥key要和前端一致,前端文件位于/public/assets/base.js
        $cryptKey = 'GftZqNEoBVdB2kwx';
        // iv也是一样要和前端一致
        $iv = '3zyJFPEzh5rUeUNi';
        // 然后使用openssl_decrypt来进行解密
        return openssl_decrypt($str, 'AES-128-CBC', $cryptKey, 0, $iv);
    }
}


/**
 * 检验账号格式
 */
if (!function_exists('customVerifyAccount')) {
    function customVerifyAccount($account)
    {
        if (!preg_match("/^[0-9a-z_]{5,20}$/i", $account)) {
            return '请输入5-20位账号，字符仅限于A-Za-z0-9_';
        }
        return false;
    }
}

/**
 * 检验密码格式
 */
if (!function_exists('customVerifyPassword')) {
    function customVerifyPassword($password)
    {
        if (!preg_match("/^[0-9a-z_]{5,20}$/i", $password)) {
            return '请输入5-20位密码，字符仅限于A-Za-z0-9_';
        }
        return false;
    }
}

/**
 * 检验权限
 */
if (!function_exists('isAuth')) {
    function isAuth($function_id)
    {
        return \lz\admin\Services\UserService::loginUserHasFunction($function_id);
    }
}
