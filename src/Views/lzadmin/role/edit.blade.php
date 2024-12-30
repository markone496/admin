@extends('lzadmin.layouts.app')
@section('title', '编辑页')
@section('styles')
    <style>
        html {
            background: #FFF;
        }

        .menu-container {
            width: 100%;
            border: 1px solid #eee;
            margin-bottom: 10px;
        }

        .menu-header {
            padding: 5px;
            height: 20px;
            line-height: 20px;
            border-bottom: 1px solid #eee;
            background: #eee;
        }

        .menu-header i {
            float: right;
            cursor: pointer;
        }

        .menu-header > .layui-form-checkbox[lay-skin="primary"] {
            margin-top: 0;
        }

        .menu-content {
            padding: 10px 10px;
        }

        /*.function-container{*/
        /*    padding: 5px 0 0;*/
        /*    display: inline-block*/
        /*}*/
    </style>
@endsection

@section('content')
    <div class="layui-form open-content">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required_option">*</span>角色名称</label>
            <div class="layui-input-block">
                <input type="text" name="role_name" lay-verify="required" value="{{$model->role_name ?? ''}}" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限：</label>
            <div class="layui-input-block" id="auth-container">

            </div>
        </div>

        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="submit" id="submit">
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var call = null;
        layui.use(function () {
            var form = layui.form;
            var tree = layui.tree;
            // 模拟数据
            var data = @json($menus);
            // 渲染
            tree.render({
                elem: '#auth-container',
                data: data,
                showCheckbox: true,  // 是否显示复选框
                onlyIconControl: true,  // 是否仅允许节点左侧图标控制展开收缩
                id: 'sys_role',
                isJump: false
            });
            // 表单提交
            form.on('submit(submit)', function (data) {
                call && call(data.field);
                return false;
            });
        });

        function submit(func) {
            call = func;
            $('#submit').click();
        }
    </script>
@endsection
