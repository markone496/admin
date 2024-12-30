<?php

use Illuminate\Support\Facades\Route;

use lz\admin\Controllers as C;

//获取后台配置
$config = config('admin');

//定义路由
Route::domain($config['domain'])->group(function () {

    //请求日志中间件
    Route::middleware(['web', 'option.log'])->group(function () {

        /**** 登录 ****/
        Route::get('/login', [C\IndexController::class, 'loginView'])->name('login');//登陆页
        Route::post('/login', [C\IndexController::class, 'checkLogin']);//检查登录
        Route::get('/loginOut', [C\IndexController::class, 'loginOut'])->name('loginOut');//退出登录

        Route::middleware(['admin.login'])->group(function () {
            Route::get('/', [C\IndexController::class, 'indexView']);//框架页
            Route::get('/homePage', [C\IndexController::class, 'homeView']);//首页
            Route::get('/passwordView', [C\IndexController::class, 'passwordView']);//修改密码页
            Route::post('/password', [C\IndexController::class, 'updatePassword']);//修改密码

            /**** 公共 ****/
            Route::get('/sys/icon', [C\SysController::class, 'iconView']);//图标选择页
            Route::post('/sys/upload/config', [C\SysController::class, 'getOssConfig']);//获取上传token

            Route::prefix('sys')->middleware(['admin.superuser'])->group(function () {
                /**** 刷新缓存 ****/
                Route::get('/refreshCache', [C\SysController::class, 'refreshCache']);//刷新缓存
                /**** 菜单 ****/
                Route::get('/menu', [C\SysMenuController::class, 'indexView']);//菜单管理页
                Route::post('/menu/getList', [C\SysMenuController::class, 'getList']);//获取树形菜单
                Route::get('/menu/addView', [C\SysMenuController::class, 'addView']);//菜单编辑页面
                Route::post('/menu/create', [C\SysMenuController::class, 'create']);//新增菜单
                Route::post('/menu/update', [C\SysMenuController::class, 'update']);//修改菜单
                Route::post('/menu/delete', [C\SysMenuController::class, 'delete']);//删除菜单
                /**** 权限 ****/
                Route::post('/function/getList', [C\SysFunctionController::class, 'getList']);//菜单权限
                Route::get('/function/edit', [C\SysFunctionController::class, 'editView']);//权限编辑页面
                Route::post('/function/create', [C\SysFunctionController::class, 'create']);//新增权限
                Route::post('/function/update', [C\SysFunctionController::class, 'update']);//修改权限
                Route::post('/function/delete', [C\SysFunctionController::class, 'delete']);//新增权限
                /**** 模型 ****/
                Route::get('/model', [C\SysModelController::class, 'indexView']);//模型页
                Route::post('/model/list', [C\SysModelController::class, 'getList']);//数据
                Route::post('/model/create', [C\SysModelController::class, 'create']);//新增
                Route::post('/model/update', [C\SysModelController::class, 'update']);//修改
                Route::post('/model/delete', [C\SysModelController::class, 'delete']);//demo删除
                Route::get('/model/config', [C\SysModelController::class, 'config']);//获取配置
                Route::post('/model/updateConfig', [C\SysModelController::class, 'updateConfig']);//修改配置
                /**** 选项 ****/
                Route::get('/option', [C\SysOptionController::class, 'indexView']);//模型页
                Route::post('/option/list', [C\SysOptionController::class, 'getList']);//数据
                Route::post('/option/create', [C\SysOptionController::class, 'create']);//新增
                Route::post('/option/update', [C\SysOptionController::class, 'update']);//修改
                Route::post('/option/delete', [C\SysOptionController::class, 'delete']);//demo删除
                Route::get('/option/config', [C\SysOptionController::class, 'config']);//获取配置
                Route::post('/option/updateConfig', [C\SysOptionController::class, 'updateConfig']);//修改配置

                /**** 系统配置 ****/
                Route::get('/config', [C\SysConfigController::class, 'indexView']);//页面
                Route::post('/config/list', [C\SysConfigController::class, 'getList']);//列表数据
                Route::get('/config/edit', [C\SysConfigController::class, 'editView']);//编辑页面
                Route::post('/config/create', [C\SysConfigController::class, 'create']);//新增
                Route::post('/config/update', [C\SysConfigController::class, 'update']);//编辑
                Route::post('/config/delete', [C\SysConfigController::class, 'delete']);//删除

                /**** 请求日志 ****/
                Route::get('/log', [C\SysLogController::class, 'indexView']);//模型页
                Route::post('/log/list', [C\SysLogController::class, 'getList']);//数据
                /**** 数据库 ****/
                Route::get('/table', [C\SysTableController::class, 'indexView']);//所有表
                Route::get('/table/info', [C\SysTableController::class, 'infoView']);//表字段

            });

            Route::prefix('sys')->middleware(['admin.auth'])->group(function () {
                /**** 主页 ****/
                Route::get('/main', [C\IndexController::class, 'mainView']);
                /**** 管理员 ****/
                Route::get('/user', [C\SysUserController::class, 'indexView']);//管理员页面
                Route::post('/user/list', [C\SysUserController::class, 'getList']);//获取管理员列表数据
                Route::get('/user/edit', [C\SysUserController::class, 'editView']);//管理员编辑页面
                Route::post('/user/create', [C\SysUserController::class, 'create']);//新增管理员
                Route::post('/user/update', [C\SysUserController::class, 'update']);//编辑管理员
                Route::post('/user/delete', [C\SysUserController::class, 'delete']);//删除管理员
                Route::post('/user/password', [C\SysUserController::class, 'password']);//重置密码
                /**** 角色 ****/
                Route::get('/role', [C\SysRoleController::class, 'indexView']);//角色页面
                Route::post('/role/list', [C\SysRoleController::class, 'getList']);//获取角色列表数据
                Route::get('/role/edit', [C\SysRoleController::class, 'editView']);//角色编辑页面
                Route::post('/role/create', [C\SysRoleController::class, 'create']);//新增角色
                Route::post('/role/update', [C\SysRoleController::class, 'update']);//编辑角色
                Route::post('/role/delete', [C\SysRoleController::class, 'delete']);//删除角色
                Route::post('/role/copy', [C\SysRoleController::class, 'copy']);//复制角色
                /**** 系统配置 ****/
                Route::get('/configData', [C\SysConfigDataController::class, 'indexView']);//页面
                Route::post('/configData/list', [C\SysConfigDataController::class, 'getList']);//列表数据
                Route::get('/configData/info', [C\SysConfigDataController::class, 'editView']);//配置详情
                Route::get('/configData/edit', [C\SysConfigDataController::class, 'editView']);//配置页
                Route::post('/configData/update', [C\SysConfigDataController::class, 'update']);//保存配置
            });

            Route::middleware(['admin.auth'])->group(function () {
                /**** 模型 ****/
                Route::get('/model/{id}', [C\ModelController::class, 'indexView'])->where('id', '[0-9]+');
                Route::post('/model/{id}/list', [C\ModelController::class, 'getList'])->where('id', '[0-9]+');
                Route::get('/model/{id}/info', [C\ModelController::class, 'infoView']);
                Route::get('/model/{id}/edit', [C\ModelController::class, 'editView']);
                Route::post('/model/{id}/create', [C\ModelController::class, 'create']);
                Route::post('/model/{id}/update', [C\ModelController::class, 'update']);
                Route::post('/model/{id}/delete', [C\ModelController::class, 'delete']);

                /**** 分表 ****/
                Route::get('/subTable/{id}', [C\SubTableController::class, 'indexView'])->where('id', '[0-9]+');
                Route::post('/subTable/{id}/list', [C\SubTableController::class, 'getList'])->where('id', '[0-9]+');
            });

        });

    });

});

