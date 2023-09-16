{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/review') }}

    {% set search_url = url({'for':'admin.review.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back"><i class="layui-icon layui-icon-return"></i>返回</a>
                {% if course %}
                    <a><cite>{{ course.title }}</cite></a>
                {% endif %}
                <a><cite>评价管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索评价
            </a>
        </div>
    </div>

    <table class="layui-table kg-table layui-form">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>內容</th>
            <th>用户</th>
            <th>评分</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set list_by_course_url = url({'for':'admin.review.list'},{'course_id':item.course.id}) %}
            {% set list_by_owner_url = url({'for':'admin.review.list'},{'owner_id':item.owner.id}) %}
            {% set moderate_url = url({'for':'admin.review.moderate','id':item.id}) %}
            {% set edit_url = url({'for':'admin.review.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.review.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.review.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.review.restore','id':item.id}) %}
            <tr>
                <td>
                    <p>课程：<a href="{{ list_by_course_url }}">{{ item.course.title }}</a>（{{ item.course.id }}）</p>
                    <p>评价：<span title="{{ item.content }}">{{ substr(item.content,0,30) }}</span>（{{ item.id }}）</p>
                    <p>时间：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
                </td>
                <td>
                    <p>昵称：<a href="{{ list_by_owner_url }}">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>内容实用：{{ item.rating1 }}</p>
                    <p>通俗易懂：{{ item.rating2 }}</p>
                    <p>逻辑清晰：{{ item.rating3 }}</p>
                </td>
                <td>{{ publish_status(item.published) }}</td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ moderate_url }}">审核</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原</a></li>
                            {% endif %}
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}