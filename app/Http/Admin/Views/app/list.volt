{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 'pc' %}
            PC客户端
        {% elseif value == 'h5' %}
            H5客户端
        {% elseif value == 'ios' %}
            IOS客户端
        {% elseif value == 'android' %}
            Android客户端
        {% elseif value == 'mp_weixin' %}
            微信小程序
        {% elseif value == 'mp_alipay' %}
            支付宝小程序
        {% else %}
            未知
        {% endif %}
    {%- endmacro %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>应用管理</cite></a>
            </span>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>编号</th>
            <th>名称</th>
            <th>类型</th>
            <th>Key / Secret</th>
            <th>创建时间</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'admin.app.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.app.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.app.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.app.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}" title="{{ item.remark }}">{{ item.name }}</a></td>
                <td>{{ type_info(item.type) }}</td>
                <td>{{ item.key }} / {{ item.secret }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td class="center"><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="layui-dropdown">
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