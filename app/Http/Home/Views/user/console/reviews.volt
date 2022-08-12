{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的评价</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table review-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col width="15%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>内容</th>
                            <th>评分</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
                            {% set edit_url = url({'for':'home.review.edit','id':item.id}) %}
                            {% set delete_url = url({'for':'home.review.delete','id':item.id}) %}
                            <tr>
                                <td>
                                    <p class="title layui-elip">课程：<a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a></p>
                                    <p class="content layui-elip" title="{{ item.content }}">评价：{{ item.content }}</p>
                                    <p class="time">时间：{{ item.create_time|time_ago }}</p>
                                </td>
                                <td>
                                    <p class="rating">内容实用：{{ "%0.1f"|format(item.rating1) }}</p>
                                    <p class="rating">通俗易懂：{{ "%0.1f"|format(item.rating2) }}</p>
                                    <p class="rating">逻辑清晰：{{ "%0.1f"|format(item.rating3) }}</p>
                                </td>
                                <td>
                                    <button class="layui-btn layui-btn-xs layui-bg-blue btn-edit-review" data-url="{{ edit_url }}">修改</button>
                                    <button class="layui-btn layui-btn-xs layui-bg-red kg-delete" data-url="{{ delete_url }}">删除</button>
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

    {{ js_include('home/js/user.console.js') }}

{% endblock %}