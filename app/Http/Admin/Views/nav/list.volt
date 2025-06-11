{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro position_info(value) %}
        {% if value == 1 %}
            顶部
        {% elseif value == 2 %}
            底部
        {% endif %}
    {%- endmacro %}

    {%- macro target_info(value) %}
        {% if value == '_blank' %}
            新窗口
        {% elseif value == '_self' %}
            原窗口
        {% endif %}
    {%- endmacro %}

    {% set back_url = url({'for':'admin.nav.list'}) %}
    {% set add_url = url({'for':'admin.nav.add'},{'parent_id':parent.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                {% if parent.id > 0 %}
                    <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>返回</a>
                    <a><cite>{{ parent.name }}</cite></a>
                {% endif %}
                <a><cite>导航管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}"><i class="layui-icon layui-icon-add-1"></i>添加导航</a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col>
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
            <th>名称</th>
            <th>层级</th>
            <th>子节点</th>
            <th>位置</th>
            <th>目标</th>
            <th>排序</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in navs %}
            {% set child_url = url({'for':'admin.nav.list'},{'parent_id':item.id}) %}
            {% set edit_url = url({'for':'admin.nav.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.nav.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.nav.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.nav.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                {% if item.position == 1 and item.level == 1 %}
                    <td><a href="{{ child_url }}"><i class="layui-icon layui-icon-add-circle"></i> {{ item.name }}</a></td>
                {% else %}
                    <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                {% endif %}
                <td>{{ item.level }}</td>
                <td>{{ item.child_count }}</td>
                <td>{{ position_info(item.position) }}</td>
                <td>{{ target_info(item.target) }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
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
