{% extends 'templates/main.volt' %}

{% block content %}

    <div class="vip-header">会员权益</div>

    <div class="vip-priv-list wrap">
        <button class="layui-btn layui-bg-blue">好课畅学</button>
        <button class="layui-btn layui-bg-blue">会员折扣</button>
        <button class="layui-btn layui-bg-blue">高清视频</button>
        <button class="layui-btn layui-bg-blue">广告免疫</button>
        <button class="layui-btn layui-bg-blue">会员标识</button>
        <button class="layui-btn layui-bg-blue">优先服务</button>
    </div>

    <div class="vip-header">开通会员</div>

    <div class="vip-option-list">
        <div class="layui-row layui-col-space20">
            {% for option in vip_options %}
                {% set order_url = url({'for':'home.order.confirm'},{'item_id':option.id,'item_type':4}) %}
                <div class="layui-col-md3">
                    <div class="vip-option-card">
                        <div class="title">{{ option.title }}</div>
                        <div class="price">￥{{ option.price }}</div>
                        <div class="order">
                            <button class="layui-btn layui-btn-sm layui-bg-red btn-order" data-url="{{ order_url }}">立即开通</button>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>

    {% set free_courses_url = url({'for':'home.vip.courses'},{'type':'free'}) %}
    {% set discount_courses_url = url({'for':'home.vip.courses'},{'type':'discount'}) %}
    {% set users_url = url({'for':'home.vip.users'}) %}

    <div class="vip-tab-wrap">
        <div class="layui-tab layui-tab-brief user-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">优惠课程</li>
                <li>畅学课程</li>
                <li>新进会员</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show" id="tab-discount-courses" data-url="{{ discount_courses_url }}"></div>
                <div class="layui-tab-item" id="tab-free-courses" data-url="{{ free_courses_url }}"></div>
                <div class="layui-tab-item" id="tab-users" data-url="{{ users_url }}"></div>
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/vip.index.js') }}

{% endblock %}
