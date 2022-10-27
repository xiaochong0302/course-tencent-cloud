{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro alias_tips(alias) %}
        {% if alias %}
            <a href="javascript:" title="可通过 /page/{{ alias }} 访问页面">{{ alias }}</a>
        {% else %}
            N/A
        {% endif %}
    {%- endmacro %}

    {% set add_url = url({'for':'admin.page.add'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>单页管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
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
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>标题</th>
            <th>别名</th>
            <th>浏览</th>
            <th>创建时间</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set page_url = url({'for':'home.page.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.page.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.page.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.page.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.page.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}">{{ item.title }}</a></td>
                <td>{{ alias_tips(item.alias) }}</td>
                <td>{{ item.view_count }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}>
                </td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ page_url }}" target="_blank">浏览</a></li>
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

    {{ partial('partials/pager') }}

{% endblock %}