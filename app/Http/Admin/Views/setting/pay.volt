{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-tab layui-tab-brief">
        <ul class="layui-tab-title kg-tab-title">
            <li class="layui-this">支付宝</li>
            <li>微信支付</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                {{ partial('setting/pay_alipay') }}
            </div>
            <div class="layui-tab-item">
                {{ partial('setting/pay_wxpay') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('#show-alipay-test').on('click', function () {
                var url = '/admin/test/alipay';
                layer.open({
                    type: 2,
                    title: '支付宝 - 支付测试',
                    resize: false,
                    area: ['640px', '320px'],
                    content: [url, 'no']
                });
            });

            $('#show-wxpay-test').on('click', function () {
                var url = '/admin/test/wxpay';
                layer.open({
                    type: 2,
                    title: '微信 - 支付测试',
                    resize: false,
                    area: ['640px', '320px'],
                    content: [url, 'no']
                });
            });

        });

    </script>

{% endblock %}
