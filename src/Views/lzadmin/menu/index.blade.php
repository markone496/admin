@extends('lzadmin.layouts.app')
@section('title', '菜单管理')
@section('styles')
    <style>

    </style>
@endsection

@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md7">
                <div class="layui-card">
                    <div class="layui-card-header">
                        菜单列表：<span class="layui-badge">模型列表：/model/{模型ID}、按月查询列表: /subTable/{模型ID}</span>
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="menuTable" lay-filter="menuTable"></table>
                    </div>
                </div>
            </div>
            <div class="layui-col-md5" id="functionItem" style="display: none">
                <div class="layui-card">
                    <div class="layui-card-header">
                        <span class="functionHeader">权限列表</span>：<span class="layui-badge">方法名前不要加 “ / ”</span>
                    </div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="functionTable" lay-filter="functionTable"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="iconTpl">
        <i class="@{{ d.icon }}"></i>
    </script>
    <script type="text/html" id="isHideTpl">
        <input type="checkbox" lay-skin="switch" lay-text="是|否" lay-filter="is_hide" value="@{{ d.id }}" @{{ d.is_hide
               ? 'checked':'' }}>
    </script>

    <script type="text/html" id="menuToolbar">
        <a class="layui-btn layui-btn-xs" lay-event="add">添加</a>
        <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="extend">更多</a>
        {{--        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>--}}
        {{--        <a class="layui-btn layui-btn-xs layui-btn-primary" lay-event="function">权限</a>--}}
    </script>

    <script type="text/html" id="menuToolbarDemo">
        <div class="layui-btn-container menuToolbarHeadBtn">
            <button class="layui-btn layui-btn-sm" lay-event="addMenu">添加菜单</button>
            <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reloadMenu">刷新菜单</button>
            <button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="open">展开或折叠</button>
        </div>
    </script>

    <script type="text/html" id="funtionToolbar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</a>
    </script>
    <script type="text/html" id="functionToolbarDemo">
        <div class="layui-btn-container functionToolbarHeadBtn">
            <button class="layui-btn layui-btn-sm" lay-event="addFunction">添加权限</button>
            <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="delFunction">删除权限</button>
            <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reloadFunction">刷新权限</button>
        </div>
    </script>
@endsection
@section('scripts')
    <script>
        layui.use(function () {
            var treeTable = layui.treeTable;
            var table = layui.table;
            var dropdown = layui.dropdown;
            var form = layui.form;
            var menuTableId = 'menuTable';
            var functionTableId = 'functionTable';
            var menu_id;
            var tree_open_status = true;
            // 表格渲染
            treeTable.render({
                elem: '#' + menuTableId, // 表格元素的选择器
                url: '/sys/menu/getList',
                toolbar: '#menuToolbarDemo',
                method: 'post',
                height: 'full-100',
                where: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                tree: {
                    customName: {
                        name: 'title',
                        icon: ''
                    },
                    view: {
                        showIcon: false,
                        expandAllDefault: true
                    }
                },
                cols: [[
                    {field: 'title', title: '标题', edit: 'text'},
                    {field: 'route', title: '路由', width: 200, edit: 'text'},
                    {field: 'icon', title: '图标', templet: '#iconTpl', width: 80, align: 'center', event: 'icon'},
                    {field: 'sort', title: '排序', width: 80, align: 'center', edit: 'text'},
                    {field: 'is_hide', title: '是否隐藏', templet: '#isHideTpl', width: 100, align: 'center'},
                    {field: 'func', title: '操作', width: 120, align: 'center', toolbar: '#menuToolbar'},
                ]],
            });
            /**** 编辑菜单 ****/
            treeTable.on('edit(' + menuTableId + ')', function (obj) {
                let data = obj.data;
                com.post('/sys/menu/update', {
                    id: data.id,
                    field: obj.field,
                    value: obj.value
                }, function (res) {
                    if (res.code) {
                        layer.msg(res.msg, {icon: 2});
                        treeTable.reload();
                    } else {
                        layer.msg(res.msg, {icon: 1});
                    }
                });
            });

            /**** 菜单隐藏 ****/
            form.on('switch(is_hide)', function (obj) {
                com.post('/sys/menu/update', {
                    id: obj.value,
                    field: 'is_hide',
                    value: obj.elem.checked ? 1 : 0
                }, function (res) {
                    if (res.code) {
                        layer.msg(res.msg, {icon: 2});
                        treeTable.reload();
                    } else {
                        layer.msg(res.msg, {icon: 1});
                    }
                });
            });

            /**** 菜单头部按钮 ****/
            treeTable.on('toolbar(' + menuTableId + ')', function (obj) {
                //添加菜单
                if (obj.event === 'addMenu') {
                    layer.open({
                        type: 2
                        , title: '添加菜单'
                        , content: '/sys/menu/addView'
                        , fixed: false
                        , maxmin: true
                        , area: ['550px', '400px']
                        , btn: ['确定', '取消']
                        , btnAlign: 'c'
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index];
                            iframeWindow.submit(function (data) {
                                com.post('/sys/menu/create', data, function (res) {
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2});
                                    } else {
                                        layer.close(index);
                                        treeTable.reload(menuTableId);
                                        layer.msg(res.msg, {icon: 1});
                                    }
                                });
                            });
                        }
                    });
                } else if (obj.event === 'reloadMenu') {//刷新菜单
                    treeTable.reload(menuTableId);
                } else if (obj.event === 'open') {
                    tree_open_status = !tree_open_status;
                    treeTable.expandAll(menuTableId, tree_open_status)
                }
            });

            /**** 菜单行点击 ****/
            treeTable.on('tool(' + menuTableId + ')', function (obj) {
                let data = obj.data;
                let id = data.id;
                let obj_index = data['LAY_DATA_INDEX'];
                if (obj.event === 'add') {
                    layer.open({
                        type: 2
                        , title: '添加菜单【' + data['title'] + '】'
                        , content: '/sys/menu/addView?id=' + id
                        , fixed: false
                        , maxmin: true
                        , area: ['550px', '400px']
                        , btn: ['确定', '取消']
                        , btnAlign: 'c'
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index];
                            iframeWindow.submit(function (data) {
                                com.post('/sys/menu/create', data, function (res) {
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2});
                                    } else {
                                        layer.close(index);
                                        treeTable.addNodes(menuTableId, {
                                            parentIndex: obj_index,
                                            index: -1,
                                            data: res.data
                                        });
                                        layer.msg(res.msg, {icon: 1});
                                    }
                                });
                            });
                        }
                    });
                } else if (obj.event === 'icon') {
                    com.iconChose(function (icon) {
                        com.post('/sys/menu/update', {
                            id: id,
                            field: 'icon',
                            value: icon
                        }, function (res) {
                            if (res.code) {
                                layer.msg(res.msg, {icon: 2});
                            } else {
                                obj.update(res.data);
                                layer.msg(res.msg, {icon: 1});
                            }
                        });
                    })
                } else if (obj.event === 'extend') {
                    dropdown.render({
                        elem: this, // 触发事件的 DOM 对象
                        show: true, // 外部事件触发即显示
                        align: "right", // 右对齐弹出
                        data: [
                            {
                                title: "权限",
                                id: "function"
                            },
                            {
                                title: "删除",
                                id: "del"
                            }
                        ],
                        click: function (menudata) {
                            if (menudata.id === "del") {
                                layer.confirm('确定删除【' + data.title + '】么？', function (index) {
                                    com.post('/sys/menu/delete', data, function (res) {
                                        if (res.code) {
                                            layer.msg(res.msg, {icon: 2})
                                        } else {
                                            layer.msg(res.msg, {icon: 1});
                                            obj.del();
                                            layer.close(index);
                                        }
                                    });
                                });
                            } else if (menudata.id === "function") {
                                $('#functionItem').show();
                                $('.functionHeader').text('【' + data.title + '】权限列表');
                                menu_id = id;
                                table.render({
                                    elem: '#' + functionTableId, // 表格元素的选择器
                                    url: '/sys/function/getList?menu_id=' + menu_id,
                                    toolbar: '#functionToolbarDemo',
                                    where: {
                                        _token: $('meta[name="csrf-token"]').attr('content')
                                    },
                                    method: 'post',
                                    height: 'full-100',
                                    cols: [[
                                        {type: 'checkbox'},
                                        {field: 'id', title: 'ID', width: 80, align: 'center'},
                                        {field: 'title', title: '标题', width: 120},
                                        {field: 'route', title: '路由'},
                                        {title: '操作', width: 120, align: 'center', toolbar: '#funtionToolbar'},
                                    ]],
                                    page: true,
                                    limit: 20,
                                    limits: [20, 50, 100, 500],
                                });
                            }
                        }
                    });
                }
            });

            /**** 权限行点击 ****/
            table.on('tool(' + functionTableId + ')', function (obj) {
                let data = obj.data;
                let id = data.id;
                if (obj.event === 'edit') {
                    layer.open({
                        type: 2
                        , title: '编辑权限'
                        , content: '/sys/function/edit?id=' + id
                        , fixed: false
                        , maxmin: true
                        , area: com.getLayerOpenArea(550, 300)
                        , btn: ['确定', '取消']
                        , btnAlign: 'c'
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index];
                            iframeWindow.submit(function (data) {
                                data.id = id;
                                com.post('/sys/function/update', data, function (res) {
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2});
                                    } else {
                                        layer.close(index);
                                        obj.update(res.data);
                                        layer.msg(res.msg, {icon: 1});
                                    }
                                });
                            });
                        }
                    });
                } else if (obj.event === 'del') {
                    layer.confirm('确定删除所选数据么？', function (index) {
                        com.post('/sys/function/delete', {menu_id: menu_id, ids: [id]}, function (res) {
                            if (res.code) {
                                layer.msg(res.msg, {icon: 2})
                            } else {
                                layer.msg(res.msg, {icon: 1});
                                obj.del();
                                layer.close(index);
                            }
                        });
                    });
                }
            });

            /**** 权限顶部按钮 ****/
            table.on('toolbar(' + functionTableId + ')', function (obj) {
                //添加权限
                if (obj.event === 'addFunction') {
                    layer.open({
                        type: 2
                        , title: '添加权限'
                        , content: '/sys/function/edit'
                        , fixed: false
                        , maxmin: true
                        , area: com.getLayerOpenArea(550, 300)
                        , btn: ['确定', '取消']
                        , btnAlign: 'c'
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index];
                            iframeWindow.submit(function (data) {
                                data.menu_id = menu_id;
                                com.post('/sys/function/create', data, function (res) {
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2});
                                    } else {
                                        layer.close(index);
                                        table.reload(functionTableId);
                                        layer.msg(res.msg, {icon: 1});
                                    }
                                });
                            });
                        }
                    });
                } else if (obj.event === 'delFunction') {
                    let checkStatus = table.checkStatus(functionTableId);
                    let data = checkStatus.data;
                    if (data.length === 0) {
                        layer.msg('请选择要删除的数据', {icon: 2});
                        return false;
                    }
                    let ids = [];
                    $(data).each(function (index, item) {
                        ids[index] = item.id;
                    });
                    layer.confirm('确定删除所选权限么？', function (index) {
                        com.post('/sys/function/delete', {menu_id: menu_id, ids: ids}, function (res) {
                            if (res.code) {
                                layer.msg(res.msg, {icon: 2})
                            } else {
                                layer.msg(res.msg, {icon: 1});
                                table.reload(functionTableId);
                                layer.close(index);
                            }
                        });
                    });
                } else if (obj.event === 'reloadFunction') {
                    table.reload(functionTableId);
                }
            });
        });
    </script>
@endsection
