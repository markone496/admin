<?php

namespace lz\admin\Controllers;


use Illuminate\Http\Request;
use lz\admin\Models\UserModel;
use lz\admin\Services\CacheKeyService;
use lz\admin\Services\MenuService;
use lz\admin\Services\RedisService;
use lz\admin\Services\UserService;

class IndexController extends Controller
{

    /**
     * 登录页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function loginView()
    {
        return $this->view('lzadmin/login/index');
    }

    /**
     * 登录
     * @param Request $request
     * @return array
     */
    public function checkLogin(Request $request)
    {
        $account = $request->input('account');
        $password = $request->input('password');
        $code = $request->input('code');
        //校验参数
        if ($verify_msg = customVerifyAccount($account)) {
            return self::error($verify_msg);
        }
        if ($password = customDecrypt($password)) {
            if ($verify_msg = customVerifyPassword($password)) {
                return self::error($verify_msg);
            }
        } else {
            return self::error('密码解析失败');
        }
        if (!preg_match("/^[0-9a-z]{4}$/i", $code)) {
            return self::error('请输入4位验证码，字符仅限于a-z0-9，不区分大小写');
        }
        //校验验证码
        if (!captcha_check($code)) {
            return self::error('验证码输入错误', 2);
        }
        if (env('APP_DEBUG')) {//如果是开发阶段，判断是否是开发账户
            $config = config('admin');
            if (UserService::passwordEncryption($account) == $config['dev_account'] && UserService::passwordEncryption($password) == $config['dev_password']) {
                //存入session
                $request->session()->put("user_id", 0);
                return self::success();
            }
        }
        $userModel = UserModel::query()->where('account', $account)->first();
        if (empty($userModel)) {
            return self::error('账号有误！请核对后登录', 2);
        }
        //检验状态
        if ($userModel['is_disable']) {
            return self::error('该账号已禁止登录', 2);
        }
        //判断今日密码是否输入错误三次
        $passwordErrorKey = CacheKeyService::getLoginPasswordErrorTotal($account);
        $err_num = (int)RedisService::get($passwordErrorKey);
        if ($err_num > 2) {
            return self::error('今日已禁止登录', 2);
        }
        //检验登录密码
        if (UserService::passwordEncryption($password) !== $userModel['password_md5']) {
            //设置错误次数
            RedisService::set($passwordErrorKey, $err_num + 1, 86400);
            $num = 2 - $err_num;
            if ($num == 0) {
                $msg = '尝试3次验证密码失败，今日禁止登录';
            } else {
                $msg = '密码输入错误,今日还剩' . (2 - $err_num) . '次机会';
            }
            return self::error($msg, 2);
        }
        //存入session
        $request->session()->put("user_id", $userModel['id']);
        return self::success();
    }

    /**
     * 退出登录
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginOut(Request $request)
    {
        //删除session
        $request->session()->forget("user_id");
        return redirect('login');
    }

    /**
     * 框架页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function indexView()
    {
        //todo 获取所有可访问菜单
        $access_menus = RedisService::get(CacheKeyService::SYS_ACCESS_MENU);
        if (empty($access_menus)) {
            $access_menus = [];
        }
        $menus = MenuService::getLoginMenu();
        $user = UserService::getUserInfoById(session('user_id'));
        return $this->view('lzadmin/index/index', compact('menus', 'user', 'access_menus'));
    }

    /**
     * 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function homeView()
    {
        return $this->view('lzadmin/index/main');
    }

    /**
     * 修改密码页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function passwordView()
    {
        return $this->view('lzadmin/index/password');
    }

    /**
     * 修改密码
     * @param Request $request
     * @return array
     */
    public function updatePassword(Request $request)
    {
        $id = $request->session()->get('user_id');
        if (!$id) {
            return self::error('请在配置文件中设置开发账号密码');
        }
        $old_password = $request->input('old_password');
        $password = $request->input('password');
        $query_password = $request->input('query_password');
        if (empty($old_password) || empty($password) || empty($query_password)) {
            return self::error('密码不能为空');
        }
        if ($password != $query_password) {
            return self::error('确认密码输入不一样');
        }
        if ($verify_msg = customVerifyPassword($password)) {
            return self::error($verify_msg);
        }
        $id = $request->session()->get('user_id');
        $userModel = UserModel::find($id);
        if ($userModel->password_md5 != UserService::passwordEncryption($old_password)) {
            return self::error('原密码错误');
        }
        $userModel->password_md5 = UserService::passwordEncryption($password);
        $result = $userModel->update();
        return self::result($result);
    }

}
