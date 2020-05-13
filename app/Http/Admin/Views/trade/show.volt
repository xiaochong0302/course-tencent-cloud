{{ partial('order/macro') }}
{{ partial('trade/macro') }}
{{ partial('refund/macro') }}

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
        <td>￥{{ trade.amount }}</td>
        <td>{{ channel_type(trade.channel) }}</td>
        <td>{{ trade_status(trade.status) }}</td>
        <td>{{ date('Y-m-d H:i',trade.create_time) }}</td>
    </tr>
</table>

<br>

<div style="text-align: center">
    {% if trade.status == 'pending' %}
        <button class="kg-close layui-btn layui-bg-green" data-url="{{ url({'for':'admin.trade.close','id':trade.id}) }}">关闭交易</button>
    {% endif %}
    {% if trade.status == 'finished' %}
        <button class="kg-refund layui-btn layui-bg-green" data-url="{{ url({'for':'admin.trade.refund','id':trade.id}) }}">申请退款</button>
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

<fieldset class="layui-elem-field layui-field-title">
    <legend>订单信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>订单序号</th>
        <th>商品名称</th>
        <th>订单金额</th>
        <th>订单状态</th>
        <th>创建时间</th>
    </tr>
    <tr>
        <td>{{ order.sn }}</td>
        <td>{{ order.subject }}</td>
        <td>￥{{ order.amount }}</td>
        <td>{{ order_status(order.status) }}</td>
        <td>{{ date('Y-m-d H:i',order.create_time) }}</td>
    </tr>
</table>

<br>

<fieldset class="layui-elem-field layui-field-title">
    <legend>用户信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>编号</th>
        <th>昵称</th>
        <th>手机</th>
        <th>邮箱</th>
    </tr>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{% if account.phone %} {{ account.phone }} {% else %} 未知 {% endif %}</td>
        <td>{% if account.email %} {{ account.email }} {% else %} 未知 {% endif %}</td>
    </tr>
</table>

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;

        $('.kg-close').on('click', function () {
            var url = $(this).attr('data-url');
            var tips = '确定要关闭交易吗？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: url,
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

        $('.kg-refund').on('click', function () {
            var url = $(this).attr('data-url');
            var tips = '确定要申请退款吗？';
            layer.confirm(tips, function () {
                $.ajax({
                    type: 'POST',
                    url: url,
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