{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/refund') }}

    {% set cancel_url = url({'for':'home.refund.cancel'}) %}

    <table class="layui-table order-table" lay-size="lg">
        <tr>
            <td colspan="2">
                <span>订单金额：<em class="price">{{ '￥%0.2f'|format(refund.order.amount) }}</em></span>
                <span>退款金额：<em class="price">{{ '￥%0.2f'|format(refund.amount) }}</em></span>
                <span>退款状态：{{ refund_status(refund.status) }}</span>
            </td>
        </tr>
        <tr>
            <td>{{ refund.subject }}</td>
            <td>{{ status_history(refund.status_history) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if refund.me.allow_cancel == 1 %}
            <button class="layui-btn btn-refund-cancel" data-sn="{{ refund.sn }}" data-url="{{ cancel_url }}">取消退款</button>
        {% endif %}
    </div>

{% endblock %}
