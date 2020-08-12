{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 'system' %}
            <span class="layui-badge layui-bg-green">内置</span>
        {% elseif value == 'custom' %}
            <span class="layui-badge layui-bg-blue">自定</span>
        {% endif %}
    {%- endmacro %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>角色管理</cite></a>
            </span>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>类型</th>
            <th>成员数</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in roles %}
            {% set user_list_url = url({'for':'admin.user.list'},{'admin_role':item.id}) %}
            {% set edit_url = url({'for':'admin.role.edit','id':item.id}) %}
            {% set delete_url = url({'for':'admin.role.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.role.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="javascript:" title="{{ item.summary }}">{{ item.name }}</a></td>
                <td>{{ type_info(item.type) }}</td>
                <td>
                    <a href="{{ user_list_url }}">
                        <span class="layui-badge layui-bg-green">{{ item.user_count }}</span>
                    </a>
                </td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
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

{% endblock %}