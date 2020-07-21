{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_refund') }}

    {% set status_types = {'all':'全部','pending':'待处理','canceled':'已取消','approved':'退款中','finished':'已完成'} %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="order-filter wrap">
                {% set status = request.get('status','trim','all') %}
                {% for key,value in status_types %}
                    {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                    {% set url = (key == 'all') ? url({'for':'web.my.refunds'}) : url({'for':'web.my.refunds'},{'status':key}) %}
                    <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                {% endfor %}
            </div>
            {% if pager.total_pages > 0 %}
                <div class="order-list">
                    {% for item in pager.items %}
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
                                    <a class="layui-btn layui-btn-sm" href="javascript:">详情</a>
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