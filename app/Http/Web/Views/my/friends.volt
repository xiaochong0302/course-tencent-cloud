{% extends 'templates/full.volt' %}

{% block content %}

    {%- macro gender_info(value) %}
        {% if value == 1 %}
            <span class="layui-badge layui-bg-red">男</span>
        {% elseif value == 2 %}
            <span class="layui-badge layui-bg-green">女</span>
        {% elseif value == 3 %}
            <span class="layui-badge layui-bg-gray">密</span>
        {% endif %}
    {%- endmacro %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav-title">我的好友</div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-size="lg">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col width="15%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>昵称</th>
                            <th>性别</th>
                            <th>地区</th>
                            <th>最后活跃</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set user_url = url({'for':'web.user.show','id':item.id}) %}
                            {% set delete_url = url({'for':'web.my.delete_friend','id':item.id}) %}
                            <tr>
                                <td><a href="{{ user_url }}" title="{{ item.about|e }}">{{ item.name }}</a></td>
                                <td>{{ gender_info(item.gender) }}</td>
                                <td>{{ item.location }}</td>
                                <td>{{ item.active_time|time_ago }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-sm kg-delete" data-url="{{ delete_url }}">删除</button>
                                </td>
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

{% block include_js %}

    {{ js_include('web/js/my.im.js') }}

{% endblock %}