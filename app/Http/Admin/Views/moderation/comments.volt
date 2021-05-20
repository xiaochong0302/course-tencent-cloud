{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/comment') }}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>评论审核</cite></a>
            </span>
        </div>
    </div>

    <table class="layui-table kg-table layui-form">
        <colgroup>
            <col width="50%">
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>评论</th>
            <th>作者</th>
            <th>时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set review_url = url({'for':'admin.answer.publish_review','id':item.id}) %}
            <tr>
                <td>{{ substr(item.content,0,32) }}</td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                <td class="center">
                    <a href="{{ review_url }}" class="layui-btn layui-btn-sm">详情</a>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}