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
            <th>课时信息</th>
            <th>终端信息</th>
            <th>学习时长</th>
            <th>活跃时间</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set duration = item.duration > 0 ? item.duration|duration : 'N/A' %}
            {% set active_time = item.active_time > 0 ? date('Y-m-d H:i:s',item.active_time) : 'N/A' %}
            <tr>
                <td>
                    <p class="layui-elip">课程：{{ item.course.title }}（{{ item.course.id }}）</p>
                    <p class="layui-elip">章节：{{ item.chapter.title }}（{{ item.chaper.id }}）</p>
                </td>
                <td>
                    <p>类型：{{ client_type(item.client_type) }}</p>
                    <p>地址：<a href="javascript:" class="kg-ip2region" title="查看位置" data-ip="{{ item.client_ip }}">{{ item.client_ip }}</a></p>
                </td>
                <td>{{ duration }}</td>
                <td>{{ active_time }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/ip2region.js') }}

{% endblock %}