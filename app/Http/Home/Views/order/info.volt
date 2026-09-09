{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/payment') }}
    {{ partial('macros/order') }}

    {% set order_cancel_url = url({'for':'home.order.cancel'}) %}
    {% set order_pay_url = url({'for':'home.order.pay'},{'sn':order.sn}) %}
    {% set refund_confirm_url = url({'for':'home.refund.confirm'},{'sn':order.sn}) %}

    <table class="layui-table order-table">
        <tr>
            <td>订单编号</td>
            <td>订单金额</td>
            <td>创建时间</td>
            <td>订单状态</td>
        </tr>
        <tr>
            <td>{{ order.sn }}</td>
            <td class="price">{{ '￥%0.2f'|format(order.amount) }}</td>
            <td>{{ date('Y-m-d H:i',order.create_time) }}</td>
            <td>{{ order_status(order.status) }}</td>
        </tr>
    </table>
    <br>
    <table class="layui-table order-table">
        <tr>
            <td>商品信息</td>
            <td>订单平台</td>
        </tr>
        <tr>
            <td>{{ item_info(order) }}</td>
            <td>{{ channel_type(order.channel) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if order.me.allow_pay == 1 %}
            <button class="layui-btn layui-bg-red btn-order-pay" data-url="{{ order_pay_url }}">支付订单</button>
        {% endif %}
        {% if order.me.allow_cancel == 1 %}
            <button class="layui-btn layui-bg-gray btn-order-cancel" data-sn="{{ order.sn }}" data-url="{{ order_cancel_url }}">取消订单</button>
        {% endif %}
        {% if order.me.allow_refund == 1 %}
            <button class="layui-btn layui-bg-blue btn-order-refund" data-url="{{ refund_confirm_url }}">申请退款</button>
        {% endif %}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}
