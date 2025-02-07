{% extends 'templates/main.volt' %}

{% block content %}

    {% set search_url = url({'for':'admin.audit.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>操作记录</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索记录
            </a>
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
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'admin.audit.show','id':item.id}) %}
            <tr>
                <td>{{ item.user_id }}</td>
                <td>{{ item.user_name }}</td>
                <td>
                    <a href="javascript:" class="kg-ip2region" title="查看位置" data-ip="{{ item.user_ip }}">{{ item.user_ip }}</a>
                </td>
                <td>{{ item.req_route }}</td>
                <td>{{ item.req_path }}</td>
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
