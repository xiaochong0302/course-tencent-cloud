{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的好友</span>
                </div>
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
                            {% set user_url = url({'for':'home.user.show','id':item.id}) %}
                            {% set delete_url = url({'for':'home.im.quit_friend','id':item.id}) %}
                            <tr>
                                <td><a href="{{ user_url }}" title="{{ item.about }}">{{ item.name }}</a></td>
                                <td>{{ gender_info(item.gender) }}</td>
                                <td>{{ item.area }}</td>
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

    {{ js_include('home/js/my.im.js') }}

{% endblock %}