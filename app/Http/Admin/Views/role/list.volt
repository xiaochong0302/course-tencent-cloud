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
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.role.add'}) }}">
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
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="javascript:" title="{{ item.summary }}">{{ item.name }}</a></td>
                <td>{{ type_info(item.type) }}</td>
                <td>
                    <a href="{{ url({'for':'admin.user.list'},{'admin_role':item.id}) }}">
                        <span class="layui-badge layui-bg-green">{{ item.user_count }}</span>
                    </a>
                </td>
                <td align="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
                        <ul>
                            <li><a href="{{ url({'for':'admin.role.edit','id':item.id}) }}">编辑</a></li>
                            <li><a href="javascript:" class="kg-delete" data-url="{{ url({'for':'admin.role.delete','id':item.id}) }}">删除</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}