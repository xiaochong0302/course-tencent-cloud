{% extends 'templates/main.volt' %}

{% block content %}

    {% set back_url = url({'for':'admin.category.list'},{'type':type}) %}
    {% set add_url = url({'for':'admin.category.add'},{'type':type,'parent_id':parent.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                {% if parent.id > 0 %}
                    <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>返回</a>
                    <a><cite>{{ parent.name }}</cite></a>
                {% endif %}
                <a><cite>分类管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            {% if show_add_button %}
                <a class="layui-btn layui-btn-sm" href="{{ add_url }}"><i class="layui-icon layui-icon-add-1"></i>添加分类</a>
            {% endif %}
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
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>图标</th>
            <th>名称</th>
            <th>层级</th>
            <th>子节点</th>
            <th>排序</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in categories %}
            {% set child_url = url({'for':'admin.category.list'},{'type':item.type,'parent_id':item.id}) %}
            {% set edit_url = url({'for':'admin.category.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.category.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.category.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.category.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><img class="kg-icon-sm" src="{{ item.icon }}" alt="{{ item.name }}"></td>
                {% if item.type in [1,3,4] %}
                    {% if item.level == 1 %}
                        <td><a href="{{ child_url }}"><i class="layui-icon layui-icon-add-circle"></i> {{ item.name }}</a></td>
                    {% else %}
                        <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                    {% endif %}
                {% else %}
                    <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                {% endif %}
                <td>{{ item.level }}</td>
                <td>{{ item.child_count }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
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
