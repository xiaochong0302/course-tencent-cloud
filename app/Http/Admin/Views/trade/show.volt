{{ partial('order/macro') }}
{{ partial('trade/macro') }}
{{ partial('refund/macro') }}

{{ partial('trade/trade_info') }}

<br>

<div class="kg-text-center">
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
            <th></th>
        </tr>
        {% for item in refunds %}
            <tr>
                <td>{{ item.sn }}</td>
                <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                <td><a href="javascript:" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                <td>{{ refund_status(item) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td><a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.refund.show','id':item.id}) }}">详情</a></td>
            </tr>
        {% endfor %}
    </table>
{% endif %}

<br>

{{ partial('order/order_info') }}

<br>

{{ partial('order/user_info') }}

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;

        $('.kg-refund').on('click', function () {
            var url = $(this).data('url');
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