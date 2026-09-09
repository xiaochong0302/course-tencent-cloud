{% extends 'templates/main.volt' %}

{% block content %}

    {% set type = request.get('type','int',1) %}
    {% set parent_id = request.get('parent_id','int',0) %}
    {% set add_url = url({'for':'admin.category.add'},{'type':type,'parent_id':parent_id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a href="{{ url({'for':'admin.category.list'},{'type':type}) }}"><cite>分类管理</cite></a>
                {% for item in parent_paths %}
                    <a href="{{ url({'for':'admin.category.list'},{'type':type,'parent_id':item.id}) }}"><cite>{{ item.name }}</cite></a>
                {% endfor %}
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}"><i class="layui-icon layui-icon-add-1"></i>添加分类</a>
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
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>层级</th>
            <th>节点</th>
            <th>排序</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in child_categories %}
            {% set child_url = url({'for':'admin.category.list'},{'type':item.type,'parent_id':item.id}) %}
            {% set edit_url = url({'for':'admin.category.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.category.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.category.delete','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                {% if item.type in [1,3,4,5,6] %}
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
                <td><input class="layui-input kg-field" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}" lay-verify="number"></td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ edit_url }}">编辑</a></li>
                            <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}
