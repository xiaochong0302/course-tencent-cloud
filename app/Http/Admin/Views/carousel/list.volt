{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro target_info(value) %}
        {% if value == 'course' %}
            <span class="layui-badge layui-bg-green">课程</span>
        {% elseif value == 'page' %}
            <span class="layui-badge layui-bg-blue">单页</span>
        {% elseif value == 'link' %}
            <span class="layui-badge layui-bg-orange">链接</span>
        {% endif %}
    {%- endmacro %}

    <div class="kg-nav">
        <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>轮播管理</cite></a>
        </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.carousel.add'}) }}">
                <i class="layui-icon layui-icon-add-1"></i>添加轮播
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
            <th>目标类型</th>
            <th>排序</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'admin.carousel.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.carousel.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.carousel.delete','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td>{{ item.title }}</td>
                <td>{{ target_info(item.target) }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="published" value="1" lay-filter="published" lay-skin="switch" lay-text="是|否" data-url="{{ update_url }}" {% if item.published == 1 %}checked{% endif %}></td>
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