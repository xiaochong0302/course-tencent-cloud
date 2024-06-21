{% extends 'templates/layer.volt' %}

{% block content %}

    {%- macro item_info(confirm) %}
        {% if confirm.item_type == 1 %}
            {% set course = confirm.item_info.course %}
            {% set expiry_flag = course.refund_expiry_time < time() ? '（已过期）' : '' %}
            <div class="kg-order-item">
                <p>课程名称：{{ course.title }}</p>
                <p>退款期限：{{ date('Y-m-d H:i:s',course.refund_expiry_time) }} {{ expiry_flag }}</p>
                <p>退款金额：{{ '￥%0.2f'|format(course.refund_amount) }}，退款比例：{{ 100 * course.refund_percent }}%</p>
            </div>
        {% elseif confirm.item_type == 2 %}
            {% set courses = confirm.item_info.courses %}
            {% for course in courses %}
                {% set expiry_flag = course.refund_expiry_time < time() ? '（已过期）' : '' %}
                <div class="kg-order-item">
                    <p>课程名称：{{ course.title }}</p>
                    <p>退款期限：{{ date('Y-m-d H:i:s',course.refund_expiry_time) }} {{ expiry_flag }}</p>
                    <p>退款金额：{{ '￥%0.2f'|format(course.refund_amount) }}，退款比例：{{ 100 * course.refund_percent }}%</p>
                </div>
            {% endfor %}
        {% elseif confirm.item_type == 4 %}
            {% set vip = confirm.item_info.vip %}
            <div class="kg-order-item">
                <p>服务名称：会员服务（{{ vip.title }}）</p>
                <p>会员期限：{{ date('Y-m-d H:i:s',vip.expiry_time) }}</p>
            </div>
        {% elseif confirm.item_type == 99 %}
            <div class="kg-order-item">
                <p>服务名称：支付测试</p>
            </div>
        {% endif %}
    {%- endmacro %}

    <table class="layui-table kg-table">
        <tr>
            <td>退款项目</td>
            <td>支付金额</td>
            <td>手续费（{{ confirm.service_rate }}%）</td>
            <td>退款金额</td>
        </tr>
        <tr>
            <td>{{ item_info(confirm) }}</td>
            <td>{{ '￥%0.2f'|format(trade.amount) }}</td>
            <td>{{ '￥%0.2f'|format(confirm.service_fee) }}</td>
            <td>
                <div id="refund-amount-tips">{{ '￥%0.2f'|format(confirm.refund_amount) }}</div>
            </td>
        </tr>
    </table>
    <br>
    <form class="layui-form" method="post" action="{{ url({'for':'admin.trade.refund','id':trade.id}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">退款比例</label>
            <div class="layui-input-block">
                <div id="slider" style="padding-top:15px;"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">退款原因</label>
            <div class="layui-input-block">
                <input class="layui-input" name="apply_note" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                <input type="hidden" name="trade_amount" value="{{ trade.amount }}">
                <input type="hidden" name="refund_amount" value="{{ confirm.refund_amount }}">
                <input type="hidden" name="service_fee" value="{{ confirm.service_fee }}">
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'slider', 'layer'], function () {

            var $ = layui.jquery;
            var slider = layui.slider;
            var index = parent.layer.getFrameIndex(window.name);

            parent.layer.iframeAuto(index);

            slider.render({
                elem: '#slider',
                value: {{ confirm.service_rate }},
                change: function (ratio) {
                    var $tradeAmount = $('input[name=trade_amount]');
                    var $refundAmount = $('input[name=refund_amount]');
                    var $serviceFee = $('input[name=service_fee]');
                    var $refundAmountTips = $('#refund-amount-tips');
                    var refundAmount = (parseFloat($tradeAmount.val()) - parseFloat($serviceFee.val())) * ratio / 100;
                    $refundAmount.val(refundAmount.toFixed(2));
                    $refundAmountTips.text('￥' + refundAmount.toFixed(2));
                }
            });

        });

    </script>

{% endblock %}