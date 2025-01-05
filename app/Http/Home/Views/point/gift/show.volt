{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point_gift') }}

    {% set gift_redeem_url = url({'for':'home.point_gift.redeem','id':gift.id}) %}
    {% set gift_list_url = url({'for':'home.point_gift.list'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ gift_list_url }}">积分商城</a>
            <a><cite>{{ gift.name }}</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="layui-card">
                <div class="layui-card-header">物品信息</div>
                <div class="layui-card-body">
                    <div class="gift-meta">
                        <div class="cover">
                            <span class="layui-badge layui-bg-green type">{{ gift_type(gift.type) }}</span>
                            <img src="{{ gift.cover }}!cover_270" alt="{{ gift.name }}">
                        </div>
                        <div class="info">
                            {% if gift.type == 1 %}
                                {% set course_url = url({'for':'home.course.show','id':gift.attrs.id}) %}
                                <p class="item">
                                    <a href="{{ course_url }}">{{ gift.name }}</a>
                                </p>
                            {% else %}
                                <p class="item">{{ gift.name }}</p>
                            {% endif %}
                            <p class="item stats">
                                <span class="key">兑换人次</span>
                                <span class="value">{{ gift.redeem_count }}</span>
                                <span class="key">兑换限额</span>
                                <span class="value">{{ gift.redeem_limit }}</span>
                            </p>
                            <p class="item stats">
                                <span class="key">兑换价格</span>
                                <span class="price">{{ gift.point }} 积分</span>
                            </p>
                            <p class="item stats">
                                <span class="key">库存数量</span>
                                <span class="value">{{ gift.stock }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">物品详情</div>
                <div class="layui-card-body">
                    <div class="gift-details ke-content kg-zoom">
                        {% if gift.type == 1 %}
                            {% set course_url = url({'for':'home.course.show','id':gift.attrs.id}) %}
                            <p class="item">
                                <a href="{{ course_url }}">{{ gift.name }}</a>
                                <span class="layui-badge">查看</span>
                            </p>
                        {% elseif gift.type == 3 %}
                            {% set vip_url = url({'for':'home.vip.index'}) %}
                            <p class="item">
                                <a href="{{ vip_url }}">{{ gift.name }}</a>
                                <span class="layui-badge">查看</span>
                            </p>
                        {% else %}
                            {{ gift.details }}
                        {% endif %}
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">物品兑换</div>
                    <div class="layui-card-body">
                        <form class="layui-form">
                            <div class="layui-form-item">
                                <div class="layui-form-mid">
                                    我的积分：<span class="red">{{ user_balance.point }} 积分</span>
                                </div>
                            </div>
                            {% if gift.me.allow_redeem == 1 %}
                                <div class="layui-form-item">
                                    <button class="layui-btn layui-bg-red layui-btn-fluid btn-redeem" data-url="{{ gift_redeem_url }}">立即兑换</button>
                                </div>
                            {% else %}
                                <div class="layui-form-item">
                                    <button class="layui-btn layui-btn-fluid layui-btn-disabled">立即兑换</button>
                                </div>
                            {% endif %}
                        </form>
                    </div>
                </div>
            </div>
            <div class="sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">热门兑换</div>
                    <div class="layui-card-body">
                        {% for gift in hot_gifts %}
                            {% set gift_url = url({'for':'home.point_gift.show','id':gift.id}) %}
                            <div class="sidebar-course-card">
                                <div class="cover">
                                    <a href="{{ gift_url }}" title="{{ gift.name }}">
                                        <img src="{{ gift.cover }}!cover_270" alt="{{ gift.name }}">
                                    </a>
                                </div>
                                <div class="info">
                                    <div class="title layui-elip">
                                        <a href="{{ gift_url }}" title="{{ gift.name }}">{{ gift.name }}</a>
                                    </div>
                                    <div class="meta">
                                        <span class="price">{{ gift.point }} 积分</span>
                                        <span class="count">{{ gift.redeem_count }} 人兑换</span>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/point.gift.show.js') }}

{% endblock %}
