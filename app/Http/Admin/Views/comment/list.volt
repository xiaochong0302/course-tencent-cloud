{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

    <table class="layui-table kg-table layui-form" lay-size="lg">
        <colgroup>
            <col width="50%">
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>评论</th>
            <th>点赞</th>
            <th>用户</th>
            <th>时间</th>
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
                <td>{{ item.content }}</td>
                <td>{{ item.like_count }}</td>
                <td><a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a></td>
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