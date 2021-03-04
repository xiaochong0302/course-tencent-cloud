{% set trade_history_url = url({'for':'admin.trade.status_history','id':trade.id}) %}

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
    </tr>
    <tr>
        <td>{{ trade.sn }}</td>
        <td>{{ '￥%0.2f'|format(trade.amount) }}</td>
        <td>{{ channel_type(trade.channel) }}</td>
        <td><a class="kg-status-history" href="javascript:" title="查看历史状态" data-url="{{ trade_history_url }}">{{ trade_status(trade.status) }}</a></td>
        <td>{{ date('Y-m-d H:i:s',trade.create_time) }}</td>
    </tr>
</table>