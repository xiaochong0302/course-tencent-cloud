{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 1 %}
            内置
        {% elseif value == 2 %}
            自定义
        {% endif %}
    {%- endmacro %}

    {% set add_url = url({'for':'admin.role.add'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>角色管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加角色
            </a>
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
                {% if item.id == 1 %}
                    <td><a title="{{ item.summary }}">{{ item.name }}</a></td>
                {% else %}
                    <td><a href="{{ edit_url }}" title="{{ item.summary }}">{{ item.name }}</a></td>
                {% endif %}
                <td>{{ type_info(item.type) }}</td>
                <td>
                    <a href="{{ user_list_url }}">
                        <span class="layui-badge layui-bg-green">{{ item.user_count }}</span>
                    </a>
                </td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.id == 1 %}
                                <li><a>编辑</a></li>
                                <li><a>删除</a></li>
                            {% else %}
                                <li><a href="{{ edit_url }}">编辑</a></li>
                                {% if item.deleted == 0 %}
                                    <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                                {% else %}
                                    <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原</a></li>
                                {% endif %}
                            {% endif %}
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}