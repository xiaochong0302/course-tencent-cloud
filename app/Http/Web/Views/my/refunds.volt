{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/refund') }}

    {% set status_types = {'all':'全部','pending':'待处理','canceled':'已取消','approved':'退款中','finished':'已完成'} %}
    {% set status = request.get('status','trim','all') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="my-nav">
                <span class="title">我的退款</span>
                {% for key,value in status_types %}
                    {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                    {% set url = (key == 'all') ? url({'for':'web.my.refunds'}) : url({'for':'web.my.refunds'},{'status':key}) %}
                    <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                {% endfor %}
            </div>
            {% if pager.total_pages > 0 %}
                <div class="order-list">
                    {% for item in pager.items %}
                        {% set refund_info_url = url({'for':'web.refund.info'},{'sn':item.sn}) %}
                        <div class="order-card">
                            <div class="header">
                                <span class="sn">编号：{{ item.sn }}</span>
                                <span class="time">时间：{{ date('Y-m-d H:i:s',item.create_time) }}</span>
                            </div>
                            <div class="body clearfix">
                                <div class="column subject">{{ item.subject }}</div>
                                <div class="column price">{{ '￥%0.2f'|format(item.amount) }}</div>
                                <div class="column status">{{ refund_status(item.status) }}</div>
                                <div class="column action">
                                    <button class="layui-btn layui-btn-xs btn-refund-info" data-url="{{ refund_info_url }}">详情</button>
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