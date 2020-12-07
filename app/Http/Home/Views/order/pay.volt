{% extends 'templates/main.volt' %}

{% block content %}

    {% set create_url = url({'for':'home.trade.create'}) %}
    {% set status_url = url({'for':'home.trade.status'}) %}

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
            {% if pay_provider.alipay.enabled == 1 %}
                <a class="alipay btn-pay" href="javascript:" data-channel="alipay">{{ image('home/img/alipay.png') }}</a>
            {% endif %}
            {% if pay_provider.wxpay.enabled == 1 %}
                <a class="wxpay btn-pay" href="javascript:" data-channel="wxpay">{{ image('home/img/wxpay.png') }}</a>
            {% endif %}
        </div>
        <div class="footer">
            <span class="tips">友情提示：请在12小时内完成支付，有问题请联系客服</span>
        </div>
    </div>

    <div id="alipay-qrcode" class="layui-hide"></div>
    <div id="wxpay-qrcode" class="layui-hide"></div>

    <div class="layui-hide">
        <input type="hidden" name="trade_create_url" value="{{ url({'for':'home.trade.create'}) }}">
        <input type="hidden" name="trade_status_url" value="{{ url({'for':'home.trade.status'}) }}">
        <input type="hidden" name="forward_url" value="{{ url({'for':'home.uc.orders'}) }}">
        <input type="hidden" name="order_sn" value="{{ order.sn }}">
        <input type="hidden" name="alipay_trade_sn">
        <input type="hidden" name="wxpay_trade_sn">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/order.pay.js') }}

{% endblock %}