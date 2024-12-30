<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>404 页面不存在</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{customAsset('assets/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{customAsset('assets/layuiadmin/style/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{customAsset('assets/base/base.css')}}">
</head>
<body>


<div class="layui-fluid">
    <div class="layadmin-tips">
        <i class="layui-icon" face>&#xe61c;</i>
        <div class="layui-text">
            <h1>
                <span class="layui-anim layui-anim-loop layui-anim-">4</span>
                <span class="layui-anim layui-anim-loop layui-anim-rotate">0</span>
                <span class="layui-anim layui-anim-loop layui-anim-">4</span>
            </h1>
            <h3>{{$msg ?? '页面不存在'}}</h3>
        </div>
    </div>
</div>
</body>
</html>
