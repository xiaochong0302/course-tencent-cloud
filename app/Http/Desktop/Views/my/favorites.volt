{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的收藏</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-size="lg">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col width="12%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>课程</th>
                            <th>学员</th>
                            <th>收藏</th>
                            <th>评分</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'desktop.course.show','id':item.id}) %}
                            {% set favorite_url = url({'for':'desktop.course.favorite','id':item.id}) %}
                            <tr>
                                <td><a href="{{ course_url }}">{{ item.title }}</a> {{ model_info(item.model) }}</td>
                                <td>{{ item.user_count }}</td>
                                <td>{{ item.favorite_count }}</td>
                                <td>{{ item.rating }}</td>
                                <td class="center">
                                    <button class="layui-btn layui-btn-sm kg-delete" data-tips="确定要取消收藏吗？" data-url="{{ favorite_url }}">取消</button>
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

    {{ js_include('desktop/js/my.js') }}

{% endblock %}