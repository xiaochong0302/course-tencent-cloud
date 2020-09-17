{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/order') }}

    {% set order_pay_url = url({'for':'home.order.pay'},{'sn':order.sn}) %}
    {% set refund_confirm_url = url({'for':'home.refund.confirm'},{'sn':order.sn}) %}

    <table class="layui-table order-table" lay-size="lg">
        <tr>
            <td colspan="2">
                订单金额：<span class="price">{{ '￥%0.2f'|format(order.amount) }}</span>
                订单状态：<span class="status">{{ order_status(order.status) }}</span>
            </td>
        </tr>
        <tr>
            <td>{{ item_info(order) }}</td>
            <td>{{ status_history(order.status_history) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if order.status == 'pending' %}
            <a class="layui-btn layui-bg-blue" href="{{ order_pay_url }}" target="_top">立即支付</a>
        {% endif %}
        {% if (order.item_type in ['course','package']) and (order.status == 'finished') %}
            <a class="layui-btn layui-bg-blue" href="{{ refund_confirm_url }}">申请退款</a>
        {% endif %}
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        layui.use(['jquery', 'layer'], function () {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.iframeAuto(index);
        });
    </script>

{% endblock %}