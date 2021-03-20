{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro sale_status(value) %}
        {% if value == 'active' %}
            进行中
        {% elseif value == 'pending' %}
            未开始
        {% elseif value == 'finished' %}
            已结束
        {% endif %}
    {% endmacro %}

    {%- macro sale_info(sale,status) %}
        {% if sale.item_type == 1 %}
            {{ course_sale_info(sale,status) }}
        {% elseif sale.item_type == 2 %}
            {{ package_sale_info(sale,status) }}
        {% elseif sale.item_type == 3 %}
            {{ vip_sale_info(sale,status) }}
        {% endif %}
    {% endmacro %}

    {%- macro course_sale_info(sale,status) %}
        {% set course = sale.item_info.course %}
        {% set course_url = url({'for':'home.course.show','id':course.id}) %}
        <div class="course-card">
            <div class="cover">
                <a href="{{ course_url }}" target="_blank">
                    <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}" title="{{ course.title }}">
                </a>
            </div>
            <div class="info">
                <div class="title layui-elip">
                    <a href="{{ course_url }}" target="_blank" title="{{ course.title }}">{{ course.title }}</a>
                </div>
                <div class="meta">
                    <span class="origin-price">{{ '￥%0.2f'|format(course.market_price) }}</span>
                    <span class="price">{{ '￥%0.2f'|format(sale.price) }}</span>
                    {% if status == 'active' %}
                        <span class="layui-badge order" data-id="{{ sale.id }}">立即购买</span>
                    {% else %}
                        <span class="layui-badge layui-bg-gray">立即购买</span>
                    {% endif %}
                </div>
            </div>
        </div>
    {% endmacro %}

    {%- macro package_sale_info(sale,status) %}
        {% set package = sale.item_info.package %}
        {% set link_url = url({'for':'home.package.courses','id':package.id}) %}
        <div class="course-card">
            <div class="cover">
                <a class="package-link" href="javascript:" data-url="{{ link_url }}">
                    <img src="{{ package.cover }}!cover_270" alt="{{ package.title }}" title="{{ package.title }}">
                </a>
            </div>
            <div class="info">
                <div class="title layui-elip">
                    <a class="package-link" href="javascript:" title="{{ package.title }}" data-url="{{ link_url }}">{{ package.title }}</a>
                </div>
                <div class="meta">
                    <span class="origin-price">{{ '￥%0.2f'|format(package.market_price) }}</span>
                    <span class="price">{{ '￥%0.2f'|format(sale.price) }}</span>
                    {% if status == 'active' %}
                        <span class="layui-badge order" data-id="{{ sale.id }}">立即购买</span>
                    {% else %}
                        <span class="layui-badge layui-bg-gray">立即购买</span>
                    {% endif %}
                </div>
            </div>
        </div>
    {% endmacro %}

    {%- macro vip_sale_info(sale,status) %}
        {% set vip = sale.item_info.vip %}
        {% set vip.title = "会员服务（%s）"|format(vip.title) %}
        <div class="course-card">
            <div class="cover">
                <img src="{{ vip.cover }}!cover_270" alt="{{ vip.title }}" title="{{ vip.title }}">
            </div>
            <div class="info">
                <div class="title layui-elip">
                    <a href="javascript:" title="{{ vip.title }}">{{ vip.title }}</a>
                </div>
                <div class="meta">
                    <span class="origin-price">{{ '￥%0.2f'|format(vip.price) }}</span>
                    <span class="price">{{ '￥%0.2f'|format(sale.price) }}</span>
                    {% if status == 'active' %}
                        <span class="layui-badge order" data-id="{{ sale.id }}">立即购买</span>
                    {% else %}
                        <span class="layui-badge layui-bg-gray">立即购买</span>
                    {% endif %}
                </div>
            </div>
        </div>
    {% endmacro %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>秒杀</cite></a>
    </div>

    {% for date_sale in sales %}
        <div class="index-wrap wrap">
            <div class="header">{{ date_sale.date }}</div>
            <div class="content">
                <div class="layui-tab layui-tab-brief">
                    <ul class="layui-tab-title">
                        {% for item in date_sale.items %}
                            {% set class = item.selected == 1 ? 'layui-this' : 'none' %}
                            <li class="{{ class }}">{{ item.hour }}（{{ sale_status(item.status) }}）</li>
                        {% endfor %}
                    </ul>
                    <div class="layui-tab-content">
                        {% for item in date_sale.items %}
                            {% set class = item.selected == 1 ? 'layui-tab-item layui-show' : 'layui-tab-item' %}
                            <div class="{{ class }}">
                                <div class="index-course-list clearfix">
                                    <div class="layui-row layui-col-space20">
                                        {% for sale in item.items %}
                                            <div class="layui-col-md3">
                                                {{ sale_info(sale,item.status) }}
                                            </div>
                                        {% endfor %}
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    </div>
                </div>
            </div>
        </div>
    {% endfor %}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/flashsale.js') }}

{% endblock %}