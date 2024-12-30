@extends('lzadmin.layouts.app')
@section('title', 'demo编辑页')
@section('styles')
    <style>
        html {
            background: #FFF;
        }
    </style>
@endsection

@section('content')
    <div class="layui-form open-content">
        @foreach($editForm as $html)
            {!! $html !!}
        @endforeach
{{--        单行文本框--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" lay-verify="required" placeholder="请输入标题" value="" autocomplete="off" class="layui-input">
                    </div>
                </div>

{{--        多行文本框--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>路由</label>
                    <div class="layui-input-block">
                        <textarea name="route" placeholder="多个路由回车换行" lay-verify="required" autocomplete="off" class="layui-textarea"></textarea>
                    </div>
                </div>

{{--        下拉菜单--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>路由</label>
                    <div class="layui-input-block">
                        <select name="">
                            <option value="">全选</option>
                        </select>
                    </div>
                </div>

{{--        单选框--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>路由</label>
                    <div class="layui-input-block">
                        <input type="radio" name="AAA" value="1" title="默认">
                        <input type="radio" name="AAA" value="2" title="选中" checked>
                        <input type="radio" name="AAA" value="3" title="禁用" disabled>
                    </div>
                </div>

{{--        复选框--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>路由</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="AAA" title="默认">
                        <input type="checkbox" name="BBB" lay-text="选中" checked>
                        <input type="checkbox" name="CCC" title="禁用" disabled>
                        <input type="checkbox" name="DDD" title="半选" id="ID-checkbox-ind">
                    </div>
                </div>

{{--        单图上传--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>封面</label>
                    <div class="layui-input-block">
                        <div class="uploadImage">
                            <input type="hidden" name="cover">
                            <div class="layui-upload-drag upload">
                                <i class="layui-icon"></i>
                                <p>点击上传，或将文件拖拽到此处</p>
                            </div>
                            <div class="image" style="display: none">
                                <img src="">
                                <div class="btn">
                                    <a class="layui-btn layui-btn-sm upload">更换</a>
                                    <a class="layui-btn layui-btn-sm layui-btn-danger delete">删除</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

{{--        多图上传--}}
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="required_option">*</span>图集</label>
                    <div class="layui-input-block">
                        <a class="layui-btn uploadImageMultipleBtn" data-field="img[]">选择图片</a>
                        <div class="uploadImageMultiple" style="margin-top: 10px;">
                            <div class="uploadImage">
                                <input type="hidden" name="cover">
                                <div class="image">
                                    <img
                                        src="http://s30vxzp80.hn-bkt.clouddn.com/test/20231103/48c2441403acb2187ca1baba24361b46.png">
                                    <div class="btn">
                                        <a class="layui-btn layui-btn-sm upload">更换</a>
                                        <a class="layui-btn layui-btn-sm layui-btn-danger delete" data-status="true">删除</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        {{--富文本--}}
        <div class="layui-form-item">
            <label class="layui-form-label"><span class="required_option">*</span>详情</label>
            <div class="layui-input-block">
                <div class="editor—wrapper">
                    <input type="hidden" value="" name="info" lay-verify="required">
                    <div class="toolbar-container"></div>
                    <div class="editor-container"></div>
                </div>
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
