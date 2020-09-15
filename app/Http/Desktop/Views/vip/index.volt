{% extends 'templates/main.volt' %}

{% block content %}

    <div class="vip-header">会员权益</div>

    <div class="vip-reason-list wrap">
        <span class="layui-badge reason-badge">好课畅学</span>
        <span class="layui-badge reason-badge">会员折扣</span>
        <span class="layui-badge reason-badge">高清视频</span>
        <span class="layui-badge reason-badge">广告免疫</span>
        <span class="layui-badge reason-badge">会员标识</span>
        <span class="layui-badge reason-badge">贴心服务</span>
    </div>

    <div class="vip-header">开通会员</div>

    <div class="vip-option-list">
        <div class="layui-row layui-col-space20">
            {% for option in vip_options %}
                {% set order_url = url({'for':'desktop.order.confirm'},{'item_id':option.id,'item_type':4}) %}
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

    {% set free_courses_url = url({'for':'desktop.vip.courses'},{'type':'free'}) %}
    {% set discount_courses_url = url({'for':'desktop.vip.courses'},{'type':'discount'}) %}
    {% set users_url = url({'for':'desktop.vip.users'}) %}

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

    {{ js_include('desktop/js/vip.js') }}

{% endblock %}