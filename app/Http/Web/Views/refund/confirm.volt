{% extends 'templates/full.volt' %}

{% block content %}

    {%- macro item_info(confirm) %}
        {% if confirm.item_type == 'course' %}
            {% set course = confirm.item_info.course %}
            {% set course_url = url({'for':'web.course.show','id':course.id}) %}
            <div class="order-item">
                <p>课程名称：<a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>退款期限：{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}</p>
                <p>退款金额：<span class="price">{{ '￥%0.2f'|format(course.refund_amount) }}</span>退款比例：<span class="price">{{ 100 * course.refund_percent }}%</span></p>
            </div>
        {% elseif confirm.item_type == 'package' %}
            {% set courses = confirm.item_info.courses %}
            {% for course in courses %}
                {% set course_url = url({'for':'web.course.show','id':course.id}) %}
                <div class="order-item">
                    <p>课程名称：<a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                    <p>退款期限：{{ date('Y-m-d H:i:s',course.refund_expiry_time) }}</p>
                    <p>退款金额：<span class="price">{{ '￥%0.2f'|format(course.refund_amount) }}</span>退款比例：<span class="price">{{ 100 * course.refund_percent }}%</span></p>
                </div>
            {% endfor %}
        {% endif %}
    {%- endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a href="{{ url({'for':'web.my.orders'}) }}">我的订单</a>
        <a><cite>确认退款</cite></a>
        <a><cite>{{ order.subject }}</cite></a>
    </div>

    <div class="container">
        <table class="layui-table kg-table order-table" lay-size="lg">
            <tr>
                <td>订单编号：{{ order.sn }}</td>
                <td>订单金额</td>
                <td>退款金额</td>
            </tr>
            <tr>
                <td>{{ item_info(confirm) }}</td>
                <td><span class="price">{{ '￥%0.2f'|format(order.amount) }}</span></td>
                <td><span class="price">{{ '￥%0.2f'|format(confirm.refund_amount) }}</span></td>
            </tr>
        </table>
        <br>
        <form class="layui-form layui-form-pane" method="post" action="{{ url({'for':'web.refund.create'}) }}">
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">退款原因</label>
                <div class="layui-input-block">
                    <textarea class="layui-textarea" name="apply_note" lay-verify="required"></textarea>
                </div>
            </div>
            <br>
            <div class="layui-form-item text-center">
                <button type="button" class="kg-back layui-btn layui-bg-gray">返回上页</button>
                <button class="layui-btn layui-bg-blue" lay-submit="true" lay-filter="go">提交申请</button>
                <input type="hidden" name="order_sn" value="{{ order.sn }}">
            </div>
        </form>
    </div>

{% endblock %}