{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/order') }}

    {% set order_cancel_url = url({'for':'home.order.cancel'}) %}
    {% set order_pay_url = url({'for':'home.order.pay'},{'sn':order.sn}) %}
    {% set refund_confirm_url = url({'for':'home.refund.confirm'},{'sn':order.sn}) %}

    <table class="layui-table order-table">
        <tr>
            <td colspan="2">
                <span>订单金额：<em class="price">{{ '￥%0.2f'|format(order.amount) }}</em></span>
                <span>订单状态：{{ order_status(order.status) }}</span>
            </td>
        </tr>
        <tr>
            <td>{{ item_info(order) }}</td>
            <td>{{ status_history(order.status_history) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if order.me.allow_pay == 1 %}
            <a class="layui-btn layui-bg-blue" href="{{ order_pay_url }}" target="_top">立即支付</a>
        {% endif %}
        {% if order.me.allow_cancel == 1 %}
            <button class="layui-btn layui-bg-red btn-order-cancel" data-sn="{{ order.sn }}" data-url="{{ order_cancel_url }}">立即取消</button>
        {% endif %}
        {% if order.me.allow_refund == 1 %}
            <a class="layui-btn layui-bg-blue" href="{{ refund_confirm_url }}">申请退款</a>
        {% endif %}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/order.info.js') }}

{% endblock %}
