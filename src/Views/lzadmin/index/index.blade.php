@extends('lzadmin.layouts.app')
@section('title', '管理后台')
@section('styles')
@endsection

@section('content')
    <div id="LAY_app">
        <div class="layui-layout layui-layout-admin">
            <div class="layui-header">
                <!-- 头部区域 -->
                <ul class="layui-nav layui-layout-left">
                    <li class="layui-nav-item layadmin-flexible" lay-unselect>
                        <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                            <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;" layadmin-event="refresh" title="刷新">
                            <i class="layui-icon layui-icon-refresh-3"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <div class="layui-form layui-input-search">
                            <select lay-search="" lay-filter="menu_search">
                                <option value="">搜索菜单</option>
                                @foreach($access_menus as $item)
                                    <option value="{{$item->route}}">{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </li>
                </ul>
                <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

                    <li class="layui-nav-item" lay-unselect>
                        <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
                            <i class="layui-icon layui-icon-notice"></i>
                            <!-- 如果有新消息，则显示小圆点 -->
                            <span class="layui-badge-dot"></span>
                        </a>
                    </li>

                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="note">
                            <i class="layui-icon layui-icon-note"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="fullscreen">
                            <i class="layui-icon layui-icon-screen-full"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect style="margin-right: 10px">
                        <a href="javascript:;">
                            <cite>{{empty($user) ? '开发者' : $user['nickname']}}</cite>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="/passwordView">修改密码</a></dd>
                            <hr>
                            <dd style="text-align: center;" id="loginOutBtn">
                                <a href="/loginOut">退出登录</a>
                            </dd>
                        </dl>
                    </li>
                </ul>
            </div>

            <!-- 侧边菜单 -->
            <div class="layui-side layui-side-menu">
                <div class="layui-side-scroll">
                    <div class="layui-logo" lay-href="home/console.html">
                        <span>{{ config('admin')['name'] }}管理系统</span>
                    </div>

                    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu"
                        lay-filter="layadmin-system-side-menu">
                        @foreach($menus as $item)
                            @continue($item['is_hide'])
                            <li class="layui-nav-item">
                                <a href="javascript:;" @if(empty($item['children'])) lay-href="{{$item['route']}}"
                                   @endif lay-tips="{{$item['title']}}">
                                    <i class="{{$item['icon']}}"></i>
                                    <cite>{{$item['title']}}</cite>
                                </a>
                                @foreach($item['children'] as $item1)
                                    @continue($item1['is_hide'])
                                    <dl class="layui-nav-child">
                                        <dd>
                                            @if(empty($item1['children']))
                                                <a lay-href="{{$item1['route']}}"> {{$item1['title']}}</a>
                                            @else
                                                <a href="javascript:;"><i class="{{$item1['icon']}}"></i>{{$item1['title']}}</a>
                                                <dl class="layui-nav-child">
                                                    @foreach($item1['children'] as $item2)
                                                        @continue($item2['is_hide'])
                                                        <dd><a lay-href="{{$item2['route']}}">{{$item2['title']}}</a>
                                                        </dd>
                                                    @endforeach
                                                </dl>
                                            @endif
                                        </dd>
                                    </dl>
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 页面标签 -->
            <div class="layadmin-pagetabs" id="LAY_app_tabs">
                <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                        <li class="layui-nav-item" lay-unselect>
                            <a href="javascript:;"></a>
                            <dl class="layui-nav-child layui-anim-fadein">
                                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
                <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                    <ul class="layui-tab-title" id="LAY_app_tabsheader">
                        <li lay-id="/main" lay-attr="/main" class="layui-this"><i
                                class="layui-icon layui-icon-home"></i></li>
                    </ul>
                </div>
            </div>


            <!-- 主体内容 -->
            <div class="layui-body" id="LAY_app_body">
                <div class="layadmin-tabsbody-item layui-show">
                    <iframe src="{{config('admin')['main_route']}}" frameborder="0" class="layadmin-iframe"></iframe>
                </div>
            </div>

            <!-- 辅助元素，一般用于移动设备下遮罩 -->
            <div class="layadmin-body-shade" layadmin-event="shade"></div>
        </div>
    </div><h1>Welcome to the Home Page</h1>
@endsection
@section('scripts')
    <script>
        layui.config({
            base: '/assets/layuiadmin/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'form', 'element'], function () {
            var form = layui.form;
            //菜单搜索
            form.on('select(menu_search)', function (data) {
                var value = data.value; // 获得被选中的值
                var title = this.innerHTML;
                layui.element.tabAdd('layadmin-layout-tabs', {
                    title: title,
                    id: value,
                });
                $('.layadmin-tabsbody-item').removeClass('layui-show');
                $('.layui-body').append('<div class="layadmin-tabsbody-item layui-show"><iframe src="' + value + '" frameborder="0" class="layadmin-iframe"></iframe></div>');
                // 成功加载后激活选项卡
                layui.element.tabChange('layadmin-layout-tabs', value);
            });
        });
    </script>
@endsection
