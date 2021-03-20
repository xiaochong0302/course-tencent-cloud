{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/flash_sale') }}

    {% set add_url = url({'for':'admin.flash_sale.add'}) %}
    {% set search_url = url({'for':'admin.flash_sale.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>秒杀管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加商品
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索商品
            </a>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
        <group>
            <col>
            <col>
            <col>
            <col>
            <col width="15%">
            <col>
            <col width="12%">
        </group>
        <thead>
        <tr>
            <th>商品信息</th>
            <th>秒杀价格</th>
            <th>秒杀库存</th>
            <th>秒杀时间</th>
            <th>参与场次</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'admin.flash_sale.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.flash_sale.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.flash_sale.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.flash_sale.restore','id':item.id}) %}
            <tr>
                <td>{{ item_full_info(item.item_type,item.item_info) }}</td>
                <td>{{ '￥%0.2f'|format(item.price) }}</td>
                <td>{{ item.stock }}</td>
                <td>
                    <p>开始：{{ date('Y-m-d H:i:s',item.start_time) }}</p>
                    <p>结束：{{ date('Y-m-d H:i:s',item.end_time) }}</p>
                </td>
                <td>{{ schedules_info(item.schedules) }}</td>
                <td><input type="checkbox" name="published" value="1" lay-filter="published" lay-skin="switch" lay-text="是|否" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
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

    {{ partial('partials/pager') }}

{% endblock %}