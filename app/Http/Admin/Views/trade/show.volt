{{ partial('order/macro') }}
{{ partial('trade/macro') }}
{{ partial('refund/macro') }}

{{ partial('trade/trade_info') }}

<br>

{% set trade_refund_url = url({'for':'admin.trade.refund','id':trade.id}) %}

<div class="kg-text-center">
    {% if trade.status == 'finished' %}
        <button class="kg-refund layui-btn layui-bg-green" data-url="{{ trade_refund_url }}">申请退款</button>
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
            {% set refund_sh_url = url({'for':'admin.refund.status_history','id':item.id}) %}
            {% set refund_show_url = url({'for':'admin.refund.show','id':item.id}) %}
            <tr>
                <td>{{ item.sn }}</td>
                <td>{{ '￥%0.2f'|format(item.amount) }}</td>
                <td><a href="javascript:" title="{{ item.apply_note }}">{{ substr(item.apply_note,0,15) }}</td>
                <td><a class="kg-status-history" href="javascript:" title="查看历史状态" data-url="{{ refund_sh_url }}">{{ refund_status(item) }}</a></td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td><a class="layui-btn layui-btn-sm" href="{{ refund_show_url }}">详情</a></td>
            </tr>
        {% endfor %}
    </table>
{% endif %}

<br>

{{ partial('order/order_info') }}

<br>

{{ partial('order/user_info') }}

{{ js_include('admin/js/status-history.js') }}

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