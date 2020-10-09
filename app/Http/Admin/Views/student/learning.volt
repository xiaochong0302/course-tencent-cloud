{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro client_type_info(value) %}
        {% if value == 1 %}
            desktop
        {% elseif value == 2 %}
            mobile
        {% elseif value == 3 %}
            app
        {% elseif value == 4 %}
            小程序
        {% endif %}
    {%- endmacro %}

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
            <th>课时信息</th>
            <th>终端信息</th>
            <th>学习时长</th>
            <th>活跃时间</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            <tr>
                <td>
                    <p class="layui-elip">课程：{{ item.course.title }}</p>
                    <p class="layui-elip">章节：{{ item.chapter.title }}</p>
                </td>
                <td>
                    <p>类型：{{ client_type_info(item.client_type) }}</p>
                    <p>地址：<a href="javascript:" class="kg-ip2region" title="查看位置" data-ip="{{ item.client_ip }}">{{ item.client_ip }}</a></p>
                </td>
                <td>{{ item.duration|duration }}</td>
                <td>{{ date('Y-m-d H:i:s',item.active_time) }}</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/ip2region.js') }}

{% endblock %}