{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    {% set add_url = url({'for':'admin.user.add'}) %}
    {% set search_url = url({'for':'admin.user.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>用户管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加用户
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索用户
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
            <th>用户头像</th>
            <th>基本信息</th>
            <th>帐号信息</th>
            <th>用户角色</th>
            <th>统计信息</th>
            <th>活跃动态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set account_phone = item.account.phone ? item.account.phone : 'N/A' %}
            {% set account_email = item.account.email ? item.account.email : 'N/A' %}
            {% set user_url = url({'for':'home.user.show','id':item.id}) %}
            {% set online_url = url({'for':'admin.user.online','id':item.id}) %}
            {% set edit_url = url({'for':'admin.user.edit','id':item.id}) %}
            {% set delete_url = url({'for':'admin.user.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.user.restore','id':item.id}) %}
            <tr>
                <td class="center">
                    <img class="kg-avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                </td>
                <td>
                    <p>昵称：<a href="{{ edit_url }}">{{ item.name }}</a>{{ status_info(item) }}</p>
                    <p>编号：{{ item.id }}</p>
                </td>
                <td>
                    <p>手机：{{ account_phone }}</p>
                    <p>邮箱：{{ account_email }}</p>
                </td>
                <td>
                    <p>教学：{{ edu_role_info(item.edu_role) }}</p>
                    <p>后台：{{ admin_role_info(item.admin_role) }}</p>
                </td>
                <td>
                    <p class="meta">
                        <span>文章：{{ item.article_count }}</span>
                        <span>评论：{{ item.comment_count }}</span>
                    </p>
                    <p class="meta">
                        <span>提问：{{ item.question_count }}</span>
                        <span>回答：{{ item.answer_count }}</span>
                    </p>
                </td>
                <td>
                    <p>注册：{{ date('Y-m-d',item.create_time) }}</p>
                    <p>活跃：{{ date('Y-m-d',item.active_time) }}</p>
                </td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ user_url }}" target="_blank">用户主页</a></li>
                            <li><a href="javascript:" class="kg-online" data-url="{{ online_url }}">在线记录</a></li>
                            {% if item.admin_role.id != 1 %}
                                <li><a href="{{ edit_url }}">编辑用户</a></li>
                                {% if item.deleted == 0 %}
                                    <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除用户</a></li>
                                {% else %}
                                    <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原用户</a></li>
                                {% endif %}
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

{% block inline_js %}

    <script>

        layui.define(['jquery', 'layer'], function () {

            var $ = layui.jquery;
            var layer = layui.layer;

            $('.kg-online').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    type: 2,
                    title: '在线记录',
                    area: ['800px', '600px'],
                    content: url
                });
            });

        });

    </script>

{% endblock %}