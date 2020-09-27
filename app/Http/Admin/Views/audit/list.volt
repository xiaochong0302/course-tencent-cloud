{% extends 'templates/main.volt' %}

{% block content %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>操作记录</cite></a>
            </span>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>用户编号</th>
            <th>用户名称</th>
            <th>用户IP</th>
            <th>请求路由</th>
            <th>请求路径</th>
            <th>请求时间</th>
            <th>请求内容</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set list_by_id_url = url({'for':'admin.audit.list'},{'user_id':item.user_id}) %}
            {% set list_by_ip_url = url({'for':'admin.audit.list'},{'user_ip':item.user_ip}) %}
            {% set list_by_route_url = url({'for':'admin.audit.list'},{'req_route':item.req_route}) %}
            {% set list_by_path_url = url({'for':'admin.audit.list'},{'req_path':item.req_path}) %}
            {% set show_url = url({'for':'admin.audit.show','id':item.id}) %}
            <tr>
                <td>{{ item.user_id }}</td>
                <td><a href="{{ list_by_id_url }}">{{ item.user_name }}</a></td>
                <td>
                    <a href="{{ list_by_ip_url }}">{{ item.user_ip }}</a>
                    <span class="layui-btn layui-btn-xs kg-ip2region" data-ip="{{ item.user_ip }}">位置</span>
                </td>
                <td><a href="{{ list_by_route_url }}">{{ item.req_route }}</a></td>
                <td><a href="{{ list_by_path_url }}">{{ item.req_path }}</a></td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td class="center">
                    <button class="kg-view layui-btn layui-btn-sm" data-url="{{ show_url }}">详情</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/ip2region.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('.kg-view').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    type: 2,
                    title: '数据内容',
                    area: ['640px', '360px'],
                    content: url
                });
            });

        });

    </script>

{% endblock %}