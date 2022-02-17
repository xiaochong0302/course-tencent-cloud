{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/group') }}

    {% set add_url = url({'for':'admin.im_group.add'}) %}
    {% set search_url = url({'for':'admin.im_group.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a><cite>群组管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加群组
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索群组
            </a>
        </div>
    </div>

    <table class="kg-table layui-table layui-form">
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
            <th>头像</th>
            <th>名称</th>
            <th>群主</th>
            <th>成员</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set group_url = url({'for':'home.im_group.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.im_group.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.im_group.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.im_group.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.im_group.restore','id':item.id}) %}
            {% set users_url = url({'for':'admin.im_group.users','id':item.id}) %}
            <tr>
                <td class="center">
                    <img class="avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                </td>
                <td>
                    <p>
                        名称：<a href="{{ edit_url }}">{{ item.name }}</a>（{{ item.id }}）
                    </p>
                    <p class="meta">
                        <span>类型：{{ type_info(item.type) }}</span>
                        <span>创建：{{ date('Y-m-d',item.create_time) }}</span>
                    </p>
                </td>
                <td>
                    {% if item.owner.id is defined %}
                        <p>昵称：<a href="{{ url({'for':'home.user.show','id':item.owner.id}) }}" target="_blank">{{ item.owner.name }}</a></p>
                        <p>编号：{{ item.owner.id }}</p>
                    {% else %}
                        N/A
                    {% endif %}
                </td>
                <td><a href="{{ users_url }}" class="layui-badge layui-bg-green">{{ item.user_count }}</a></td>
                <td><input type="checkbox" name="published" value="1" lay-filter="published" lay-skin="switch" lay-text="是|否" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ group_url }}" target="_blank">浏览</a></li>
                            {% endif %}
                            <li><a href="{{ users_url }}">成员</a></li>
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