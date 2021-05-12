{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

    <table class="layui-table kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>终端类型</th>
            <th>终端地址</th>
            <th>活跃时间</th>
            <th>创建时间</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            <tr>
                <td>{{ client_type(item.client_type) }}</td>
                <td><a href="javascript:" title="查看位置" class="layui-badge layui-bg-gray kg-ip2region">{{ item.client_ip }}</a></td>
                <td>{{ date('Y-m-d H:i',item.active_time) }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/ip2region.js') }}

{% endblock %}