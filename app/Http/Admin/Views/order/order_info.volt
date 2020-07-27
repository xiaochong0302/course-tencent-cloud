{% set order_status_url = url({'for':'admin.order.statuses','id':order.id}) %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>订单信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <td colspan="6">订单编号：{{ order.sn }}</td>
    <tr>
    <tr>
        <td>商品信息</td>
        <td>订单金额</td>
        <td>订单类型</td>
        <td>订单状态</td>
        <td>历史状态</td>
        <td>创建时间</td>
    </tr>
    <tr>
        <td>{{ item_info(order) }}</td>
        <td>{{ '￥%0.2f'|format(order.amount) }}</td>
        <td>{{ item_type(order.item_type) }}</td>
        <td>{{ order_status(order.status) }}</td>
        <td>
            <button class="layui-btn layui-btn-xs layui-bg-green order-status" data-url="{{ order_status_url }}">详情</button>
        </td>
        <td>{{ date('Y-m-d H:i:s',order.create_time) }}</td>
    </tr>
</table>

<script>
    layui.use(['jquery', 'layer'], function () {
        var $ = layui.jquery;
        var layer = layui.layer;
        $('.order-status').on('click', function () {
            layer.open({
                type: 2,
                title: '历史状态',
                content: $(this).data('url'),
                area: ['640px', '320px']
            });
        });
    });
</script>