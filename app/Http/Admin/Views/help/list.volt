{% extends 'templates/main.volt' %}

{% block content %}

    {% set add_url = url({'for':'admin.help.add'}) %}
    {% set category_url = url({'for':'admin.help.category'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>帮助管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ category_url }}">
                <i class="layui-icon layui-icon-add-1"></i>帮助分类
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加帮助
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
            <th>分类</th>
            <th>标题</th>
            <th>浏览</th>
            <th>排序</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in helps %}
            {% set list_url = url({'for':'admin.help.list'},{'category_id':item.category.id}) %}
            {% set help_url = url({'for':'home.help.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.help.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.help.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.help.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.help.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ list_url }}">{{ item.category.name }}</a></td>
                <td><a href="{{ edit_url }}">{{ item.title }}</a></td>
                <td>{{ item.view_count }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ help_url }}" target="_blank">浏览</a></li>
                            {% endif %}
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