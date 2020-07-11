{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    {%- macro cart_course_card(course, user) %}
        {% set course_url = url({'for':'web.course.show','id':course.id}) %}
        <div class="cart-course-card clearfix">
            <div class="cover">
                <img src="{{ course.cover }}!cover_270" alt="course.title|e">
            </div>
            <div class="info">
                <p><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>
                    市场价格 <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
                    会员价格 <span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
                </p>
                <p>
                    学习期限 <span class="expiry">{{ course.study_expiry }}个月</span>
                    退款期限 <span class="expiry">{{ course.refund_expiry }}天</span>
                </p>
            </div>
        </div>
    {%- endmacro %}

    {%- macro cart_reward_card(item_info) %}
        {% set course = item_info.course %}
        {% set reward = item_info.reward %}
        {% set course_url = url({'for':'web.course.show','id':course.id}) %}
        <div class="cart-course-card clearfix">
            <div class="cover">
                <img src="{{ course.cover }}!cover_270" alt="course.title|e">
            </div>
            <div class="info">
                <p><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>赞赏金额 <span class="price">{{ '￥%0.2f'|format(reward.price) }}</span></p>
                <p>
                    难度 <span>{{ level_info(course.level) }}</span>
                    课时 <span>{{ course.lesson_count }}</span>
                    学员 <span>{{ course.user_count }}</span>
                </p>
            </div>
        </div>
    {%- endmacro %}

    {%- macro cart_vip_card(item_info) %}
        {% set vip = item_info.vip %}
        <div class="cart-course-card clearfix">
            <div class="cover">
                <img src="/static/web/img/vip_cover.png" alt="会员服务">
            </div>
            <div class="info">
                <p>会员服务</p>
                <p>价格 <span class="price">{{ '￥%0.2f'|format(vip.price) }}</span></p>
                <p>期限 <span class="expiry">{{ vip.expiry }}个月</span></p>
            </div>
        </div>
    {%- endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>确认订单</cite></a>
    </div>

    <div class="cart-item-list wrap">
        {% if confirm.item_type == 'course' %}
            {% set course = confirm.item_info.course %}
            {{ cart_course_card(course, auth_user) }}
        {% elseif confirm.item_type == 'package' %}
            {% set package = confirm.item_info.package %}
            {% for course in package.courses %}
                {{ cart_course_card(course, auth_user) }}
            {% endfor %}
        {% elseif confirm.item_type == 'reward' %}
            {{ cart_reward_card(confirm.item_info) }}
        {% elseif confirm.item_type == 'vip' %}
            {{ cart_vip_card(confirm.item_info) }}
        {% endif %}
    </div>

    <div class="cart-stats wrap clearfix">
        <div class="info">
            商品总价：<span class="amount">{{ '￥%0.2f'|format(confirm.total_amount) }}</span>
            优惠金额：<span class="amount">{{ '￥%0.2f'|format(confirm.discount_amount) }}</span>
            支付金额：<span class="amount pay-amount">{{ '￥%0.2f'|format(confirm.pay_amount) }}</span>
        </div>
        <form class="layui-form cart-form" method="post" action="{{ url({'for':'web.order.create'}) }}">
            <button class="layui-btn layui-bg-red order-btn" lay-submit="true" lay-filter="go">提交订单</button>
            <input type="hidden" name="item_id" value="{{ confirm.item_id }}">
            <input type="hidden" name="item_type" value="{{ confirm.item_type }}">
        </form>
    </div>

{% endblock %}