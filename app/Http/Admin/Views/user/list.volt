{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set add_url = url({'for':'admin.user.add'}) %}
    {% set search_url = url({'for':'admin.user.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>用户管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加用户
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索用户
            </a>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
            <col width="10%">
            <col>
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
            <th>用户昵称</th>
            <th>所在地区</th>
            <th>用户性别</th>
            <th>用户角色</th>
            <th>活跃时间</th>
            <th>注册时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set preview_url = url({'for':'home.user.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.user.edit','id':item.id}) %}
            <tr>
                <td class="center">
                    <img class="avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                </td>
                <td><a href="{{ preview_url }}" title="{{ item.about }}" target="_blank">{{ item.name }}</a>（{{ item.id }}）{{ status_info(item) }}</td>
                <td>{{ item.area }}</td>
                <td>{{ gender_info(item.gender) }}</td>
                <td>
                    <p>教学：{{ edu_role_info(item.edu_role) }}</p>
                    <p>后台：{{ admin_role_info(item.admin_role) }}</p>
                </td>
                <td>{{ date('Y-m-d H:i:s',item.active_time) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ preview_url }}" target="_blank">预览</a></li>
                            <li><a href="{{ edit_url }}">编辑</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}