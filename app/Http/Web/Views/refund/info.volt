{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_refund') }}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="{{ url({'for':'web.my.refunds'}) }}">我的退款</a>
        <a><cite>退款详情</cite></a>
        <a><cite>{{ refund.subject }}</cite></a>
    </div>

    <div class="container">
        <table class="layui-table kg-table order-table" lay-size="lg">
            <tr>
                <td>退款项目</td>
                <td>订单金额</td>
                <td>退款金额</td>
                <td>退款状态</td>
                <td>流转时间</td>
            </tr>
            <tr>
                <td>{{ refund.subject }}</td>
                <td><span class="price">{{ '￥%0.2f'|format(refund.order.amount) }}</span></td>
                <td><span class="price">{{ '￥%0.2f'|format(refund.amount) }}</span></td>
                <td>{{ refund_status(refund.status) }}</td>
                <td>{{ status_history(refund.status_history) }}</td>
            </tr>
        </table>
        <br>
        <div class="text-center">
            <button class="kg-back layui-btn layui-bg-gray">返回上页</button>
            {% if refund.status == 'approved' %}
                <button class="kg-refund layui-btn" data-sn="{{ refund.sn }}" data-url="{{ url({'for':'web.refund.cancel'}) }}">取消退款</button>
            {% endif %}
        </div>
        <br>
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        var $ = layui.jquery;
        var layer = layui.layer;
        $('.kg-refund').on('click', function () {
            var url = $(this).data('url');
            var data = {sn: $(this).data('sn')};
            var tips = '确定要取消退款吗？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    success: function (res) {
                        layer.msg(res.msg, {icon: 1});
                        setTimeout(function () {
                            window.location.href = '/my/refunds';
                        }, 1500);
                    },
                    error: function (xhr) {
                        var json = JSON.parse(xhr.responseText);
                        layer.msg(json.msg, {icon: 2});
                    }
                });
            }, function () {

            });
        });
    </script>

{% endblock %}