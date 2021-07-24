{% extends 'templates/main.volt' %}

{% block content %}

{{ partial('macros/consult') }}

    {% set search_url = url({'for':'admin.consult.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back"><i class="layui-icon layui-icon-return"></i>返回</a>
                {% if course %}
                    <a><cite>{{ course.title }}</cite></a>
                {% endif %}
                <a><cite>咨询管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索咨询
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
            <th>问答</th>
            <th>用户</th>
            <th>时间</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set item.answer = item.answer ? item.answer : 'N/A' %}
            {% set list_by_course_url = url({'for':'admin.consult.list'},{'course_id':item.course.id}) %}
            {% set list_by_user_url = url({'for':'admin.consult.list'},{'owner_id':item.owner.id}) %}
            {% set moderate_url = url({'for':'admin.consult.moderate','id':item.id}) %}
            {% set edit_url = url({'for':'admin.consult.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.consult.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.consult.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.consult.restore','id':item.id}) %}
            <tr>
                <td>
                    <p>课程：<a href="{{ list_by_course_url }}">{{ item.course.title }}</a>（{{ item.course.id }}）{{ private_info(item.private) }}</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.question }}">提问：{{ item.question }}</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.answer }}">回复：{{ item.answer }}</p>
                </td>
                <td>
                    <p>昵称：<a href="{{ list_by_user_url }}">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>提问：{{ date('Y-m-d H:i:s',item.create_time) }}</p>
                    {% if item.reply_time > 0 %}
                        <p>回复：{{ date('Y-m-d H:i:s',item.reply_time) }}</p>
                    {% else %}
                        <p>回复：N/A</p>
                    {% endif %}
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