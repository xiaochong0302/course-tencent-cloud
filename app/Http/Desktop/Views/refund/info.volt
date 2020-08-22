{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/refund') }}

    {% set cancel_url = url({'for':'desktop.refund.cancel'}) %}

    <table class="layui-table order-table" lay-size="lg">
        <tr>
            <td colspan="2">
                订单金额：<span class="price">{{ '￥%0.2f'|format(refund.order.amount) }}</span>
                退款金额：<span class="price">{{ '￥%0.2f'|format(refund.amount) }}</span>
                退款状态：<span class="status">{{ refund_status(refund.status) }}</span>
            </td>
        </tr>
        <tr>
            <td>{{ refund.subject }}</td>
            <td>{{ status_history(refund.status_history) }}</td>
        </tr>
    </table>
    <br>
    <div class="center">
        {% if refund.status == 'approved' %}
            <button class="kg-refund layui-btn" data-sn="{{ refund.sn }}" data-url="{{ cancel_url }}">取消退款</button>
        {% endif %}
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;
            var index = parent.layer.getFrameIndex(window.name);

            parent.layer.iframeAuto(index);

            $('.kg-refund').on('click', function () {
                layer.confirm('确定要取消退款吗？', function () {
                    $.ajax({
                        type: 'POST',
                        url: $(this).data('url'),
                        data: {sn: $(this).data('sn')},
                        success: function (res) {
                            layer.msg(res.msg, {icon: 1});
                            setTimeout(function () {
                                parent.window.location.href = '/my/refunds';
                            }, 1500);
                        }
                    });
                });
            });
        });
    </script>

{% endblock %}