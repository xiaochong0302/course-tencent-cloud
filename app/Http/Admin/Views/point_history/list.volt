{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point') }}

    {% set search_url = url({'for':'admin.point_history.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>积分记录</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索积分
            </a>
        </div>
    </div>

    <table class="layui-table" lay-size="lg">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>用户信息</th>
            <th>积分变化</th>
            <th>事件类型</th>
            <th>事件详情</th>
            <th>创建时间</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_filter_url = url({'for':'admin.point_history.list'},{'user_id':item.user_id}) %}
            <tr>
                <td><a href="{{ user_filter_url }}">{{ item.user_name }}</a>（{{ item.user_id }}）</td>
                <td>{{ event_point_info(item.event_point) }}</td>
                <td>{{ event_type_info(item.event_type) }}</td>
                <td>{{ event_item_info(item) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}