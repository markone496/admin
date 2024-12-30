@extends('lzadmin.layouts.app')
@section('title', '编辑页')
@section('styles')
    <style>
        #config {
            background: white;
        }

        #config tbody td {
            padding: 1px;
            text-align: center;
        }
        #config tbody td:first-child{
            cursor: all-scroll;
        }
        #config thead th {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-md12" id="config">
                <div class="layui-form">
                    <div class="layui-form-item" style="margin-top: 10px">
                        <label class="layui-form-label">方法名</label>
                        <div class="layui-input-inline" style="width: 500px">
                            <input type="text" name="action" class="layui-input" value="{{$option->action ?? ''}}">
                        </div>
                        <a class="layui-btn" id="addCols">新增配置</a>
                    </div>
                    <div class="layui-form-item" style="margin-top: 10px">
                        <label class="layui-form-label">选项配置</label>
                        <div class="layui-input-block">
                            <table class="layui-table"
                                   style="width: {{array_sum(array_column($config,'width')) + 200}}px;">
                                <colgroup>
                                    <col width="60">
                                    @foreach($config as $data)
                                        <col width="{{$data['width']}}">
                                    @endforeach
                                    <col width="140">
                                </colgroup>
                                <thead>
                                <tr>
                                    <th></th>
                                    @foreach($config as $data)
                                        <th width="{{$data['width']}}">{{$data['title']}}</th>
                                    @endforeach
                                    <th>#</th>
                                </tr>
                                </thead>
                                <tbody id="optionTbody">
                                @foreach($option->option_config as $model)
                                    <tr>
                                        <td>
                                            <i class="layui-icon layui-icon-snowflake"></i>
                                        </td>
                                        @foreach($config as $data)
                                            <td>
                                                @if(empty($data['option']))
                                                    <input type="text" class="layui-input"
                                                           name="option_config[{{$data['field']}}][]"
                                                           value="{{$model[$data['field']]}}">
                                                @else
                                                    <select lay-search class="layui-select"
                                                            name="option_config[{{$data['field']}}][]">
                                                        <option value="">请选择</option>
                                                        @foreach($data['option'] as $option)
                                                            <option
                                                                @if($model[$data['field']] == $option['value']) selected
                                                                @endif value="{{$option['value']}}">{{$option['title']}}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            <a class="layui-btn layui-btn-xs layui-btn-warm configCopy">复制</a>
                                            <a class="layui-btn layui-btn-xs layui-btn-danger configDel">删除</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="layui-form-item layui-hide">
                        <input type="button" lay-submit lay-filter="submit" id="submit">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="optionTrHtml">
        <tr>
            <td>
                <i class="layui-icon layui-icon-snowflake"></i>
            </td>
            @foreach($config as $data)
                <td>
                    @if(empty($data['option']))
                        <input type="text" class="layui-input" name="option_config[{{$data['field']}}][]">
                    @else
                        <select lay-search class="layui-select" name="option_config[{{$data['field']}}][]">
                            <option value="">请选择</option>
                            @foreach($data['option'] as $option)
                                <option value="{{$option['value']}}">{{$option['title']}}</option>
                            @endforeach
                        </select>
                    @endif
                </td>
            @endforeach
            <td>
                <a class="layui-btn layui-btn-xs layui-btn-warm configCopy">复制</a>
                <a class="layui-btn layui-btn-xs layui-btn-danger configDel">删除</a>
            </td>
        </tr>
    </script>

@endsection
@section('scripts')
    <script>
        var call = null;
        layui.use(function () {
            var form = layui.form;
            $('.layui-tab-item').height($(window).height() - 225);
            $('#addCols').click(function () {
                var tbodyObj = $('#optionTbody');
                var temp = $('#optionTrHtml').html();
                tbodyObj.append(temp);
                form.render('select');
                tbodyObj.sortable({
                    items: "tr"
                });
            });
            //排序
            $("tbody").sortable({
                items: "tr"
            });
            //删除
            $('#config').on('click', '.configDel', function () {
                let self = this;
                layer.confirm('确定删除此行数据么？', function (index) {
                    $(self).closest('tr').remove();
                    layer.close(index);
                });
            });
            //复制
            $('#config').on('click', '.configCopy', function () {
                let trObj = $(this).closest('tr');
                trObj.after(trObj.clone());
                form.render('select');
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
