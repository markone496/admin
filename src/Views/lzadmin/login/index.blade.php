@extends('lzadmin.layouts.app')
@section('title', '系统登录')
@section('styles')
    <link rel="stylesheet" href="{{customAsset('assets/layuiadmin/style/login.css')}}">
    <style>
        #canvas {
            background: #FFF;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
        }
    </style>
@endsection

@section('content')
    <canvas id="canvas"></canvas>
    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">

        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>{{ config('admin')['name'] }}管理系统</h2>
                <p>Management System</p>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                           for="LAY-user-login-username"></label>
                    <input type="text" name="account" lay-verify="required" placeholder="用户名" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                           for="LAY-user-login-password"></label>
                    <input type="password" name="password" lay-verify="required" placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <div class="layui-row">
                        <div class="layui-col-xs7">
                            <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                                   for="LAY-user-login-vercode"></label>
                            <input type="text" name="code" lay-verify="required" placeholder="图形验证码"
                                   class="layui-input">
                        </div>
                        <div class="layui-col-xs5">
                            <div style="margin-left: 10px;cursor: pointer;" id="captcha">
                                {!! captcha_img() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" id="login" lay-submit lay-filter="login">登 录</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var canvas = document.getElementById('canvas');
        var ctx = canvas.getContext('2d');
        var particles = [];
        var maxDistance = 100; // 粒子之间连线的最大距离
        var mouse = {
            x: null,
            y: null
        };

        // 获取鼠标的坐标
        window.addEventListener('mousemove', function (e) {
            mouse.x = e.x;
            mouse.y = e.y;
        });

        // 创建粒子对象
        function Particle(x, y, radius, color) {
            this.x = x;
            this.y = y;
            this.startX = x;
            this.startY = y;
            this.radius = radius;
            this.color = color;
            this.speedX = (Math.random() - 0.5) * 2;
            this.speedY = (Math.random() - 0.5) * 2;
        }

        // 绘制粒子
        Particle.prototype.draw = function () {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2, false);
            ctx.fillStyle = this.color;
            ctx.fill();
            ctx.closePath();
        }

        // 更新粒子位置
        Particle.prototype.update = function () {
            this.x += this.speedX;
            this.y += this.speedY;

            if (this.x + this.radius > canvas.width || this.x - this.radius < 0) {
                this.speedX = -this.speedX;
            }
            if (this.y + this.radius > canvas.height || this.y - this.radius < 0) {
                this.speedY = -this.speedY;
            }

            // 与鼠标位置互动
            if (Math.abs(this.x - mouse.x) < maxDistance && Math.abs(this.y - mouse.y) < maxDistance) {
                if (this.radius < 20) {
                    this.radius += 1;
                }
            } else if (this.radius > 2) {
                this.radius -= 1;
            }

            this.draw();
        }

        // 连接粒子
        function connectParticles() {
            for (var i = 0; i < particles.length; i++) {
                for (var j = i + 1; j < particles.length; j++) {
                    var dx = particles[i].x - particles[j].x;
                    var dy = particles[i].y - particles[j].y;
                    var distance = Math.sqrt(dx * dx + dy * dy);

                    if (distance < maxDistance) {
                        // 绘制连线
                        ctx.beginPath();
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.strokeStyle = 'rgba(255, 255, 255, ' + (1 - distance / maxDistance) + ')';
                        ctx.stroke();
                        ctx.closePath();
                    }
                }
            }
        }

        // 创建粒子并添加到数组中
        function createParticles(amount) {
            for (var i = 0; i < amount; i++) {
                var x = Math.random() * canvas.width;
                var y = Math.random() * canvas.height;
                var radius = Math.random() * 5;
                var color = 'rgba(' + Math.random() * 255 + ',' + Math.random() * 255 + ',' + Math.random() * 255 + ',0.8)';
                particles.push(new Particle(x, y, radius, color));
            }
        }

        // 动画循环
        function animate() {
            requestAnimationFrame(animate);
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (var i = 0; i < particles.length; i++) {
                particles[i].update();
            }

            connectParticles();
        }

        // 初始化
        function init() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            createParticles(50);
            animate();
        }

        window.addEventListener('resize', function () {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        });

        init();

    </script>
    <script>
        layui.use('form', function () {
            var form = layui.form;
            // 验证码刷新
            $('#captcha img').click(function () {
                $(this).prop('src', '/captcha/default?' + new Date().getTime())
            });
            $(document).keydown(function (event) {
                if (event.keyCode == 13) {
                    $('#login').click();
                }
            });
            // 表单提交
            form.on('submit(login)', function (data) {
                let field = data.field;
                field.password = com.encryptionCustom(field.password);
                com.post('/login', field, function (res) {
                    if (res.code) {
                        if (res.code === 2) {
                            $("input[name=code]").val("");
                            $('#captcha img').prop('src', '/captcha/default?' + new Date().getTime());
                        }
                        layer.msg(res.msg, {icon: 2})
                    } else {
                        window.location.href = '/';
                    }
                });
                return false;
            });
        });

    </script>
@endsection
