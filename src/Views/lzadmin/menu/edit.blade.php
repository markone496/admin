@extends('lzadmin.layouts.app')
@section('title', '菜单添加')
@section('styles')
    <style>
        html{
            background: #FFF;
        }
    </style>
@endsection

@section('content')
    <div class="layui-form open-content">
        <div class="layui-form-item layui-hide">
            <input name="parent_id" value="{{$id}}">
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required_option">*</span>标题</label>
            <div class="layui-input-block">
                <input type="text" name="title" lay-verify="required" placeholder="请输入菜单标题" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">路由</label>
            <div class="layui-input-block">
                <input type="text" name="route" placeholder="请输入路由" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图标</label>
            <div class="layui-input-inline" style="width: 280px">
                <input type="text" name="icon" placeholder="请输入图标类名或选择图标" autocomplete="off"
                       class="layui-input">
            </div>
            <button class="layui-btn" id="choseIcon">点击选择</button>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required_option">*</span>排序</label>
            <div class="layui-input-inline" style="width: 280px">
                <input type="number" name="sort" lay-verify="required" value="0"
                       autocomplete="off"
                       class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">升序排列</div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">隐藏</label>
            <div class="layui-input-block">
                <input type="checkbox" name="is_hide" lay-skin="switch" lay-text="是|否">
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

            $('#choseIcon').click(function () {
                parent.com.iconChose(function (icon) {
                    $("input[name=icon]").val(icon)
                })
            })
        });

        function submit(func) {
            call = func;
            $('#submit').click();
        }
    </script>
@endsection
