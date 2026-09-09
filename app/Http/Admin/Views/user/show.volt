{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set onlines_url = url({'for':'admin.user.onlines','id':user.id}) %}
    {% set study_courses_url = url({'for':'admin.user.study_courses','id':user.id}) %}
    {% set orders_url = url({'for':'admin.user.orders','id':user.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>用户详情</legend>
    </fieldset>

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本信息</li>
            <li>在学课程</li>
            <li>我的订单</li>
            <li>在线记录</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('user/show_basic') }}
            </div>
            <div class="layui-tabs-item">
                <div id="study-course-list" data-url="{{ study_courses_url }}"></div>
            </div>
            <div class="layui-tabs-item">
                <div id="order-list" data-url="{{ orders_url }}"></div>
            </div>
            <div class="layui-tabs-item">
                <div id="online-list" data-url="{{ onlines_url }}"></div>
            </div>
        </div>
    </div>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'helper'], function () {

            var $ = layui.jquery;
            var helper = layui.helper;

            var $courseList = $('#study-course-list');
            helper.ajaxLoadHtml($courseList.data('url'), $courseList.attr('id'));

            var $orderList = $('#order-list');
            helper.ajaxLoadHtml($orderList.data('url'), $orderList.attr('id'));

            var $onlineList = $('#online-list');
            helper.ajaxLoadHtml($onlineList.data('url'), $onlineList.attr('id'));

        });

    </script>

{% endblock %}
