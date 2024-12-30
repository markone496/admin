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
        <div class="layui-tab layui-tab-brief">
            <ul class="layui-tab-title">
                @foreach($data as $index=>$item)
                    <li @if(!$index) class="layui-this" @endif>{{$item['table_name']}}</li>
                @endforeach
            </ul>
            <div class="layui-tab-content">
                @foreach($data as $index=>$item)
                    <div class="layui-tab-item  @if(!$index) layui-show @endif">
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
                            @foreach($item['fields'] as $field)
                                <?php $field['table_name'] = $item['table_name'];?>
                                <tr>
                                    <td><input type="checkbox" name="table[]" value="{{json_encode($field)}}"></td>
                                    <td>{{$field['column_name']}}</td>
                                    <td>{{$field['column_comment']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
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
