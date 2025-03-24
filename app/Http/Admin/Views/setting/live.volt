{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">推流配置</li>
            <li>拉流配置</li>
            <li>回调配置</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/live_push') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/live_pull') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/live_notify') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form', 'layer'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;

            form.on('radio(push_auth_enabled)', function (data) {
                var block = $('#push-auth-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(pull_auth_enabled)', function (data) {
                var block = $('#pull-auth-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(pull_trans_enabled)', function (data) {
                var block = $('#pull-trans-tpl-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            $('#show-push-test').on('click', function () {
                var url = '/admin/test/live/push?stream=test';
                layer.open({
                    type: 2,
                    title: '推流测试',
                    area: ['720px', '540px'],
                    content: [url, 'no']
                });
            });

            $('#show-pull-test').on('click', function () {
                var url = '/admin/test/live/pull';
                layer.open({
                    type: 2,
                    title: '拉流测试',
                    resize: false,
                    area: ['720px', '456px'],
                    content: [url, 'no']
                });
            });

        });

    </script>

{% endblock %}
