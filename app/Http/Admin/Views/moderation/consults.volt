{% extends 'templates/main.volt' %}

{% block content %}

    {% set search_url = url({'for':'admin.consult.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>咨询审核</cite></a>
            </span>
        </div>
    </div>

    <table class="layui-table kg-table layui-form">
        <colgroup>
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
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set item.answer = item.answer ? item.answer : 'N/A' %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
            {% set moderate_url = url({'for':'admin.consult.moderate','id':item.id}) %}
            <tr>
                <td>
                    <p>课程：<a href="{{ course_url }}">{{ item.course.title }}</a>（{{ item.course.id }}）</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.question }}">提问：{{ item.question }}</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.answer }}">回复：{{ item.answer }}</p>
                </td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}">{{ item.owner.name }}</a></p>
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
                <td class="center">
                    <a href="{{ moderate_url }}" class="layui-btn layui-btn-sm">详情</a>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}