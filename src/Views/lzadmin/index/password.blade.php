@extends('lzadmin.layouts.app')
@section('title', '')
@section('styles')
@endsection

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md6">
                <div class="layui-card">
                    <div class="layui-card-header">修改密码</div>
                    <div class="layui-card-body">
                        <form class="layui-form">
                            <div class="layui-form-item">
                                <label class="layui-form-label"><span class="required_option">*</span>原密码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="old_password" lay-verify="required" placeholder="请输入原密码" value="" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label"><span class="required_option">*</span>新密码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="password" lay-verify="required" placeholder="请输入新密码" value="" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label"><span class="required_option">*</span>确认密码</label>
                                <div class="layui-input-block">
                                    <input type="text" name="query_password" lay-verify="required" placeholder="请再次输入新密码" value="" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <div class="layui-input-block">
                                    <button class="layui-btn" lay-submit lay-filter="submit">确认保存</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        layui.use(function () {
            var form = layui.form;
            // 表单提交
            form.on('submit(submit)', function (data) {
                let field = data.field;
                com.post("/password", field, function (res) {
                    if (res.code) {
                        layer.msg(res.msg, {icon: 2})
                    } else {
                        layer.msg(res.msg, {icon: 1}, function () {
                            parent.location.href = '/loginOut';
                        })
                    }
                });
                return false;
            });
        })
    </script>
@endsection
