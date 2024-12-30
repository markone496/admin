@extends('lzadmin.layouts.index')

@section('script')
    <script>
        function copy(obj, table) {
            layer.confirm('确定要复制【' + obj.data.role_name + '】么？', function (index) {
                com.post('/sys/role/copy', {id: obj.data.id}, function (res) {
                    if (res.code) {
                        layer.msg(res.msg, {icon: 2})
                    } else {
                        layer.msg(res.msg, {icon: 1});
                        table.reload();
                        layer.close(index);
                    }
                });
            });
        }
    </script>
@endsection
