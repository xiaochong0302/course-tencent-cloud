{% extends 'templates/main.volt' %}

{% block content %}

    {% set gift_redeem_url = url({'for':'home.point_redeem.create'}) %}
    {% set gift_list_url = url({'for':'home.point_gift.list'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ gift_list_url }}">积分兑换</a>
            <a><cite>{{ gift.name }}</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="layui-card">
                <div class="layui-card-header">商品信息</div>
                <div class="layui-card-body">
                    <div class="gift-meta clearfix">
                        <div class="cover">
                            <img src="{{ gift.cover }}!cover_270" alt="{{ gift.name }}">
                        </div>
                        <div class="info">
                            <p class="item">{{ gift.name }}</p>
                            <p class="item stats">
                                <span class="key">兑换价格</span>
                                <span class="price">{{ gift.point }} 积分</span>
                                <span class="key">兑换人次</span>
                                <span class="value">{{ gift.redeem_count }}</span>
                            </p>
                            <p class="item">
                                <button class="layui-btn layui-bg-red btn-redeem" data-id="{{ gift.id }}" data-url="{{ gift_redeem_url }}">立即兑换</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">商品详情</div>
                <div class="layui-card-body">
                    <div class="gift-details markdown-body">{{ gift.details }}</div>
                </div>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">热门商品</div>
                    <div class="layui-card-body">
                        {% for gift in hot_gifts %}
                            {% set gift_url = url({'for':'home.point_gift.show','id':gift.id}) %}
                            <div class="sidebar-course-card clearfix">
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

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/point.gift.show.js') }}

{% endblock %}