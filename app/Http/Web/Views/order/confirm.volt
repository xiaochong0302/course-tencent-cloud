{% extends 'templates/full.volt' %}

{% block content %}

    {%- macro cart_course_card(course, user) %}
        {% set course_url = url({'for':'web.course.show','id':course.id}) %}
        <div class="cart-course-card clearfix">
            <div class="cover">
                <img src="{{ course.cover }}!cover_270" alt="course.title|e">
            </div>
            <div class="info">
                <p><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>
                    市场价格 <span class="price">￥{{ course.market_price }}</span>
                    会员价格 <span class="price">￥{{ course.vip_price }}</span>
                </p>
                <p>
                    学习期限 <span class="expiry">{{ course.study_expiry }}个月</span>
                    退款期限 <span class="expiry">{{ course.refund_expiry }}天</span>
                </p>
            </div>
        </div>
    {%- endmacro %}

    {%- macro reward_course_card(reward) %}
        <div>I am reward</div>
    {%- endmacro %}

    {%- macro vip_course_card(vip) %}
        <div>I am vip</div>
    {%- endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>确认订单</cite></a>
    </div>

    <div class="cart-item-list container">
        {% if confirm.item_type == 'course' %}
            {% set course = confirm.item_info.course %}
            {{ cart_course_card(course, auth_user) }}
        {% elseif confirm.item_type == 'package' %}
            {% set package = confirm.item_info.package %}
            {% for course in package.courses %}
                {{ cart_course_card(course, auth_user) }}
            {% endfor %}
        {% elseif confirm.item_type == 'reward' %}
            {% set reward = confirm.item_info.reward %}
            {{ cart_reward_card(reward) }}
        {% elseif confirm.item_type == 'vip' %}
            {% set vip = confirm.item_info.vip %}
            {{ cart_vip_card(vip) }}
        {% endif %}
    </div>

    <div class="cart-stats container clearfix">
        <div class="info">
            商品总价：<span class="amount">￥{{ confirm.total_amount }}</span>
            优惠金额：<span class="amount">￥{{ confirm.discount_amount }}</span>
            支付金额：<span class="amount pay-amount">￥{{ confirm.pay_amount }}</span>
        </div>
        <form class="layui-form cart-form" method="post" action="{{ url({'for':'web.order.create'}) }}">
            <button class="layui-btn layui-bg-red order-btn" lay-submit="true" lay-filter="go">提交订单</button>
            <input type="hidden" name="item_id" value="{{ confirm.item_id }}">
            <input type="hidden" name="item_type" value="{{ confirm.item_type }}">
        </form>
    </div>

{% endblock %}