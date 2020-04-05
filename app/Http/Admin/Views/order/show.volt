{{ partial('order/macro') }}
{{ partial('trade/macro') }}
{{ partial('refund/macro') }}

<fieldset class="layui-elem-field layui-field-title">
    <legend>订单信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <td colspan="10">订单编号：{{ order.sn }}
        <td>
    <tr>
    <tr>
        <td>商品信息</td>
        <td>订单金额</td>
        <td>订单类型</td>
        <td>订单状态</td>
        <td>创建时间</td>
    </tr>
    <tr>
        <td>{{ item_info(order) }}</td>
        <td>￥{{ order.amount }}</td>
        <td>{{ item_type(order.item_type) }}</span></td>
        <td>{{ order_status(order.status) }}</td>
        <td>{{ date('Y-m-d H:i',order.create_time) }}</td>
    </tr>
</table>

<br>

<div style="text-align: center">
    {% if order.status == 'pending' %}
        <button class="kg-close layui-btn layui-bg-green" order-id="{{ order.id }}">关闭订单</button>
    {% endif %}
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
        </tr>
        {% for item in refunds %}
            <tr>
                <td>{{ item.sn }}</td>
                <td>￥{{ item.amount }}</td>
                <td><a href="#" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                <td>{{ refund_status(item) }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
            </tr>
        {% endfor %}
    </table>
{% endif %}

<br>

{% if trades.count() > 0 %}
    <fieldset class="layui-elem-field layui-field-title">
        <legend>交易信息</legend>
    </fieldset>
    <table class="kg-table layui-table">
        <tr>
            <th>交易号</th>
            <th>交易金额</th>
            <th>交易平台</th>
            <th>交易状态</th>
            <th>创建时间</th>
        </tr>
        {% for item in trades %}
            <tr>
                <td>{{ item.sn }}</td>
                <td>￥{{ item.amount }}</td>
                <td>{{ channel_type(item.channel) }}</td>
                <td>{{ trade_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
            </tr>
        {% endfor %}
    </table>
{% endif %}

<br>

<fieldset class="layui-elem-field layui-field-title">
    <legend>买家信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>编号</th>
        <th>昵称</th>
        <th>邮箱</th>
        <th>手机</th>
    </tr>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{% if account.phone %}{{ account.phone }}{% else %}N/A{% endif %}</td>
        <td>{% if account.email %}{{ account.email }}{% else %}N/A{% endif %}</td>
    </tr>
</table>

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;

        $('.kg-close').on('click', function () {
            var orderId = $(this).attr('order-id');
            var tips = '确定要关闭订单吗？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: '/admin/order/' + orderId + '/close',
                    success: function (res) {
                        layer.msg(res.msg, {icon: 1});
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function (xhr) {
                        var json = JSON.parse(xhr.responseText);
                        layer.msg(json.msg, {icon: 2});
                    }
                });
            }, function () {

            });
        });

    });

</script>