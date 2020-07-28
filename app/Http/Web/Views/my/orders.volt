{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('partials/macro_order') }}

    {% set status_types = {'all':'全部','pending':'待支付','finished':'已完成','closed':'已关闭','refunded':'已退款'} %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="order-filter wrap">
                {% set status = request.get('status','trim','all') %}
                {% for key,value in status_types %}
                    {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                    {% set url = (key == 'all') ? url({'for':'web.my.orders'}) : url({'for':'web.my.orders'},{'status':key}) %}
                    <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                {% endfor %}
            </div>
            {% if pager.total_pages > 0 %}
                <div class="order-list">
                    {% for item in pager.items %}
                        {% set order_info_url = url({'for':'web.order.info'},{'sn':item.sn}) %}
                        <div class="order-card">
                            <div class="header">
                                <span class="sn">编号：{{ item.sn }}</span>
                                <span class="time">时间：{{ date('Y-m-d H:i:s',item.create_time) }}</span>
                            </div>
                            <div class="body clearfix">
                                <div class="column subject">{{ item.subject }}</div>
                                <div class="column price">{{ '￥%0.2f'|format(item.amount) }}</div>
                                <div class="column status">{{ order_status(item.status) }}</div>
                                <div class="column action">
                                    <a class="layui-btn layui-btn-sm btn-order-info" href="javascript:" data-url="{{ order_info_url }}">详情</a>
                                </div>
                            </div>
                        </div>
                    {% endfor %}
                </div>
                {{ partial('partials/pager') }}
            {% endif %}
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/my.js') }}

{% endblock %}