@extends('lzadmin.layouts.app')
@section('title', '首页')
@section('styles')
    <style>
        .store-total-item {
            background: #FFFFFF;
            display: flex;
            padding: 30px;
            align-items: center;
            box-shadow: 0 1px 8px rgb(0 0 0 / 8%);
        }
        .store-total-item .icon-box {
            padding: 10px;
            display: flex;
            background: #e7f8f8;
            border-radius: 50%;
        }
        .store-total-item .data-box {
            margin-left: 20px;
        }
        .store-total-item .data-box .data {
            font-size: 24px;
            padding-top: 5px;
        }
        .store-total-item .data-box .name {
            font-size: 14px;
            color: #999999;
        }
    </style>
@endsection
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-sm6 layui-col-md3">
                <div data-href="/admin/Index/index.html?p=/admin/index/home.html?bcid=14"
                     class="store-total-item item-link">
                    <div class="icon-box">
                        <i class="layui-icon layui-icon-chart"></i>
                    </div>
                    <div class="data-box">
                        <div class="data">2179</div>
                        <div class="name">今日访问</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="store-total-item item-link">
                    <div class="icon-box">
                        <i class="layui-icon layui-icon-chart-screen"></i>
                    </div>
                    <div class="data-box">
                        <div class="data">613496</div>
                        <div class="name">总访问</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div data-href="/admin/Index/index.html?p=/admin/article/index.html?bcid=23_24"
                     class="store-total-item item-link">
                    <div class="icon-box">
                        <i class="layui-icon layui-icon-read"></i>
                    </div>
                    <div class="data-box">
                        <div class="data">16</div>
                        <div class="name">文章数量</div>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="store-total-item item-link">
                    <div class="icon-box">
                        <i class="layui-icon layui-icon-notice"></i>
                    </div>
                    <div class="data-box">
                        <div class="data">0</div>
                        <div class="name">待处理</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row layui-col-space15">
            <div class="layui-col-md9">
                <div class="layui-card">
                    <div class="layui-card-header">
                        数据概览
                        <div class="layui-btn-group" style="float: right">
                            <button type="button" class="tjday layui-btn layui-btn-sm" data-day="7">
                                最近7天
                            </button>
                            <button type="button" class="tjday layui-btn layui-btn-primary layui-btn-sm" data-day="15">
                                最近15天
                            </button>
                            <button type="button" class="tjday layui-btn layui-btn-primary layui-btn-sm" data-day="30">
                                最近30天
                            </button>
                            <button type="button" class="tjday layui-btn layui-btn-primary layui-btn-sm" data-day="60">
                                最近60天
                            </button>
                        </div>
                    </div>
                    <div class="layui-card-body layui-text">
                        <div id="my_chart"
                             style="width: 100%; height: 590px; -webkit-tap-highlight-color: transparent; user-select: none; position: relative; background: transparent;"
                             _echarts_instance_="ec_1734941013139">
                            <div
                                style="position: relative; overflow: hidden; width: 1219px; height: 590px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;">
                                <canvas data-zr-dom-id="zr_0" width="1219" height="590"
                                        style="position: absolute; left: 0px; top: 0px; width: 1219px; height: 590px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas>
                            </div>
                            <div
                                style="position: absolute; display: none; border-style: solid; white-space: nowrap; z-index: 9999999; transition: left 0.4s cubic-bezier(0.23, 1, 0.32, 1), top 0.4s cubic-bezier(0.23, 1, 0.32, 1); background-color: rgba(50, 50, 50, 0.7); border-width: 0px; border-color: rgb(51, 51, 51); border-radius: 4px; color: rgb(255, 255, 255); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 5px; left: 695px; top: 154px;">
                                12/20<br><span
                                    style="display:inline-block;margin-right:5px;border-radius:10px;width:10px;height:10px;background-color:#c23531;"></span>来访量:
                                17,879
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-col-md3">
                <div class="layui-card" style="height: 654px">
                    <div class="layui-card-header">服务器信息</div>
                    <div class="layui-card-body layui-text">
                        <table class="layui-table">
                            <tbody>
                            <tr>
                                <td width="100">操作系统：</td>
                                <td>Linux</td>
                            </tr>
                            <tr>
                                <td width="100">IP端口：</td>
                                <td>172.16.120.102:80</td>
                            </tr>
                            <tr>
                                <td width="100">PHP运行方式：</td>
                                <td>fpm-fcgi</td>
                            </tr>
                            <tr>
                                <td width="100">当前PHP版本：</td>
                                <td>8.0.26</td>
                            </tr>
                            <tr>
                                <td width="100">最低PHP版本：</td>
                                <td>PHP &gt;= 8.0.0</td>
                            </tr>
                            <tr>
                                <td width="100">上传附件限制：</td>
                                <td>50M</td>
                            </tr>
                            <tr>
                                <td width="100">执行时间限制：</td>
                                <td>300秒</td>
                            </tr>
                            <tr>
                                <td width="100">剩余空间：</td>
                                <td>23817.34M</td>
                            </tr>
                            <tr>
                                <td width="100">服务器时间：</td>
                                <td>2024年12月23日 16:03:33</td>
                            </tr>
                            <tr>
                                <td width="100">北京时间：</td>
                                <td>2024年12月23日 16:03:33</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="layui-row ">
            <div class="layui-col-md9">

            </div>
        </div>
    </div>
@endsection
@yield('script')
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script>
        layui.use(function () {
            $('.item-link').click(function () {
                var href = $(this).data('href');
                if( href != undefined ) {
                    parent.window.location.href = $(this).data('href');
                }
            });
            var myChart = echarts.init(document.getElementById('my_chart'));
            var option = {
                title: {
                    text: '堆叠区域图'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'cross',
                        label: {
                            backgroundColor: '#6a7985'
                        }
                    }
                },
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                legend: {
                    data: ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
                },
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
                    }
                ],
                series: [
                    {
                        name: '邮件营销',
                        type: 'line',
                        stack: '总量',
                        areaStyle: {},
                        data: [120, 132, 101, 134, 90, 230, 210]
                    }
                ]
            };
            myChart.setOption(option);
        });
    </script>
@endsection

