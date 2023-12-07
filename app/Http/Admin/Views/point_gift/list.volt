{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point_gift') }}

    {% set redeem_url = url({'for':'admin.point_gift_redeem.list'}) %}
    {% set add_url = url({'for':'admin.point_gift.add'}) %}
    {% set search_url = url({'for':'admin.point_gift.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>礼品管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ redeem_url }}">
                <i class="layui-icon layui-icon-log"></i>兑换记录
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加礼品
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索礼品
            </a>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
        <group>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </group>
        <thead>
        <tr>
            <th>编号</th>
            <th>物品名称</th>
            <th>物品类型</th>
            <th>所需积分</th>
            <th>库存数量</th>
            <th>兑换限额</th>
            <th>兑换人次</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set redeem_url = url({'for':'admin.point_gift_redeem.list'},{'gift_id':item.id}) %}
            {% set gift_url = url({'for':'home.point_gift.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.point_gift.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.point_gift.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.point_gift.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.point_gift.restore','id':item.id}) %}
            <tr>
                <td>{{ item.id }}</td>
                <td><a href="{{ edit_url }}">{{ item.name }}</a></td>
                <td>{{ gift_type(item.type) }}</td>
                <td>{{ item.point }}</td>
                <td>{{ item.stock }}</td>
                <td>{{ item.redeem_limit }}</td>
                <td><a class="layui-badge layui-bg-green" href="{{ redeem_url }}">{{ item.redeem_count }}</a></td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ gift_url }}" target="_blank">浏览</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原</a></li>
                            {% endif %}
                            <li><a href="{{ redeem_url }}">兑换记录</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}