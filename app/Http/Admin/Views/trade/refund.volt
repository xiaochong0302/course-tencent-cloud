{% extends 'templates/layer.volt' %}

{% block content %}

    {%- macro item_info(confirm) %}
        {% if confirm.item_type == 1 %}
            {% set course = confirm.item_info.course %}
            {% set expiry_flag = course.refund_expiry_time < time() ? '（已过期）' : '' %}
            <div class="kg-order-item">
                <p>课程名称：{{ course.title }}</p>
                <p>退款期限：{{ date('Y-m-d H:i',course.refund_expiry_time) }} {{ expiry_flag }}</p>
                <p>退款金额：{{ '￥%0.2f'|format(course.refund_amount) }}，退款比例：{{ 100 * course.refund_rate }}%</p>
            </div>
        {% elseif confirm.item_type == 2 %}
            {% set courses = confirm.item_info.courses %}
            {% for course in courses %}
                {% set expiry_flag = course.refund_expiry_time < time() ? '（已过期）' : '' %}
                <div class="kg-order-item">
                    <p>课程名称：{{ course.title }}</p>
                    <p>退款期限：{{ date('Y-m-d H:i',course.refund_expiry_time) }} {{ expiry_flag }}</p>
                    <p>退款金额：{{ '￥%0.2f'|format(course.refund_amount) }}，退款比例：{{ 100 * course.refund_rate }}%</p>
                </div>
            {% endfor %}
        {% elseif confirm.item_type == 3 %}
            {% set vip = confirm.item_info.vip %}
            <div class="kg-order-item">
                <p>服务名称：会员服务（{{ vip.title }}）</p>
                <p>会员期限：{{ date('Y-m-d H:i',vip.expiry_time) }}</p>
                <p>退款金额：{{ '￥%0.2f'|format(confirm.refund_amount) }}</p>
            </div>
        {% elseif confirm.item_type == 4 %}
            {% set exam_paper = confirm.item_info.exam_paper %}
            {% set expiry_flag = exam_paper.refund_expiry_time < time() ? '（已过期）' : '' %}
            <div class="order-item">
                <p>试卷名称：{{ exam_paper.title }}</p>
                <p>退款期限：{{ date('Y-m-d H:i',exam_paper.refund_expiry_time) }} {{ expiry_flag }}</p>
                <p>退款金额：{{ '￥%0.2f'|format(confirm.refund_amount) }}</p>
            </div>
        {% elseif confirm.item_type == 5 %}
            {% set article = confirm.item_info.article %}
            <div class="kg-order-item">
                <p>专栏名称：{{ article.title }}</p>
                <p>退款金额：{{ '￥%0.2f'|format(confirm.refund_amount) }}</p>
            </div>
        {% elseif confirm.item_type == 10 %}
            {% set virtual_recharge = confirm.item_info.virtual_recharge %}
            <div class="kg-order-item">
                <p>微信虚拟充值</p>
            </div>
        {% elseif confirm.item_type == 98 %}
            <div class="kg-order-item">
                <p>提现账户验证</p>
            </div>
        {% elseif confirm.item_type == 99 %}
            <div class="kg-order-item">
                <p>支付测试</p>
            </div>
        {% endif %}
    {%- endmacro %}

    <table class="layui-table kg-table">
        <tr>
            <td>退款项目</td>
            <td>支付金额</td>
            <td>手续费用</td>
            <td>退款金额</td>
        </tr>
        <tr>
            <td>{{ item_info(confirm) }}</td>
            <td>{{ '￥%0.2f'|format(trade.amount) }}</td>
            <td>
                <span id="service-fee-tips">{{ '￥%0.2f'|format(confirm.service_fee) }}</span>
            </td>
            <td>
                <span id="refund-amount-tips">{{ '￥%0.2f'|format(confirm.refund_amount) }}</span>
            </td>
        </tr>
    </table>
    {% if confirm.item_type == 10 %}
        <br>
        <div class="kg-center red">注意：虚拟充值现金退款不会自动取消已开通课程等操作，需要人工对相关虚拟代币订单进行退款处理。</div>
    {% endif %}
    <br>
    <form class="layui-form" method="post" action="{{ url({'for':'admin.trade.refund','id':trade.id}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">手续费率</label>
            <div class="layui-input-block">
                <div id="slider-1" style="padding-top:15px;"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">退款比例</label>
            <div class="layui-input-block">
                <div id="slider-2" style="padding-top:15px;"></div>
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
                <input type="hidden" name="service_rate" value="{{ confirm.service_rate }}">
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

            var $tradeAmount = $('input[name=trade_amount]');
            var $refundAmount = $('input[name=refund_amount]');
            var $serviceFee = $('input[name=service_fee]');
            var $serviceRate = $('input[name=service_rate]');
            var $refundAmountTips = $('#refund-amount-tips');
            var $serviceFeeTips = $('#service-fee-tips');
            var tradeAmount = parseFloat($tradeAmount.val());
            var refundAmount = parseFloat($refundAmount.val());
            var serviceFee = parseFloat($serviceFee.val());
            var serviceRate = parseInt($serviceRate.val());
            var refundRate = Math.floor(refundAmount / (tradeAmount - serviceFee) * 100);

            slider.render({
                elem: '#slider-1',
                value: serviceRate,
                max: 30,
                change: function (ratio) {
                    serviceRate = ratio;
                    calculate();
                }
            });

            slider.render({
                elem: '#slider-2',
                value: refundRate,
                change: function (ratio) {
                    refundRate = ratio
                    calculate();
                }
            });

            var calculate = function () {
                serviceFee = tradeAmount * serviceRate / 100;
                refundAmount = tradeAmount * (100 - serviceRate) * refundRate / 100 / 100;
                $refundAmount.val(refundAmount.toFixed(2));
                $serviceFee.val(serviceFee.toFixed(2));
                $refundAmountTips.text('￥' + refundAmount.toFixed(2));
                $serviceFeeTips.text('￥' + serviceFee.toFixed(2));
            }

        });

    </script>

{% endblock %}
