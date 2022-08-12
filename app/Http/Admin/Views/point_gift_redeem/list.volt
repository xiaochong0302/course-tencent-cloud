{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point_gift') }}

    {% set search_url = url({'for':'admin.point_gift_redeem.search'}) %}

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
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>物品信息</th>
            <th>用户信息</th>
            <th>消耗积分</th>
            <th>兑换状态</th>
            <th>兑换时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_filter_url = url({'for':'admin.point_gift_redeem.list'},{'user_id':item.user_id}) %}
            {% set deliver_url = url({'for':'admin.point_gift_redeem.deliver','id':item.id}) %}
            {% set gift_url = url({'for':'home.point_gift.show','id':item.gift_id}) %}
            <tr>
                <td>
                    <p>物品名称：<a href="{{ gift_url }}" target="_blank">{{ item.gift_name }}</a>（{{ item.gift_id }}）</p>
                    <p>物品类型：{{ gift_type(item.gift_type) }}</p>
                </td>
                <td>
                    <p>用户名称：<a href="{{ user_filter_url }}">{{ item.user_name }}</a>（{{ item.user_id }}）</p>
                    <p>联系方式：<a href="javascript:" class="layui-badge layui-bg-green kg-contact" data-name="{{ item.contact_name }}" data-phone="{{ item.contact_phone }}" data-address="{{ item.contact_address }}">查看</a></p>
                </td>
                <td>{{ item.gift_point }}</td>
                <td>{{ redeem_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
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

{% block include_js %}

    {{ js_include('admin/js/contact.js') }}

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