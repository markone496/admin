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
    <div class="layui-form open-content" style="padding: 10px">
        <table class="layui-table">
            <colgroup>
                <col width="60">
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>#</th>
                <th>表名</th>
                <th>备注</th>
            </tr>
            <tbody>
            @foreach($data as $item)
                <tr>
                    <td><input type="checkbox" name="table[]" value="{{$item['table_name']}}"></td>
                    <td>{{$item['table_name']}}</td>
                    <td>{{$item['table_comment']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

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
