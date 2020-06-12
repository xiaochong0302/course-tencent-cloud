{{ partial('order/macro') }}
{{ partial('trade/macro') }}
{{ partial('refund/macro') }}

{{ partial('order/order_info') }}

<br>

<div class="kg-text-center">
    <button class="kg-back layui-btn layui-bg-gray">返回上页</button>
</div>

{% if refunds.count() > 0 %}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>退款信息</legend>
    </fieldset>
    <table class="kg-table layui-table">
        <tr>
            <th>退款序号</th>
            <th>退款金额</th>
            <th>退款原因</th>
            <th>退款状态</th>
            <th>创建时间</th>
            <th></th>
        </tr>
        {% for item in refunds %}
            <tr>
                <td>{{ item.sn }}</td>
                <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                <td><a href="#" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                <td>{{ refund_status(item) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td><a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.refund.show','id':item.id}) }}">详情</a></td>
            </tr>
        {% endfor %}
    </table>
    <br>
{% endif %}

{% if trades.count() > 0 %}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>交易信息</legend>
    </fieldset>
    <table class="kg-table layui-table">
        <tr>
            <th>交易序号</th>
            <th>交易金额</th>
            <th>交易平台</th>
            <th>交易状态</th>
            <th>创建时间</th>
            <th></th>
        </tr>
        {% for item in trades %}
            <tr>
                <td>{{ item.sn }}</td>
                <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                <td>{{ channel_type(item.channel) }}</td>
                <td>{{ trade_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td><a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.trade.show','id':item.id}) }}">详情</a></td>
            </tr>
        {% endfor %}
    </table>
    <br>
{% endif %}

{{ partial('order/user_info') }}