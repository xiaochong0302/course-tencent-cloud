{% extends 'templates/main.volt' %}

{% block content %}

    {% set add_url = url({'for':'admin.tag.add'}) %}
    {% set search_url = url({'for':'admin.tag.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>标签管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加标签
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索标签
            </a>
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
            <th>课程</th>
            <th>文章</th>
            <th>问题</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'admin.tag.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.tag.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.tag.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.tag.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><img class="kg-icon" src="{{ item.icon }}" alt="{{ item.name }}"></td>
                <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                <td>{{ item.course_count }}</td>
                <td>{{ item.article_count }}</td>
                <td>{{ item.question_count }}</td>
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

    {{ partial('partials/pager') }}

{% endblock %}