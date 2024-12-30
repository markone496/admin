@extends('lzadmin.layouts.app')
@section('title', '编辑页')
@section('styles')
    <style>
        html {
            background: #FFF;
        }
    </style>
@endsection

@section('content')
    <form class="layui-form open-content">
        @foreach($editForm as $html)
            {!! $html !!}
        @endforeach
        <div class="layui-form-item layui-hide">
            <input type="button" lay-submit lay-filter="submit" id="submit">
        </div>
    </form>
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
