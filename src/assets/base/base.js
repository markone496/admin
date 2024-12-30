var com = {
    /**
     * post请求
     * @param url
     * @param data
     * @param call
     */
    post: function (url, data, call) {
        let index = layer.load();
        data = $.extend({
            _token: $('meta[name="csrf-token"]').attr('content')
        }, data);
        $.post(url, data, function (res) {
            layer.close(index);
            if (res.code === -1) {
                layer.msg(res.msg, {icon: 2}, function () {
                    var currentWindow = window;
                    while (true) {
                        var innerDoc = currentWindow.document;
                        // 判断是否存在要修改的元素
                        if ($(innerDoc).find('#loginOutBtn').length > 0) {
                            innerDoc.location.href = '/loginOut';
                            break;
                        }
                        currentWindow = currentWindow.parent;
                    }
                });
                return false;
            }
            call && call(res);
        }, 'json').fail(function (xhr, status, error) {
            layer.close(index);
            let res = {code: 1, msg: 'Http请求：' + xhr.status};
            call && call(res);
        });
    },
    /**
     * 图标选择
     * @param call
     */
    iconChose: function (call) {
        layer.open({
            type: 2
            , title: '选择图标'
            , content: '/sys/icon'
            , fixed: false
            , maxmin: true
            , area: ['100%', '100%']
            , btn: ['确定', '取消']
            , btnAlign: 'c'
            , yes: function (index, layero) {
                let iframeWindow = window['layui-layer-iframe' + index];
                iframeWindow.submit(function (icon) {
                    layer.close(index);
                    call && call(icon);
                });
            }
        });
    },
    /**
     * 字符加密
     * @param value
     * @returns {string}
     */
    encryptionCustom: function (value) {
        let key = CryptoJS.enc.Utf8.parse('GftZqNEoBVdB2kwx');
        let iv = CryptoJS.enc.Utf8.parse('3zyJFPEzh5rUeUNi');
        let encryptData = CryptoJS.AES.encrypt(value, key, {
            mode: CryptoJS.mode.CBC,
            iv: iv,
            padding: CryptoJS.pad.Pkcs7
        });
        return encryptData.toString();
    },
    /**
     * 数据表格
     * @param option
     * @param eventCallback
     */
    table: function (option, eventCallback) {
        let table = layui.table;
        let form = layui.form;
        //定义表格参数
        let options = $.extend({
            title: '数据列表',
            id: 'table',
            elem: '#table',
            toolbar: '#toolbarDemo',
            method: 'post',
            height: 'full-120',
            page: true,
            limits: [20, 50, 100, 500],
            limit: 20
        }, option);
        //追加请求条件
        options.where = $.extend({
            _token: $('meta[name="csrf-token"]').attr('content')
        }, options.where);
        //定义表格ID
        let tableId = options.id;
        //定义表格事件
        let tableEvent = $.extend({
            toolbar: null,
            tool: null,
            edit: null,
        }, eventCallback);
        //实例化表格
        var tableObj = table.render(options);
        //触发排序事件
        table.on('sort(' + tableId + ')', function (obj) {
            var where = obj.config.where;
            where.order_by_field = obj.field;
            where.order_by_type = obj.type;
            table.reload(tableId, {
                page: {
                    curr: 1
                }
                , where: where
            });
            return false;
        });
        //监听头部按钮点击事件
        table.on('toolbar(' + tableId + ')', function (obj) {
            if (obj.event === 'reload') {//表格重载
                table.reload(tableId);
            } else if (obj.event === 'export') {//导出
                com.layerTableExport(obj);
            } else {//自定义方法
                let func = {
                    getCheckData: function () {//获取选中数据
                        let checkStatus = table.checkStatus(tableId);
                        return checkStatus.data;
                    },
                    reload: function () {
                        table.reload(tableId);
                    }
                };
                if (typeof tableEvent.toolbar === 'function') {
                    tableEvent.toolbar(obj, func);
                }
            }
        });
        //监听行点击事件
        table.on('tool(' + tableId + ')', function (obj) {
            if (typeof tableEvent.tool === 'function') {
                tableEvent.tool(obj, this);
            }
        });
        //监听行编辑事件
        table.on('edit(' + tableId + ')', function (obj) {
            if (typeof tableEvent.edit === 'function') {
                tableEvent.edit(obj);
            }
        });

        //搜索
        let searchFormData;

        function tableSearch(data) {
            data._token = $('meta[name="csrf-token"]').attr('content');
            // 执行搜索重载
            table.reload(tableId, {
                page: {
                    curr: 1 // 重新从第 1 页开始
                },
                where: data // 搜索的字段
            });
        }

        // 监听搜索
        form.on('submit(' + tableId + 'Search)', function (data) {
            var field = data.field; // 获得表单字段
            tableSearch(field);
            searchFormData = field;
            return false; // 阻止默认 form 跳转
        });
        //更多搜索
        $('#other-search-btn').click(function () {
            layer.open({
                title: '更多搜索',
                area: com.getLayerOpenArea(400),
                content: $('#other-search-form').html(),
                shadeClose: true,
                scrollbar: false,
                offset: 'r',
                skin: "layui-anim layui-anim-rl layui-layer-adminRight",
                anim: -1,
                btnAlign: 'c',
                btn: ['搜索', '重置'],
                success: function (layero) {
                    form.val('other-search-form', searchFormData);
                    //渲染日期组件
                    layero.find('.customer-layDate-obj').each(function () {
                        let obj = this;
                        let type = $(obj).attr('data-type');
                        let range = $(obj).attr('data-range');
                        com.laydateRender(obj, type, range);
                    });
                }
                , yes: function (index, layero) {
                    var field = form.val('other-search-form');
                    searchFormData = field;
                    layer.close(index);
                    tableSearch(field);
                    form.val('search-form', searchFormData);
                }
                , btn2: function (index, layero) {
                    var formElem = layero.find('form');
                    // 重置表单
                    formElem[0].reset();
                    return false;
                }
            });
        });
        return tableObj;
    },

    /**
     * 弹出表单
     * @param option
     */
    openForm: function (option) {
        let options = $.extend({
            title: '表单编辑',
            width: 500,
            content: '',
            callback: null,
            shadeClose: false,
            btn: ['确定', '关闭'],
            yes: function (index, layero) {
                var iframeWindow = window['layui-layer-iframe' + index];
                iframeWindow.submit(function (field) {
                    options.callback && options.callback(index, field);
                });
            }
        }, option);
        layer.open({
            title: options.title,
            area: com.getLayerOpenArea(options.width),
            type: 2,
            content: options.content,
            shadeClose: options.shadeClose,
            scrollbar: false,
            offset: 'r',
            skin: "layui-anim layui-anim-rl layui-layer-adminRight",
            anim: -1,
            btnAlign: 'c',
            btn: options.btn,
            yes: options.yes
        });
    },
    laydateRender: function (obj, type, range) {
        let laydate = layui.laydate;
        let option = {
            elem: obj,
            type: type,
            position: 'fixed',
        };
        if (!!range) {
            option.range = true;
            option.rangeLinked = true;
        }
        laydate.render(option);
    },
    /**
     * 获取layui弹窗宽高
     * @param width
     * @param height
     * @returns {[string, string]}
     */
    getLayerOpenArea: function (width, height) {
        if (!width) {
            width = 0;
        }
        if (!height) {
            height = 0;
        }
        let clientWidth = document.documentElement.clientWidth;
        let clientHeight = document.documentElement.clientHeight;
        if (width === 0 || clientWidth <= width) {
            width = '100%';
        } else {
            width = width + 'px';
        }
        if (height === 0 || clientHeight <= height) {
            height = '100%';
        } else {
            height = height + 'px';
        }
        return [width, height];
    },
    /**
     * 上传图片
     * @param elem
     * @param callback
     */
    uploadImage: function (elem, callback) {
        var upload = layui.upload;
        //拖拽上传
        upload.render({
            elem: elem
            , url: '/upload/'
            , auto: false
            , choose: function (obj) {
                var currentElem = this.item; // 获取当前点击的元素
                com.post('/sys/upload/config', {}, function (res) {
                    if (res.code === 0) {
                        var loadIndex = layer.load(4, {
                            content: "<div class='customer-loading-content'>上传中...</div>",
                            shade: [0.4, '#000']
                        });
                        var data = res.data;
                        //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
                        obj.preview(function (index, file, result) {
                            var file_info = file.name.split('.');
                            var file_name = data.filename + '.' + file_info[1];
                            var file_url = data.cdn_host + '/' + file_name;
                            //获取OSS认证信息
                            var formData = new FormData();
                            //七牛云
                            // formData.append('key', file_name); //存储在oss的文件路径
                            // formData.append('token', data.token); //签名
                            // formData.append("file", file);

                            //OSS配置
                            formData.append('key', file_name);
                            formData.append('OSSAccessKeyId', data.accessKeyId);
                            formData.append("policy", data.policy);
                            formData.append('Signature', data.signature);
                            formData.append("success_action_status", 200);
                            formData.append("file", file);
                            $.ajax({
                                url: data.host,
                                type: 'POST',
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function () {
                                    callback && callback(file_url, currentElem);
                                    layer.close(loadIndex);
                                },
                                error: function () {
                                    layer.close(loadIndex);
                                    layer.msg('上传失败', {icon: 2});
                                }
                            });
                        });

                    } else {
                        layer.msg(res.msg, {icon: 2})
                    }
                });
            }
        });
    },

    /**
     * 上传文件
     * @param elem
     * @param callback
     */
    uploadFile: function (elem, callback) {
        var upload = layui.upload;
        //拖拽上传
        upload.render({
            elem: elem
            , url: '/upload/'
            , accept: 'file' //普通文件
            , auto: false
            , choose: function (obj) {
                var currentElem = this.item; // 获取当前点击的元素

                com.post('/sys/upload/config', {}, function (res) {
                    if (res.code === 0) {
                        var loadIndex = layer.load(4, {
                            content: "<div class='customer-loading-content'>上传中...</div>",
                            shade: [0.4, '#000']
                        });
                        var data = res.data;
                        //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
                        obj.preview(function (index, file, result) {
                            var file_info = file.name.split('.');
                            var file_name = data.filename + '.' + file_info[1];
                            var file_url = data.cdn_host + '/' + file_name;
                            //获取OSS认证信息
                            var formData = new FormData();
                            //七牛云
                            // formData.append('key', file_name); //存储在oss的文件路径
                            // formData.append('token', data.token); //签名
                            // formData.append("file", file);

                            //OSS配置
                            formData.append('key', file_name);
                            formData.append('OSSAccessKeyId', data.accessKeyId);
                            formData.append("policy", data.policy);
                            formData.append('Signature', data.signature);
                            formData.append("success_action_status", 200);
                            formData.append("file", file);
                            $.ajax({
                                url: data.host,
                                type: 'POST',
                                data: formData,
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function () {
                                    callback && callback(file_url, currentElem);
                                    layer.close(loadIndex);
                                },
                                error: function () {
                                    layer.close(loadIndex);
                                    layer.msg('上传失败', {icon: 2});
                                }
                            });
                        });

                    } else {
                        layer.msg(res.msg, {icon: 2})
                    }
                });
            }
        });
    },

    /**
     * 富文本编辑器
     * @param obj
     */
    wangEditor: function (obj) {
        let value = $(obj).find("textarea").val();
        let {createEditor, createToolbar} = window.wangEditor;
        let editorConfig = {
            MENU_CONF: {
                uploadImage: {
                    async customUpload(file, insertFn) {
                        com.post('/sys/upload/config', {}, function (res) {
                            if (res.code === 0) {
                                var data = res.data;
                                var file_info = file.name.split('.');
                                var file_name = data.filename + '.' + file_info[1];
                                var file_url = data.cdn_host + '/' + file_name;
                                //获取OSS认证信息
                                var formData = new FormData();
                                //七牛云配置
                                // formData.append('key', file_name); //存储在oss的文件路径
                                // formData.append('token', data.token); //签名
                                // formData.append("file", file);

                                //OSS配置
                                formData.append('key', file_name);
                                formData.append('OSSAccessKeyId', data.accessKeyId);
                                formData.append("policy", data.policy);
                                formData.append('Signature', data.signature);
                                formData.append("success_action_status", 200);
                                formData.append("file", file);
                                $.ajax({
                                    url: data.host,
                                    type: 'POST',
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function () {
                                        insertFn(file_url, file_url, file_url)
                                    },
                                    error: function () {
                                        layer.msg('上传失败', {icon: 2});
                                    }
                                });

                            } else {
                                layer.msg(res.msg, {icon: 2})
                            }
                        });
                    }
                },
                insertVideo: {
                    onInsertedVideo(videoNode) {
                        if (videoNode == null) return;
                        const {src} = videoNode;
                        console.log('inserted video', src)
                    },
                }
            },
            onChange(editor) {
                const html = editor.getHtml();
                $(obj).find("textarea").val(html);
                // 也可以同步到 <textarea>
            }
        };

        let editor = createEditor({
            selector: $(obj).find('.editor-container').get(0),
            html: value,
            config: editorConfig,
            mode: 'default', // or 'simple'
        });
        if ($(obj).attr('data-edit')) {
            editor.disable();
        }
        createToolbar({
            editor,
            selector: $(obj).find('.toolbar-container').get(0),
            config: {
                toolbarKeys: [
                    "bold", "underline", "justifyLeft", "justifyCenter", "justifyRight", "through", "clearStyle", "color", "bgColor", "fontSize",
                    "fontFamily", "divider", "insertLink", "editLink", "unLink", "headerSelect",
                    "header1", "header2", "undo", "fullScreen", "uploadImage", "insertVideo",
                ],
            },
            mode: 'default', // or 'simple'
        });
    },

    /**
     * 导出excel
     * @param obj
     */
    layerTableExport: function (obj) {
        var count = obj.config.page.count;
        var title = obj.config.title;
        if (count > 1000) {
            layer.confirm('大数据导出时间会很长，确定要导出么？', function (index) {
                layer.close(index);
                startExport();
            });
        } else {
            startExport();
        }

        //开始导出数据
        function startExport() {
            var load = layer.msg('正在下载，请勿刷新或关闭页面...', {
                icon: 16,
                shade: 0.01,
                time: 0 // 设置 time 为 0，使加载动画一直显示
            });
            var where = {};
            $.each(obj.config.where, function (key, value) {
                where[key] = value;
            });
            var url = obj.config.url;
            var cols = colsFormat(obj.config.cols[0]);
            var allData = []; // 用于存储所有数据
            // 获取所有数据
            var page = 1;
            var limit = 50;
            loadData(page, limit);

            //获取字段名
            function colsFormat(cols) {
                var col = {};
                $(cols).each(function (index, item) {
                    if (!item.hide && item.field) {
                        col[item.field] = item.title
                    }
                });
                return col;
            }

            //判断字符串中是否有html标签
            function hasHTMLTags(str) {
                var regex = /(<([^>]+)>)/ig;
                return regex.test(str);
            }

            // 递归获取数据
            function loadData(page, limit) {
                where.page = page;
                where.limit = limit;
                // 发送请求获取数据
                $.post(url, where, function (res) {
                    var currentPageData = res.data;
                    var data = [];
                    $(currentPageData).each(function (index, item) {
                        var field = {};
                        $.each(cols, function (key, title) {
                            var value = item[key];
                            if (hasHTMLTags(item[key])) {
                                value = $(item[key]).text();
                            }
                            field[title] = value;
                        });
                        data.push(field);
                    });
                    allData = allData.concat(data);
                    if (currentPageData.length === limit) {
                        // 若当前页数据数量等于每页数量，继续获取下一页数据
                        loadData(page + 1, limit);
                    } else {
                        // 获取完所有数据后，执行导出操作
                        exportToExcel(allData);
                    }
                });
            }

            // 导出数据到 Excel
            function exportToExcel(data) {
                var worksheet = XLSX.utils.json_to_sheet(data);
                var workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "Sheet1");
                // 将 Excel 文件保存到本地
                XLSX.writeFile(workbook, title + '.xlsx');
                layer.close(load);
            }
        }
    }
};

//符文本编辑器渲染
$('.editor—wrapper').each(function () {
    com.wangEditor(this);
});

//文件上传
com.uploadFile($('.uploadFile').find('.upload'), function (file_url, currentElem) {
    let uploadFile = $(currentElem).closest('.uploadFile');
    $(uploadFile).find("input[type=text]").val(file_url);
});

//单图上传
com.uploadImage($('.uploadImage').find('.upload'), function (file_url, currentElem) {
    let uploadImage = $(currentElem).closest('.uploadImage');
    $(uploadImage).find('img').prop('src', file_url);
    $(uploadImage).find("input[type=hidden]").val(file_url);
    let obj = $(uploadImage).find('.layui-upload-drag');
    if (!!obj) {
        $(obj).hide();
        $(uploadImage).find('.image').show();
    }
});

//多图上传
com.uploadImage($('.uploadImageMultipleBtn'), function (file_url, currentElem) {
    let field = $(currentElem).attr('data-field');
    let uploadImageMultiple = $(currentElem).closest('.layui-form-item').find('.uploadImageMultiple');
    var temp = '<div class="uploadImage">\n' +
        '                        <input type="hidden" name="' + field + '" value="' + file_url + '">\n' +
        '                        <div class="image">\n' +
        '                            <img\n' +
        '                                src="' + file_url + '">\n' +
        '                            <div class="btn">\n' +
        '                                <a class="layui-btn layui-btn-sm upload">更换</a>\n' +
        '                                <a class="layui-btn layui-btn-sm layui-btn-danger delete" data-status="true">删除</a>\n' +
        '                            </div>\n' +
        '                        </div>\n' +
        '                    </div>';
    $(uploadImageMultiple).append(temp);
    var insertedElement = $(uploadImageMultiple).find('.uploadImage:last-child');
    com.uploadImage($(insertedElement).find('.upload'), function (file_url, currentElem) {
        let uploadImage = $(currentElem).closest('.uploadImage');
        $(uploadImage).find('img').prop('src', file_url);
        $(uploadImage).find("input[type=hidden]").val(file_url);
    })
});

/**
 * 删除图片
 */
$('body').on('click', '.uploadImage .delete', function () {
    let obj = $(this).closest('.uploadImage');
    let status = $(this).attr('data-status');
    layer.confirm('确定删除该图片么？', function (index) {
        if (!!status) {
            $(obj).remove();
        } else {
            $(obj).find('input').val('');
            $(obj).find('.layui-upload-drag').show();
            $(obj).find('.image').hide();
        }
        layer.close(index);
    });
});


/**
 * 图片组预览
 */
$('body').on('click', '.image-show-box img', function () {
    let self = this;
    let obj = $(this).closest('.image-show-box');
    let data = [];
    let start = 0;
    $(obj).find('img').each(function (index) {
        if (self === this) {
            start = index;
        }
        let src = $(this).prop('src');
        data.push({
            'src': src
        })
    });
    layer.photos({
        photos: {
            "data": data,
            "start": start
        }
    });
});

/**
 * 多图排序
 */
$(".uploadImageMultiple").sortable({
    items: ".uploadImage"
});

//渲染时间组件
$('.customer-layDate-obj').each(function () {
    let obj = this;
    let type = $(obj).attr('data-type');
    let range = $(obj).attr('data-range');
    com.laydateRender(obj, type, range);
});
