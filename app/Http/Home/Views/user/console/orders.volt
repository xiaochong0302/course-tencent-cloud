{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/order') }}

    {% set status_types = {'0':'全部','1':'待支付','3':'已完成','4':'已关闭','5':'已退款'} %}
    {% set status = request.get('status','trim','0') %}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的订单</span>
                    {% for key,value in status_types %}
                        {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = (key == '0') ? url({'for':'home.uc.orders'}) : url({'for':'home.uc.orders'},{'status':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_pages > 0 %}
                    <div class="order-list">
                        {% for item in pager.items %}
                            {% set order_info_url = url({'for':'home.order.info'},{'sn':item.sn}) %}
                            <div class="order-card">
                                <div class="header">
                                    <span>编号：{{ item.sn }}</span>
                                    <span>时间：{{ date('Y-m-d H:i:s',item.create_time) }}</span>
                                    {% if item.promotion_type > 0 %}
                                        促销：<span class="layui-badge layui-bg-blue">{{ promotion_type(item.promotion_type) }}</span>
                                    {% endif %}
                                </div>
                                <div class="body clearfix">
                                    <div class="column subject">{{ item.subject }}</div>
                                    <div class="column price">{{ '￥%0.2f'|format(item.amount) }}</div>
                                    <div class="column status">{{ order_status(item.status) }}</div>
                                    <div class="column action">
                                        <button class="layui-btn layui-btn-sm btn-order-info" data-url="{{ order_info_url }}">详情</button>
                                    </div>
                                </div>
                            </div>
                        {% endfor %}
                    </div>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}