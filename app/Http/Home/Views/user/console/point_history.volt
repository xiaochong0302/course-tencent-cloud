{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point') }}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">积分记录</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table history-table" lay-size="lg">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>来源</th>
                            <th>积分</th>
                            <th>详情</th>
                            <th>时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            <tr>
                                <td>{{ event_type_info(item.event_type) }}</td>
                                <td>{{ event_point_info(item.event_point) }}</td>
                                <td>{{ event_detail_info(item) }}</td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}