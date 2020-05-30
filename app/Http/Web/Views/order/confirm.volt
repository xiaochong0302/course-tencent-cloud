{% extends 'templates/base.volt' %}

{% block content %}

    {%- macro cart_course_card(course) %}
        {% set course_url = url({'for':'web.course.show','id':course.id}) %}
        <div class="cover">
            <img src="{{ course.cover }}!cover_270" alt="course.title|e">
        </div>
        <div class="info">
            <p><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
            <p>
                市场价格 <span class="price">{{ course.market_price }}</span>
                会员价格 <span class="price">{{ course.vip_price }}</span>
            </p>
            <p>
                学习期限 <span class="expiry">{{ course.study_expiry }}</span>
                退款期限 <span class="expiry">{{ course.refund_expiry }}</span>
            </p>
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

    <div class="cart module">
        {% if confirm.item_type == 'course' %}
            {% set course = confirm.item_info.course %}
            {{ cart_course_card(course) }}
        {% elseif confirm.item_type == 'package' %}
            {% set package = confirm.item_info.package %}
            {% for course in package.courses %}
                {{ cart_course_card(course) }}
            {% endfor %}
        {% elseif confirm.item_type == 'reward' %}
            {% set reward = confirm.item_info.reward %}
            {{ cart_reward_card(reward) }}
        {% elseif confirm.item_type == 'vip' %}
            {% set vip = confirm.item_info.vip %}
            {{ cart_vip_card(vip) }}
        {% endif %}
    </div>

{% endblock %}