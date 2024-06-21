{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {%- macro cart_course_card(course) %}
        {% set course_url = url({'for':'home.course.show','id':course.id}) %}
        <div class="cart-item-card">
            <div class="cover">
                <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
            </div>
            <div class="info">
                <p><a href="{{ course_url }}" target="_blank">{{ course.title }}</a></p>
                <p>
                    <span class="key">市场价格</span>
                    <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
                    <span class="key">会员价格</span>
                    <span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
                </p>
                {% if course.model in [1,2,3] %}
                    <p>
                        <span class="key">学习期限</span>
                        <span class="value">{{ course.study_expiry }} 个月</span>
                        {% if course.refund_expiry > 0 %}
                            <span class="key">退款期限</span>
                            <span class="value">{{ course.refund_expiry }} 天</span>
                        {% else %}
                            <span class="key">退款期限</span>
                            <span class="value">不支持</span>
                        {% endif %}
                    </p>
                {% elseif course.model == 4 %}
                    <p>
                        <span class="key">上课时间</span>
                        <span class="value">{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span>
                        <span class="key">上课地点</span>
                        <span class="value">{{ course.attrs.location }}</span>
                    </p>
                {% endif %}
            </div>
        </div>
    {%- endmacro %}

    {%- macro cart_vip_card(item_info) %}
        {% set vip = item_info.vip %}
        <div class="cart-item-card">
            <div class="cover">
                <img src="{{ vip.cover }}!cover_270" alt="{{ vip.title }}">
            </div>
            <div class="info">
                <p>会员服务</p>
                <p>
                    <span class="key">价格</span>
                    <span class="price">{{ '￥%0.2f'|format(vip.price) }}</span>
                </p>
                <p>
                    <span class="key">期限</span>
                    <span class="expiry">{{ vip.expiry }} 个月</span>
                </p>
            </div>
        </div>
    {%- endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>确认订单</cite></a>
    </div>

    <div class="cart-item-list wrap">
        {% if confirm.item_type == 1 %}
            {% set course = confirm.item_info.course %}
            {{ cart_course_card(course) }}
        {% elseif confirm.item_type == 2 %}
            {% set package = confirm.item_info.package %}
            {% for course in package.courses %}
                {{ cart_course_card(course) }}
            {% endfor %}
        {% elseif confirm.item_type == 4 %}
            {{ cart_vip_card(confirm.item_info) }}
        {% endif %}
    </div>

    <div class="cart-stats wrap">
        <div class="info">
            商品总价：<span class="amount">{{ '￥%0.2f'|format(confirm.total_amount) }}</span>
            优惠金额：<span class="amount">{{ '￥%0.2f'|format(confirm.discount_amount) }}</span>
            支付金额：<span class="amount pay-amount">{{ '￥%0.2f'|format(confirm.pay_amount) }}</span>
        </div>
        <form class="layui-form cart-form" method="post" action="{{ url({'for':'home.order.create'}) }}">
            <button class="layui-btn layui-bg-red order-btn" lay-submit="true" lay-filter="go">提交订单</button>
            <input type="hidden" name="item_id" value="{{ confirm.item_id }}">
            <input type="hidden" name="item_type" value="{{ confirm.item_type }}">
        </form>
    </div>

{% endblock %}