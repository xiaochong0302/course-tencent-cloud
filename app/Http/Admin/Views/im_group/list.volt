{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 'course' %}
            <span class="layui-badge layui-bg-green">课</span>
        {% elseif value == 'chat' %}
            <span class="layui-badge layui-bg-blue">聊</span>
        {% endif %}
    {%- endmacro %}

    {%- macro owner_info(owner) %}
        {% if owner %}
            {{ owner.name }}（{{ owner.id }}）
        {% else %}
            未设置
        {% endif %}
    {%- endmacro %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>群组管理</cite></a>
            </span>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
        <colgroup>
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
            <th>名称</th>
            <th>群主</th>
            <th>成员</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'admin.im_group.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.im_group.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.im_group.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.im_group.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td>{{ item.name }} {{ type_info(item.type) }}</td>
                <td>{{ owner_info(item.owner) }}</td>
                <td><span class="layui-badge layui-bg-gray">{{ item.user_count }}</span></td>
                <td><input type="checkbox" name="published" value="1" lay-filter="published" lay-skin="switch" lay-text="是|否" data-url="{{ update_url }}" {% if item.published == 1 %}checked{% endif %}></td>
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

    {{ partial('partials/pager') }}

{% endblock %}