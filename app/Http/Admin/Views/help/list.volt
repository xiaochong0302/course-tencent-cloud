{% extends 'templates/main.volt' %}

{% block content %}

    <div class="kg-nav">
        <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>帮助管理</cite></a>
        </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.help.add'}) }}">
                <i class="layui-icon layui-icon-add-1"></i>添加帮助
            </a>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
        <colgroup>
            <col>
            <col>
            <col>
            <col width="10%">
            <col width="10%">
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>标题</th>
            <th>分类</th>
            <th>排序</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in helps %}
            {% set list_url = url({'for':'admin.help.list'},{'category_id':item.category.id}) %}
            {% set edit_url = url({'for':'admin.help.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.help.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.help.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.help.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td>{{ item.title }}</td>
                <td><a href="{{ list_url }}">{{ item.category.name }}</a></td>
                <td align="center"><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td align="center"><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked{% endif %}></td>
                <td align="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <span class="layui-icon layui-icon-triangle-d"></span></button>
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