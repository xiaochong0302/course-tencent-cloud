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
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}?target=search">
                <i class="layui-icon layui-icon-search"></i>搜索用户
            </a>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
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
            <th>基本信息</th>
            <th>帐号信息</th>
            <th>用户角色</th>
            <th>数据统计</th>
            <th>活跃动态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set account_phone = item.account.phone ? item.account.phone : 'N/A' %}
            {% set account_email = item.account.email ? item.account.email : 'N/A' %}
            {% set show_url = url({'for':'admin.user.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.user.edit','id':item.id}) %}
            {% set assign_course_url = url({'for':'admin.user.assign_course','id':item.id}) %}
            <tr>
                <td class="center">
                    <img class="kg-avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                </td>
                <td>
                    <p>昵称：{{ item.name }} {{ status_info(item) }}</p>
                    <p>编号：{{ item.id }}</p>
                </td>
                <td>
                    <p>手机：{{ account_phone }}</p>
                    <p>邮箱：{{ account_email }}</p>
                </td>
                <td>
                    <p>教学：{{ edu_role_info(item.edu_role) }}</p>
                    <p>后台：{{ admin_role_info(item.admin_role) }}</p>
                </td>
                <td>
                    <p>课程：{{ item.study_course_count }}</p>
                    <p>收藏：{{ item.favorite_count }}</p>
                </td>
                <td>
                    <p>注册：{{ item.create_time > 0 ? date('Y-m-d',item.create_time) : 'N/A' }}</p>
                    <p>活跃：{{ item.active_time > 0 ? date('Y-m-d',item.active_time) : 'N/A' }}</p>
                </td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ show_url }}">用户详情</a></li>
                            {% if item.admin_role.id != 1 %}
                                <li><a href="{{ edit_url }}">编辑用户</a></li>
                            {% else %}
                                <li><a class="layui-disabled">编辑用户</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ assign_course_url }}">赠送课程</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}
