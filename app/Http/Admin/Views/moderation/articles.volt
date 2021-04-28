{% extends 'templates/main.volt' %}

{% block content %}

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
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>文章</th>
            <th>作者</th>
            <th>来源</th>
            <th>评论</th>
            <th>时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set show_url = url({'for':'admin.article.show','id':item.id}) %}
            <tr>
                <td>
                    <p>标题：{{ item.title }}（{{ item.id }}）</p>
                    <p class="meta">
                        {% if item.category.id is defined %}
                            <span>分类：{{ item.category.name }}</span>
                        {% endif %}
                        {% if item.tags %}
                            <span>标签：{{ tags_info(item.tags) }}</span>
                        {% endif %}
                    </p>
                </td>
                <td>
                    <p>昵称：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>{{ source_info(item.source_type,item.source_url) }}</td>
                <td>
                    {% if item.allow_comment == 1 %}
                        开启
                    {% else %}
                        关闭
                    {% endif %}
                </td>
                <td>
                    {% if item.update_time > 0 %}
                        {{ date('Y-m-d H:i:s',item.update_time) }}
                    {% else %}
                        {{ date('Y-m-d H:i:s',item.create_time) }}
                    {% endif %}
                </td>
                <td class="center">
                    <a href="{{ show_url }}" class="layui-btn layui-btn-sm">详情</a>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}