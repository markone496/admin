@extends('lzadmin.layouts.index')

@section('script')
    <script>
        function resetPassword(obj, table) {
            layer.prompt({title: '请输入新密码', formType: 3}, function (value, index) {
                com.post('/sys/user/password', {id: obj.data.id, password: value}, function (res) {
                    if (res.code) {
                        layer.msg(res.msg, {icon: 2})
                    } else {
                        layer.msg(res.msg, {icon: 1});
                        layer.close(index);
                    }
                });
            });
        }
    </script>
@endsection
