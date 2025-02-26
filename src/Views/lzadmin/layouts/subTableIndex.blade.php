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
                                <div class="layui-form-item">
                                    <label class="layui-form-label">月份：</label>
                                    <div class="layui-input-inline ">
                                        <input type="text" name="month" value="{{$month}}" class="layui-input customer-layDate-obj" autocomplete="off" data-type="month">
                                    </div>
                                </div>
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
            var model_id = "{{$model_id}}";
            var primary_key = "{{$primary_key}}";
            var cols = @json($cols);
            let colsData = [];
            var tableCols = localStorage.getItem('tableCols-'+ model_id);
            if (tableCols) {
                tableCols = JSON.parse(tableCols);
            } else {
                tableCols = {};
            }
            $(cols).each(function (index, item) {
                if (tableCols[item.field]) {
                    item.hide = true
                }
                colsData[index] = item;
            });
            var table = com.table(
                {
                    title: '{{$title}}',
                    url: '{{$route}}list',
                    cols: [colsData],
                },
                {
                    toolbar: function (obj, func) {
                        if (obj.event === 'LAYTABLE_COLS') {
                            $('.layui-table-tool-panel').on('click', 'li', function () {
                                let data = {};
                                $(obj.config.cols[0]).each(function (index, item) {
                                    if (item.hide) {
                                        data[item.field] = item.hide;
                                    }
                                });
                                localStorage.setItem('tableCols-' + model_id, JSON.stringify(data));
                            });
                        } else {
                            if (typeof window[obj.event] === 'function') {
                                window[obj.event](obj, func);
                            } else {
                                console.log(obj.event + '函数未定义');
                            }
                        }
                    },
                    tool: function (obj, self) {
                        let data = obj.data;
                        let id = data[primary_key];
                        var event_data = JSON.parse($(self).attr('data-data'));

                        function eventCall(event) {
                            if (typeof window[event] === 'function') {
                                window[event](obj, table);
                            } else {
                                layer.msg(event + '函数未定义', {icon: 2});
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

