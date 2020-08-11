{% extends 'templates/main.volt' %}

{% block content %}

    {% set add_page_url = url({'for':'admin.page.add'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>单页管理</cite></a>
        </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_page_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加单页
            </a>
        </div>
    </div>

    <table class="layui-table kg-table layui-form">
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
            <th>标题</th>
            <th>创建时间</th>
            <th>更新时间</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'web.page.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.page.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.page.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.page.delete','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ show_url }}" target="_blank">{{ item.title }}</a></td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                <td>{{ date('Y-m-d H:i',item.update_time) }}</td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked{% endif %}>
                </td>
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

    {{ partial('partials/pager') }}

{% endblock %}