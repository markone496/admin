@extends('lzadmin.layouts.app')
@section('title', '权限添加')
@section('styles')
    <style>
        html{
            background: #FFF;
        }
    </style>
@endsection

@section('content')
    <div class="layui-form open-content">
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required_option">*</span>权限名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" placeholder="请输入权限名称" value="{{$model->title ?? ''}}" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required_option">*</span>路由</label>
            <div class="layui-input-block">
            <textarea name="route" placeholder="多个路由回车换行" lay-verify="required"  autocomplete="off"
                      class="layui-textarea">{{$model->route ?? ''}}</textarea>
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
        layui.use(['form'], function () {
            var form = layui.form;
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
