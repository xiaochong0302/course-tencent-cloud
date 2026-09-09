{% extends 'templates/main.volt' %}

{% block content %}

    {% set closed_tips_display = site.status == 'closed' ? 'display:block' : 'display:none' %}
    {% set analytics_script_display = site.analytics_enabled == 1 ? 'display:block' : 'display:none' %}

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本信息</li>
            <li>备案信息</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('setting/site_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('setting/site_filing') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'layer', 'upload'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var upload = layui.upload;

            form.on('radio(status)', function (data) {
                var block = $('#closed-tips-block');
                if (data.value === 'closed') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(analytics_enabled)', function (data) {
                var block = $('#analytics-script-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            upload.render({
                elem: '#upload-logo',
                url: '/admin/upload/icon/img',
                accept: 'images',
                acceptMime: 'image/*',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=logo]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

            upload.render({
                elem: '#upload-favicon',
                url: '/admin/upload/icon/img',
                accept: 'images',
                acceptMime: 'image/*',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=favicon]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

        });

    </script>

{% endblock %}
