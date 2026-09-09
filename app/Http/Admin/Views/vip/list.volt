{% extends 'templates/main.volt' %}

{% block content %}

    {% set add_url = url({'for':'admin.vip.add'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>会员套餐</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加套餐
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
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>套餐名称</th>
            <th>套餐期限（月）</th>
            <th>套餐价格</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'admin.vip.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.vip.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.vip.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.vip.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}">{{ item.title }}</a></td>
                <td>{{ item.expiry }}</td>
                <td>{{ '￥%0.2f'|format(item.price) }}</td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}>
                </td>
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