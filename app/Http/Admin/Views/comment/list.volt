{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

    {% set batch_delete_url = url({'for':'admin.comment.batch_delete'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                {% if request.get('item_id') > 0 %}
                    <a class="kg-back"><i class="layui-icon layui-icon-return"></i>返回</a>
                {% endif %}
                <a><cite>评论管理</cite></a>
            </span>
            <span class="layui-btn layui-btn-sm layui-bg-red kg-batch" data-url="{{ batch_delete_url }}">批量删除</span>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col width="50%">
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>作者</th>
            <th>内容</th>
            <th>回复</th>
            <th>点赞</th>
            <th>创建</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set update_url = url({'for':'admin.comment.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.comment.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.comment.restore','id':item.id}) %}
            <tr>
                <td><a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a>（{{ item.owner.id }}）</td>
                <td>
                    <p class="layui-elip kg-item-elip" title="{{ item.content }}">{{ item.content }}</p>
                </td>
                <td>{{ item.reply_count }}</td>
                <td>{{ item.like_count }}</td>
                <td>{{ date('Y-m-d',item.create_time) }}</td>
                <td class="center">
                    {% if item.deleted == 0 %}
                        <a href="javascript:" class="layui-badge layui-bg-red kg-delete" data-url="{{ delete_url }}">删除</a>
                    {% else %}
                        <a href="javascript:" class="layui-badge layui-bg-green kg-restore" data-url="{{ restore_url }}">还原</a>
                    {% endif %}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}