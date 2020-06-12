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
    </tr>
    <tr>
        <td>{{ trade.sn }}</td>
        <td>{{ '￥%0.2f'|format(trade.amount) }}</td>
        <td>{{ channel_type(trade.channel) }}</td>
        <td>{{ trade_status(trade.status) }}</td>
        <td>{{ date('Y-m-d H:i:s',trade.create_time) }}</td>
    </tr>
</table>