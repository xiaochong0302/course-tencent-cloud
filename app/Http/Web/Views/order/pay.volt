{% extends 'templates/base.volt' %}

{% block content %}

    {%- macro reward_course_card(reward) %}
        <div>I am reward</div>
    {%- endmacro %}

    {%- macro vip_course_card(vip) %}
        <div>I am vip</div>
    {%- endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>支付订单</cite></a>
    </div>

    <div class="payment module">
        <div class="header">
            订单名称：<span>{{ order.subject }}</span>
            订单编号：<span>{{ order.sn }}</span>
            支付金额：<span class="amount">￥{{ order.amount }}</span>
        </div>
        <div class="channel">
            <a class="alipay" href="javascript:"></a>
            <a class="wxpay" href="javascript:"></a>
        </div>
        <div class="footer">
            <span class="tips">友情提示：请在12小时内完成支付，有问题请联系客服</span>
        </div>
    </div>

{% endblock %}