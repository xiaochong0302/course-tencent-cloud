{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 1 %}
            课程
        {% elseif value == 2 %}
            水吧
        {% elseif value == 3 %}
            职工
        {% else %}
            未知
        {% endif %}
    {%- endmacro %}

    {%- macro owner_info(owner) %}
        {% if owner.id is defined %}
            {% set filter_url = url({'for':'admin.im_group.list'},{'owner_id':owner.id}) %}
            <a href="{{ filter_url }}">{{ owner.name }}</a>（{{ owner.id }}）
        {% else %}
            未设置
        {% endif %}
    {%- endmacro %}

    {% set add_url = url({'for':'admin.im_group.add'}) %}
    {% set search_url = url({'for':'admin.im_group.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>群组管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加群组
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索群组
            </a>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
        <colgroup>
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
            <th>名称</th>
            <th>类型</th>
            <th>群主</th>
            <th>成员</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set preview_url = url({'for':'home.im_group.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.im_group.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.im_group.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.im_group.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.im_group.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                <td> {{ type_info(item.type) }}</td>
                <td>{{ owner_info(item.owner) }}</td>
                <td><span class="layui-badge layui-bg-gray">{{ item.user_count }}</span></td>
                <td><input type="checkbox" name="published" value="1" lay-filter="published" lay-skin="switch" lay-text="是|否" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ preview_url }}" target="_blank">预览</a></li>
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