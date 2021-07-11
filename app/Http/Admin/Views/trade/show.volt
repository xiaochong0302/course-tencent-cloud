{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('order/macro') }}
    {{ partial('trade/macro') }}
    {{ partial('refund/macro') }}
    {{ partial('trade/trade_info') }}

    <br>

    {% set refund_url = url({'for':'admin.trade.refund','id':trade.id}) %}

    <div class="kg-center">
        {% if trade.status == 2 %}
            <button class="kg-refund layui-btn" data-url="{{ refund_url }}">申请退款</button>
        {% endif %}
        <button class="kg-back layui-btn layui-btn-primary">返回上页</button>
    </div>

    {% if refunds.count() > 0 %}
        <fieldset class="layui-elem-field layui-field-title">
            <legend>退款信息</legend>
        </fieldset>
        <table class="layui-table kg-table">
            <tr>
                <th>退款序号</th>
                <th>退款金额</th>
                <th>退款原因</th>
                <th>退款状态</th>
                <th>创建时间</th>
                <th></th>
            </tr>
            {% for item in refunds %}
                {% set history_url = url({'for':'admin.refund.status_history','id':item.id}) %}
                {% set show_url = url({'for':'admin.refund.show','id':item.id}) %}
                <tr>
                    <td>{{ item.sn }}</td>
                    <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                    <td><a href="javascript:" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                    <td><a class="kg-status-history" href="javascript:" title="查看历史状态" data-url="{{ history_url }}">{{ refund_status(item.status) }}</a></td>
                    <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                    <td><a class="layui-btn layui-btn-sm" href="{{ show_url }}">详情</a></td>
                </tr>
            {% endfor %}
        </table>
    {% endif %}

    <br>

    {{ partial('order/order_info') }}

    <br>

    {{ partial('order/user_info') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/status-history.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('.kg-refund').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    type: 2,
                    title: '申请退款',
                    content: [url, 'no'],
                    area: ['800px', '320px']
                });
            });

        });

    </script>

{% endblock %}