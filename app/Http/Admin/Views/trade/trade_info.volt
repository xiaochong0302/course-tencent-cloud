{% set trade_status_url = url({'for':'admin.trade.statuses','id':trade.id}) %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>交易信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>交易序号</th>
        <th>交易金额</th>
        <th>交易平台</th>
        <th>交易状态</th>
        <th>历史状态</th>
        <th>创建时间</th>
    </tr>
    <tr>
        <td>{{ trade.sn }}</td>
        <td>{{ '￥%0.2f'|format(trade.amount) }}</td>
        <td>{{ channel_type(trade.channel) }}</td>
        <td>{{ trade_status(trade.status) }}</td>
        <td>
            <button class="layui-btn layui-btn-xs layui-bg-green trade-status" data-url="{{ trade_status_url }}">详情</button>
        </td>
        <td>{{ date('Y-m-d H:i:s',trade.create_time) }}</td>
    </tr>
</table>

<script>
    layui.use(['jquery', 'layer'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        $('.trade-status').on('click', function () {
            layer.open({
                type: 2,
                title: '历史状态',
                content: $(this).data('url'),
                area: ['640px', '320px']
            });
        });
    });
</script>