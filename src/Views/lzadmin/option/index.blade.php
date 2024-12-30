@extends('lzadmin.layouts.app')
@section('title', '选项')
<style>
    #config {
        background: white;
    }
</style>
@section('styles')
@endsection
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md3">
            <div class="layui-card">
                <div class="layui-card-header">
                    选项列表
                </div>
                <div class="layui-card-body">
                    <table class="layui-hide" id="table" lay-filter="table"></table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/html" id="toolbarDemo">
    <div class="layui-btn-container functionToolbarHeadBtn">
        <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
    </div>
</script>
<script type="text/html" id="toolbar">
    <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="delete">删除</a>
    <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="config">配置</a>
</script>


@section('content')
@endsection
@section('scripts')
    <script>
        layui.use(function () {
            var form = layui.form;
            var element_type = 'table';
            var element = layui.element;
            element.on('tab(config)', function (data) {
                element_type = $(this).attr('data-type');
            });
            var modelTable = com.table(
                {
                    title: '选项',
                    url: '/sys/option/list',
                    height: 'full-100',
                    cols: [[
                        {field: 'id', title: 'ID', width: 70, align: 'center'},
                        {field: 'title', title: '选项名称', edit: true},
                        {title: '操作', width: 120, align: 'center', toolbar: '#toolbar'},
                    ]],
                },
                {
                    toolbar: function (obj, func) {
                        if (obj.event === 'add') {
                            layer.prompt({title: '请输入选项名称', formType: 3}, function (value, index) {
                                com.post('/sys/option/create', {title: value}, function (res) {
                                    layer.close(index);
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2})
                                    } else {
                                        func.reload();
                                        layer.msg(res.msg, {icon: 1});
                                    }
                                });
                            });
                        }
                    },
                    tool: function (obj, self) {
                        let data = obj.data;
                        let id = data.id;
                        if (obj.event === 'delete') {
                            layer.confirm('确定删除【' + data.id + '】么？', function (index) {
                                com.post('/sys/option/delete', {id: id}, function (res) {
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2})
                                    } else {
                                        layer.msg(res.msg, {icon: 1});
                                        obj.del();
                                        layer.close(index);
                                    }
                                });
                            });
                        } else if (obj.event === 'config') {
                            com.openForm({
                                title: '【' + data.title + '】配置',
                                content: '/sys/option/config?id=' + id,
                                width: 1500,
                                callback: function (index, field) {
                                    field.id = id;
                                    com.post('/sys/option/updateConfig', field, function (res) {
                                        if (res.code) {
                                            layer.msg(res.msg, {icon: 2})
                                        } else {
                                            layer.msg(res.msg, {icon: 1});
                                        }
                                    });
                                }
                            });
                        }
                    },
                    edit: function (obj) {
                        let data = obj.data;
                        com.post('/sys/option/update', {
                            id: data.id,
                            field: obj.field,
                            value: obj.value
                        }, function (res) {
                            if (res.code) {
                                layer.msg(res.msg, {icon: 2});
                                obj.reload();
                            } else {
                                layer.msg(res.msg, {icon: 1});
                            }
                        });
                    }
                }
            );
        })
    </script>
@endsection
