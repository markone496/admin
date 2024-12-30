@extends('lzadmin.layouts.app')
@section('title', 'demo页')
@section('styles')
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    {{--                    常用搜索--}}
                    <div class="layui-card-header" id="table-search-box-const">
                        <form class="layui-form" lay-filter="search-form">
                            <div class="search-item">
                                @foreach($search['const'] as $html)
                                    {!! $html !!}
                                @endforeach
                            </div>
                            <div class="search-btn">
                                <button class="layui-btn layui-btn-normal" lay-submit lay-filter="tableSearch">搜索
                                </button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                <a class="layui-btn layui-btn-primary" id="other-search-btn">更多</a>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-body" style="background: #FFF">
                        {{--                        数据表格--}}
                        <table class="layui-hide" id="table" lay-filter="table"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    顶部按钮--}}
    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container functionToolbarHeadBtn">
            @foreach($toolbar as $item)
                <button class="layui-btn layui-btn-sm {{$item['color']}}"
                        lay-event="{{$item['event']}}">{{$item['title']}}</button>
            @endforeach
            <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="reload">刷新</button>
        </div>
    </script>
    {{--    行按钮--}}
    <script type="text/html" id="toolbar">
        @foreach($tool as $item)
        <a class="layui-btn layui-btn-xs {{$item['color']}}" lay-event="{{$item['event']}}"
           data-data="{{json_encode($item['data'], true)}}">{{$item['title']}}</a>
        @endforeach
    </script>
    {{--    更多搜索--}}
    <script type="text/html" id="other-search-form">
        <form class="layui-form" lay-filter="other-search-form">
            @foreach($search['other'] as $html)
                {!! $html !!}
            @endforeach
        </form>
    </script>
@endsection
@yield('script')
@section('scripts')
    <script>
        layui.use(function () {
            var dropdown = layui.dropdown;
            var primary_key = "{{$primary_key}}";
            var table = com.table(
                {
                    title: '{{$title}}',
                    url: '{{$route}}list',
                    cols: [@json($cols)],
                },
                {
                    toolbar: function (obj, func) {
                        if (obj.event === 'create') {
                            com.openForm({
                                content: '{{$route}}edit',
                                width: 700,
                                callback: function (index, field) {
                                    com.post('{{$route}}create', field, function (res) {
                                        if (res.code) {
                                            layer.msg(res.msg, {icon: 2});
                                        } else {
                                            // layer.close(index);
                                            func.reload();
                                            layer.msg(res.msg, {icon: 1});
                                        }
                                    });
                                }
                            });
                        } else if (obj.event === 'delete') {
                            let data = func.getCheckData();
                            if (data.length === 0) {
                                layer.msg('请先选择数据', {icon: 2});
                                return false;
                            }
                            let ids = [];
                            $(data).each(function (index, item) {
                                ids[index] = item[primary_key];
                            });
                            layer.confirm('确定删除所选数据么么？', function (index) {
                                com.post('{{$route}}delete', {primary_key: ids}, function (res) {
                                    if (res.code) {
                                        layer.msg(res.msg, {icon: 2})
                                    } else {
                                        layer.msg(res.msg, {icon: 1});
                                        func.reload();
                                        layer.close(index);
                                    }
                                });
                            });
                        } else {
                            if (typeof window[obj.event] === 'function') {
                                window[obj.event](obj, func);
                            } else {
                                layer.msg(obj.event + '函数未定义', {icon: 2});
                            }
                        }
                    },
                    tool: function (obj, self) {
                        let data = obj.data;
                        let id = data[primary_key];
                        var event_data = JSON.parse($(self).attr('data-data'));

                        function eventCall(event) {
                            if (event === 'update') {
                                com.openForm({
                                    title: '表单编辑【' + id + '】',
                                    width: 700,
                                    content: '{{$route}}edit?primary_key=' + id,
                                    callback: function (index, field) {
                                        field.primary_key = id;
                                        com.post('{{$route}}update', field, function (res) {
                                            if (res.code) {
                                                layer.msg(res.msg, {icon: 2});
                                            } else {
                                                layer.close(index);
                                                obj.update(res.data);
                                                layer.msg(res.msg, {icon: 1});
                                            }
                                        });
                                    }
                                });
                            } else if (event === 'delete') {
                                layer.confirm('确定删除【' + id + '】么？', function (index) {
                                    com.post('{{$route}}delete', {primary_key: [id]}, function (res) {
                                        if (res.code) {
                                            layer.msg(res.msg, {icon: 2})
                                        } else {
                                            layer.msg(res.msg, {icon: 1});
                                            obj.del();
                                            layer.close(index);
                                        }
                                    });
                                });
                            } else if (event === 'info') {
                                com.openForm({
                                    title: '详情【' + id + '】',
                                    width: 700,
                                    content: '{{$route}}info?primary_key=' + id,
                                    btn: ['关闭'],
                                    shadeClose: true,
                                    yes: function (index, layero) {
                                        layer.close(index);
                                    }
                                });
                            } else {
                                if (typeof window[event] === 'function') {
                                    window[event](obj, table);
                                } else {
                                    layer.msg(event + '函数未定义', {icon: 2});
                                }
                            }
                        }

                        if (event_data.length > 0) {
                            dropdown.render({
                                elem: self, // 触发事件的 DOM 对象
                                show: true, // 外部事件触发即显示
                                data: event_data,
                                click: function (menudata) {
                                    eventCall(menudata.event);
                                }
                            });
                        } else {
                            eventCall(obj.event);
                        }
                    }
                }
            );
        })
    </script>
@endsection

