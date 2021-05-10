{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}
    {{ partial('macros/article') }}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>文章审核</cite></a>
            </span>
        </div>
    </div>

    <table class="layui-table kg-table layui-form">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>文章</th>
            <th>作者</th>
            <th>终端</th>
            <th>时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set review_url = url({'for':'admin.article.review','id':item.id}) %}
            <tr>
                <td>
                    <p>标题：{{ item.title }}</p>
                    <p class="meta">
                        <span>来源：{{ source_info(item.source_type,item.source_url) }}</span>
                        {% if item.tags %}
                            <span>标签：{{ tags_info(item.tags) }}</span>
                        {% endif %}
                    </p>
                </td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>类型：{{ client_type(item.client_type) }}</p>
                    <p>地址：<a href="javascript:" class="kg-ip2region" title="查看位置" data-ip="{{ item.client_ip }}">{{ item.client_ip }}</a></p>
                </td>
                <td>
                    {% if item.update_time > 0 %}
                        {{ date('Y-m-d H:i:s',item.update_time) }}
                    {% else %}
                        {{ date('Y-m-d H:i:s',item.create_time) }}
                    {% endif %}
                </td>
                <td class="center">
                    <a href="{{ review_url }}" class="layui-btn layui-btn-sm">详情</a>
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