<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="referrer" content="no-referrer">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{customAsset('assets/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{customAsset('assets/layuiadmin/style/admin.css')}}" media="all">
    <link rel="stylesheet" href="{{customAsset('assets/base/base.css')}}">
    <link rel="stylesheet" href="{{customAsset('assets/base/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{customAsset('assets/base/wangeditor.css')}}">
    @yield('styles')
</head>
<body>
@yield('content')
<script src="{{customAsset('assets/base/jquery-3.6.0.min.js')}}"></script>
<script src="{{customAsset('assets/base/wangeditor.js')}}"></script>
<script src="{{customAsset('assets/base/jquery-ui.js')}}"></script>
<script src="{{customAsset('assets/layuiadmin/layui/layui.js')}}"></script>
<script src="{{customAsset('assets/base/crypto-js.min.js')}}"></script>
<script src="{{customAsset('assets/base/xlsx.full.min.js')}}"></script>
<script src="{{customAsset('assets/base/base.js')}}"></script>
@yield('scripts')
</body>
</html>
