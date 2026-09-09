{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/payment') }}
    {{ partial('macros/refund') }}

    {% set cancel_url = url({'for':'home.refund.cancel'}) %}

    <table class="layui-table order-table">
        <tr>
            <td>退款编号</td>
            <td>退款金额</td>
            <td>创建时间</td>
            <td>退款状态</td>
        </tr>
        <tr>
            <td>{{ refund.sn }}</td>
            <td class="price">{{ '￥%0.2f'|format(refund.amount) }}</td>
            <td>{{ date('Y-m-d H:i',refund.create_time) }}</td>
            <td>{{ refund_status(refund.status) }}</td>
        </tr>
    </table>
    <br>
    <table class="layui-table order-table">
        <tr>
            <td>订单名称</td>
            <td>订单编号</td>
            <td>订单平台</td>
            <td>订单金额</td>
        </tr>
        <tr>
            <td>{{ refund.order.subject }}</td>
            <td>{{ refund.order.sn }}</td>
            <td>{{ channel_type(refund.order.channel) }}</td>
            <td class="price">{{ '￥%0.2f'|format(refund.order.amount) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if refund.me.allow_cancel == 1 %}
            <button class="layui-btn btn-refund-cancel" data-sn="{{ refund.sn }}" data-url="{{ cancel_url }}">取消退款</button>
        {% endif %}
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}
