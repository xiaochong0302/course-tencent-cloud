{% extends 'templates/main.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>支付订单</cite></a>
    </div>

    <div class="payment wrap">
        <div class="header">
            订单名称：<span>{{ order.subject }}</span>
            订单编号：<span>{{ order.sn }}</span>
            支付金额：<span class="amount">{{ '￥%0.2f'|format(order.amount) }}</span>
        </div>
        <div class="channel">
            {% set create_url = url({'for':'desktop.trade.create'}) %}
            {% set status_url = url({'for':'desktop.trade.status'}) %}
            <a class="alipay btn-pay" href="javascript:" data-channel="1"></a>
            <a class="wxpay btn-pay" href="javascript:" data-channel="2"></a>
        </div>
        <div class="footer">
            <span class="tips">友情提示：请在12小时内完成支付，有问题请联系客服</span>
        </div>
    </div>

    <div id="alipay-qrcode" class="layui-hide"></div>
    <div id="wxpay-qrcode" class="layui-hide"></div>

    <div class="layui-hide">
        <input type="hidden" name="trade_create_url" value="{{ url({'for':'desktop.trade.create'}) }}">
        <input type="hidden" name="trade_status_url" value="{{ url({'for':'desktop.trade.status'}) }}">
        <input type="hidden" name="forward_url" value="{{ url({'for':'desktop.my.orders'}) }}">
        <input type="hidden" name="order_sn" value="{{ order.sn }}">
        <input type="hidden" name="alipay_trade_sn">
        <input type="hidden" name="wxpay_trade_sn">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('desktop/js/order.pay.js') }}

{% endblock %}