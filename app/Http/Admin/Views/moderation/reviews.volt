{% extends 'templates/main.volt' %}

{% block content %}

    {% set search_url = url({'for':'admin.consult.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>评价审核</cite></a>
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
            <th>內容</th>
            <th>用户</th>
            <th>评分</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
            {% set moderate_url = url({'for':'admin.review.moderate','id':item.id}) %}
            <tr>
                <td>
                    <p>课程：<a href="{{ course_url }}">{{ item.course.title }}</a>（{{ item.course.id }}）</p>
                    <p class="layui-elip kg-item-elip" title="{{ item.content }}">评价：{{ item.content }}</p>
                    <p>时间：{{ date('Y-m-d H:i',item.create_time) }}</p>
                </td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>内容实用：{{ item.rating1 }}</p>
                    <p>通俗易懂：{{ item.rating2 }}</p>
                    <p>逻辑清晰：{{ item.rating3 }}</p>
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