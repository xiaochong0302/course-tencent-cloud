{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point') }}

    {% set search_url = url({'for':'admin.point_redeem.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>兑换记录</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索兑换
            </a>
        </div>
    </div>

    <table class="kg-table layui-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>物品名称</th>
            <th>消耗积分</th>
            <th>兑换状态</th>
            <th>兑换时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_filter_url = url({'for':'admin.point_redeem.list'},{'user_id':item.user_id}) %}
            {% set deliver_url = url({'for':'admin.point_redeem.deliver','id':item.id}) %}
            {% set gift_url = url({'for':'home.point_gift.show','id':item.gift_id}) %}
            <tr>
                <td>
                    <p><a href="{{ gift_url }}" target="_blank">{{ item.gift_name }}（{{ item.gift_id }}）</a>{{ gift_type_info(item.gift_type) }}</p>
                    <p>用户名称：<a href="{{ user_filter_url }}">{{ item.user_name }}</a> （{{ item.user_id }}） 联系方式：
                        <button class="layui-btn layui-btn-xs kg-contact" data-name="{{ item.contact_name }}" data-phone="{{ item.contact_phone }}" data-address="{{ item.contact_address }}">查看</button>
                    </p>
                </td>
                <td>{{ item.gift_point }}</td>
                <td>{{ redeem_status_info(item.status) }}</td>
                <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                <td class="center">
                    {% if item.gift_type == 2 %}
                        <button class="layui-btn layui-btn-sm kg-deliver" data-url="{{ deliver_url }}">发货</button>
                    {% else %}
                        N/A
                    {% endif %}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('.kg-deliver').on('click', function () {
                var url = $(this).data('url');
                layer.confirm('确定要发货吗？', function () {
                    $.post(url, function (res) {
                        layer.msg(res.msg, {icon: 1});
                        setTimeout(function () {
                            window.location.reload();
                        }, 1500);
                    });
                });
            });
        });

    </script>

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/contact.js') }}

{% endblock %}