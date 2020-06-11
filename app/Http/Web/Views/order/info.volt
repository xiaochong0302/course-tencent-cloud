{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_order') }}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="{{ url({'for':'web.my.orders'}) }}">我的订单</a>
        <a><cite>订单详情</cite></a>
        <a><cite>{{ order.subject }}</cite></a>
    </div>

    <div class="container">
        <table class="layui-table kg-table order-table" lay-size="lg">
            <tr>
                <td>基本信息</td>
                <td>订单金额</td>
                <td>订单类型</td>
                <td>订单状态</td>
                <td>流转时间</td>
            </tr>
            <tr>
                <td>{{ item_info(order) }}</td>
                <td><span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></td>
                <td>{{ item_type(order.item_type) }}</td>
                <td>{{ order_status(order.status) }}</td>
                <td>{{ status_history(order.status_history) }}</td>
            </tr>
        </table>
        <br>
        <div class="text-center">
            <a href="javascript:" class="kg-back layui-btn layui-bg-gray">返回上页</a>
            {% if order.status == 'pending' %}
                <a class="layui-btn layui-bg-blue" href="{{ url({'for':'web.order.pay'},{'sn':order.sn}) }}">立即支付</a>
            {% endif %}
            {% if (order.item_type in ['course','package']) and (order.status == 'finished') %}
                <a class="layui-btn layui-bg-blue" href="{{ url({'for':'web.refund.confirm'},{'sn':order.sn}) }}">申请退款</a>
            {% endif %}
        </div>
        <br>
    </div>

{% endblock %}