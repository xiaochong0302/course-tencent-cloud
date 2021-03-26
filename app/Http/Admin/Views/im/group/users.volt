{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set back_url = url({'for':'admin.im_group.list'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back" href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>返回</a>
                <a href="{{ back_url }}"><cite>群组列表</cite></a>
                <a><cite>{{ group.name }}</cite></a>
                <a><cite>成员管理</cite></a>
            </span>
        </div>
    </div>

    <table class="kg-table layui-table">
        <colgroup>
            <col width="10%">
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>用户头像</th>
            <th>用户名称</th>
            <th>所在地区</th>
            <th>用户性别</th>
            <th>成员角色</th>
            <th>加入时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_url = url({'for':'home.user.show','id':item.user.id}) %}
            {% set delete_url = url({'for':'admin.im_group_user.delete'},{'group_id':item.group_id,'user_id':item.user_id}) %}
            {% set is_owner = item.user.id == group.owner_id ? 1 : 0 %}
            {% set role_type = is_owner == 1 ? '群主' : '成员' %}
            <tr>
                <td class="center">
                    <img class="avatar-sm" src="{{ item.user.avatar }}!avatar_160" alt="{{ item.user.name }}">
                </td>
                <td><a href="{{ user_url }}" title="{{ item.user.about }}" target="_blank">{{ item.user.name }}</a>（{{ item.user.id }}）</td>
                <td>{{ item.user.area }}</td>
                <td>{{ gender_info(item.user.gender) }}</td>
                <td>{{ role_type }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td class="center">
                    {% if is_owner == 0 %}
                        <button class="layui-btn layui-bg-red kg-delete" data-url="{{ delete_url }}">删除</button>
                    {% else %}
                        <button class="layui-btn layui-btn-disabled">删除</button>
                    {% endif %}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}