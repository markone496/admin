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

        #config tbody td:first-child {
            cursor: all-scroll;
        }

        #config thead th {
            text-align: center;
        }

        .btnConfig .layui-input-inline {
            width: 90px
        }

        .btnConfig .layui-form-radio {
            line-height: 20px
        }
    </style>
@endsection

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-md12" id="config">
                <div class="layui-form">
                    <div class="layui-tab layui-tab-brief" lay-filter="config">
                        <ul class="layui-tab-title">
                            @foreach($config as $key=>$item)
                                <li data-type="{{$key}}"
                                    @if($key == 'table_config') class="layui-this" @endif>{{$item['title']}}</li>
                            @endforeach
                        </ul>
                        <div class="layui-tab-content layui-form">
                            @foreach($config as $key=>$item)
                                <div class="layui-tab-item  @if($key == 'table_config') layui-show @endif"
                                     style="overflow: auto;">
                                    <div class="layui-col-md12 btnConfig">
                                        <div class="layui-form-item">
                                            <div class="layui-input-inline">
                                                <a class="layui-btn layui-btn-sm addCols">新增一行</a>
                                            </div>
                                            @if($key == 'cols_config')
                                                <div class="layui-input-inline">
                                                    <a class="layui-btn layui-btn-sm layui-btn-primary addField">挑选字段</a>
                                                </div>
                                                <div class="layui-input-inline" style="width: 350px">
                                                    <input type="radio" name="choose_type" value="" title="关闭选择框"
                                                           @if(empty($mod->choose_type)) checked @endif>
                                                    <input type="radio" name="choose_type" value="checkbox" title="开启复选"
                                                           @if($mod->choose_type == 'checkbox') checked @endif>
                                                    <input type="radio" name="choose_type" value="radio" title="开启单选"
                                                           @if($mod->choose_type == 'radio')  checked @endif>
                                                </div>
                                            @elseif($key == 'table_config')
                                                <div class="layui-input-inline">
                                                    <a class="layui-btn layui-btn-sm layui-btn-primary addTable">挑选表</a>
                                                </div>
                                                <div class="layui-text-em" style="padding: 4px 0;">默认第一栏为主表配置</div>
                                            @elseif($key == 'search_config')
                                                <div class="layui-input-inline">
                                                    <a class="layui-btn layui-btn-sm layui-btn-primary addField">挑选字段</a>
                                                </div>
                                                <div class="layui-text-em" style="padding: 4px 0;">
                                                    由于常用搜索栏显示有限，建议最多设置4个
                                                </div>
                                            @elseif($key == 'toolbar_config')
                                                <div class="layui-text-em" style="padding: 4px 0;">
                                                    内置3个方法：新增【create】、批量删除【delete】、导出【export】
                                                </div>
                                            @elseif($key == 'tool_config')
                                                <div class="layui-text-em" style="padding: 4px 0;">
                                                    内置3个方法：详情【info】、编辑【update】、批量删除【delete】
                                                </div>
                                            @elseif($key == 'form_config' || $key == 'info_config' )
                                                <div class="layui-input-inline">
                                                    <a class="layui-btn layui-btn-sm layui-btn-primary addField">挑选字段</a>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                    <table class="layui-table"
                                           style="width: {{array_sum(array_column($item['data'],'width')) + 200}}px;">
                                        <colgroup>
                                            <col width="60">
                                            @foreach($item['data'] as $data)
                                                <col width="{{$data['width']}}">
                                            @endforeach
                                            <col width="140">
                                        </colgroup>
                                        <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($item['data'] as $data)
                                                <th width="{{$data['width']}}">{{$data['title']}}</th>
                                            @endforeach
                                            <th>#</th>
                                        </tr>
                                        </thead>
                                        <tbody id="{{$key}}Tbody">
                                        @foreach($models[$key] as $model)
                                            <tr>
                                                <td>
                                                    <i class="layui-icon layui-icon-snowflake"></i>
                                                </td>
                                                @foreach($item['data'] as $data)
                                                    <td>
                                                        @if(empty($data['option']))
                                                            <input type="text" class="layui-input"
                                                                   name="{{$key}}[{{$data['field']}}][]"
                                                                   value="{{$model[$data['field']]}}">
                                                        @else
                                                            <select lay-search class="layui-select"
                                                                    name="{{$key}}[{{$data['field']}}][]">
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
                            @endforeach
                        </div>
                    </div>
                    <div class="layui-form-item layui-hide">
                        <input type="button" lay-submit lay-filter="submit" id="submit">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach($config as $key=>$item)
        <script type="text/html" id="{{$key}}TrHtml">
            <tr>
                <td>
                    <i class="layui-icon layui-icon-snowflake"></i>
                </td>
                @foreach($item['data'] as $data)
                    <td>
                        @if(empty($data['option']))
                            <input type="text" class="layui-input" name="{{$key}}[{{$data['field']}}][]" value="{{$data['value'] ?? ''}}">
                        @else
                            <select lay-search class="layui-select" name="{{$key}}[{{$data['field']}}][]">
                                <option value="">请选择</option>
                                @foreach($data['option'] as $option)
                                    @if(isset($data['value']) && $data['value'] === $option['value'])
                                        <option value="{{$option['value']}}" selected>{{$option['title']}}</option>
                                    @else
                                        <option value="{{$option['value']}}">{{$option['title']}}</option>
                                    @endif
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
    @endforeach

@endsection
@section('scripts')
    <script>
        var call = null;
        layui.use(function () {
            var form = layui.form;
            var element_type = 'table_config';
            var element = layui.element;
            element.on('tab(config)', function (data) {
                element_type = $(this).attr('data-type');
            });
            $('.layui-tab-item').height($(window).height() - 125);
            $('.addCols').click(function () {
                var tbodyObj = $('#' + element_type + 'Tbody');
                var temp = $('#' + element_type + 'TrHtml').html();
                tbodyObj.append(temp);
                form.render('select');
                tbodyObj.sortable({
                    items: "tr"
                });
            });
            $('.addTable').click(function () {
                com.openForm({
                    title: '挑选表',
                    width: 400,
                    content: '/sys/table',
                    callback: function (index, field) {
                        var tbodyObj = $('#' + element_type + 'Tbody');
                        var temp = $('#' + element_type + 'TrHtml').html();
                        $.each(field, function (key, table_name) {
                            var clonedTemp = $(temp).clone();
                            $(clonedTemp).find("input").eq(0).val(table_name);
                            tbodyObj.append(clonedTemp);
                        });
                        form.render('select');
                        tbodyObj.sortable({
                            items: "tr"
                        });
                        layer.close(index);
                    }
                });
            });
            $('.addField').click(function () {
                //获取已选择的表
                var table_data = [];
                $("input[name='table_config[table][]']").each(function () {
                    let val = $(this).val().trim();
                    if (!!val) {
                        table_data.push(val);
                    }
                });
                if (table_data.length === 0) {
                    layer.msg('请先设置表', {icon: 2});
                    return false;
                }
                com.openForm({
                    title: '挑选字段',
                    width: 600,
                    content: '/sys/table/info?table=' + encodeURIComponent(JSON.stringify(table_data)),
                    callback: function (index, fields) {
                        var tbodyObj = $('#' + element_type + 'Tbody');
                        var temp = $('#' + element_type + 'TrHtml').html();
                        $.each(fields, function (key, value) {
                            let field = $.parseJSON(value);
                            var clonedTemp = $(temp).clone();
                            if (element_type === 'cols_config') {
                                $(clonedTemp).find("input").eq(0).val(field.table_name);
                                $(clonedTemp).find("input").eq(1).val(field.column_name);
                                $(clonedTemp).find("input").eq(3).val(field.column_comment);
                            } else if (element_type === 'search_config') {
                                $(clonedTemp).find("input").eq(0).val(field.table_name);
                                $(clonedTemp).find("input").eq(1).val(field.column_name);
                                $(clonedTemp).find("input").eq(2).val(field.column_comment);
                            } else if (element_type === 'form_config') {
                                $(clonedTemp).find("input").eq(0).val(field.table_name);
                                $(clonedTemp).find("input").eq(1).val(field.column_name);
                                $(clonedTemp).find("input").eq(2).val(field.column_comment);
                            } else if (element_type === 'info_config') {
                                $(clonedTemp).find("input").eq(0).val(field.table_name);
                                $(clonedTemp).find("input").eq(1).val(field.column_name);
                                $(clonedTemp).find("input").eq(2).val(field.column_comment);
                            }
                            tbodyObj.append(clonedTemp);
                        });
                        form.render('select');
                        tbodyObj.sortable({
                            items: "tr"
                        });
                        layer.close(index);
                    }
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
