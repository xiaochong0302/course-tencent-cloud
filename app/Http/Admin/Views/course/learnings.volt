{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

    {% if pager.total_pages > 0 %}
        <table class="layui-table kg-table">
            <colgroup>
                <col>
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
            <tr>
                <th>章节名称</th>
                <th>终端类型</th>
                <th>终端地址</th>
                <th>学习时长</th>
                <th>最近学习</th>
            </tr>
            </thead>
            <tbody>
            {% for item in pager.items %}
                {% set duration = item.duration > 0 ? item.duration|duration : 'N/A' %}
                {% set active_time = item.active_time > 0 ? date('Y-m-d H:i:s',item.active_time) : 'N/A' %}
                <tr>
                    <td>{{ item.chapter.title }}（{{ item.chapter.id }}）</td>
                    <td>{{ client_type(item.client_type) }}</td>
                    <td><a href="javascript:" class="kg-ip2region" title="查看位置" data-ip="{{ item.client_ip }}">{{ item.client_ip }}</a></td>
                    <td>{{ duration }}</td>
                    <td>{{ active_time }}</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
        {{ partial('partials/pager') }}
    {% endif %}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/ip2region.js') }}

{% endblock %}