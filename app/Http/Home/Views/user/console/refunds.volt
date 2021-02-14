{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/refund') }}

    {% set status_types = {'0':'全部','1':'待处理','2':'已取消','3':'退款中','5':'已完成'} %}
    {% set status = request.get('status','int','0') %}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的退款</span>
                    {% for key,value in status_types %}
                        {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = (key == 'all') ? url({'for':'home.uc.refunds'}) : url({'for':'home.uc.refunds'},{'status':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_pages > 0 %}
                    <div class="order-list">
                        {% for item in pager.items %}
                            {% set refund_info_url = url({'for':'home.refund.info'},{'sn':item.sn}) %}
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
                                        <button class="layui-btn layui-btn-sm btn-refund-info" data-url="{{ refund_info_url }}">详情</button>
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