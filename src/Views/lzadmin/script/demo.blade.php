@extends('lzadmin.layouts.index')

@section('script')
    <script>
        /**
         *  顶部按钮事件
         *  事件名对应方法名
         *  obj: table对象
         *  func：内置方法对象; 获取被选中的数据:func.getCheckData()、刷新列表数据:func.reload()
         */
        function topBtnEvent(obj, func) {

        }

        /**
         *  行按钮事件
         *  事件名对应方法名
         *  obj: 行对象;获取行数据：obj.data
         *  table：table对象
         */
        function rowBtnEvent(obj, table) {

        }
    </script>
@endsection
