{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('order/macro') }}
    {{ partial('trade/macro') }}
    {{ partial('refund/macro') }}
    {{ partial('order/order_info') }}

    <br>

    <div class="kg-center">
        <button class="layui-btn layui-btn-primary kg-back">返回上页</button>
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
                {% set refund_sh_url = url({'for':'admin.refund.status_history','id':item.id}) %}
                {% set refund_show_url = url({'for':'admin.refund.show','id':item.id}) %}
                <tr>
                    <td>{{ item.sn }}</td>
                    <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                    <td><a href="javascript:" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                    <td><a class="kg-status-history" href="javascript:" title="查看历史状态" data-url="{{ refund_sh_url }}">{{ refund_status(item.status) }}</a></td>
                    <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                    <td><a class="layui-btn layui-btn-sm" href="{{ refund_show_url }}">详情</a></td>
                </tr>
            {% endfor %}
        </table>
        <br>
    {% endif %}

    {% if trades.count() > 0 %}
        <fieldset class="layui-elem-field layui-field-title">
            <legend>交易信息</legend>
        </fieldset>
        <table class="layui-table kg-table">
            <tr>
                <th>交易序号</th>
                <th>交易金额</th>
                <th>交易平台</th>
                <th>交易状态</th>
                <th>创建时间</th>
                <th></th>
            </tr>
            {% for item in trades %}
                {% set trade_sh_url = url({'for':'admin.trade.status_history','id':item.id}) %}
                {% set trade_show_url = url({'for':'admin.trade.show','id':item.id}) %}
                <tr>
                    <td>{{ item.sn }}</td>
                    <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                    <td>{{ channel_type(item.channel) }}</td>
                    <td><a class="kg-status-history" href="javascript:" title="查看历史状态" data-url="{{ trade_sh_url }}">{{ trade_status(item.status) }}</a></td>
                    <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                    <td><a class="layui-btn layui-btn-sm" href="{{ trade_show_url }}">详情</a></td>
                </tr>
            {% endfor %}
        </table>
        <br>
    {% endif %}

    {{ partial('order/user_info') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/status-history.js') }}

{% endblock %}