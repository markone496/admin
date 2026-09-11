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
        try {
            // 长度校验：8-20 位（按字符数计算）
            $len = mb_strlen($password, 'UTF-8');
            if ($len < 8 || $len > 20) {
                throw new Exception('');
            }

            // 允许的特殊符号集合（可根据业务调整）
            $allowedSpecialChars = '!@#$%';

            // 1. 必须包含大写字母
            if (!preg_match('/[A-Z]/', $password)) {
                throw new Exception('');
            }
            // 2. 必须包含小写字母
            if (!preg_match('/[a-z]/', $password)) {
                throw new Exception('');
            }
            // 3. 必须包含数字
            if (!preg_match('/[0-9]/', $password)) {
                throw new Exception('');
            }

            // 4. 必须包含至少一个允许的特殊符号
            $specialPattern = '/[' . preg_quote($allowedSpecialChars, '/') . ']/';
            if (!preg_match($specialPattern, $password)) {
                throw new Exception('');
            }
            // 5. 密码中不允许出现允许字符集之外的字符
            $allowedPattern = '/^[A-Za-z0-9' . preg_quote($allowedSpecialChars, '/') . ']+$/';
            if (!preg_match($allowedPattern, $password)) {
                throw new Exception('');
            }
            return false;
        } catch (\Throwable $exception) {
            return '请输入8-20位密码，必须包含大小写字母数字和特殊符号。字符仅限于!@#$%';
        }
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
