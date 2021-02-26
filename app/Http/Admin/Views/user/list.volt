{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro gender_info(value) %}
        {% if value == 1 %}
            男
        {% elseif value == 2 %}
            女
        {% elseif value == 3 %}
            密
        {% endif %}
    {%- endmacro %}

    {%- macro edu_role_info(user) %}
        {% if user.edu_role.id == 1 %}
            学员
        {% elseif user.edu_role.id == 2 %}
            <a href="{{ url({'for':'admin.user.list'},{'edu_role':user.edu_role.id}) }}">讲师</a>
        {% endif %}
    {%- endmacro %}

    {%- macro admin_role_info(user) %}
        {% if user.admin_role.id > 0 %}
            <a href="{{ url({'for':'admin.user.list'},{'admin_role':user.admin_role.id}) }}">{{ user.admin_role.name }}</a>
        {% endif %}
    {%- endmacro %}

    {%- macro status_info(user) %}
        {% if user.vip == 1 %}
            <span class="layui-badge layui-bg-orange" title="期限：{{ date('Y-m-d H:i:s',user.vip_expiry_time) }}">会员</span>
        {% endif %}
        {% if user.locked == 1 %}
            <span class="layui-badge" title="期限：{{ date('Y-m-d H:i:s',user.lock_expiry_time) }}">锁定</span>
        {% endif %}
    {%- endmacro %}

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
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>昵称</th>
            <th>性别</th>
            <th>教学角色</th>
            <th>后台角色</th>
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
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}" title="{{ item.about }}">{{ item.name }}</a>{{ status_info(item) }}</td>
                <td>{{ gender_info(item.gender) }}</td>
                <td>{{ edu_role_info(item) }}</td>
                <td>{{ admin_role_info(item) }}</td>
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