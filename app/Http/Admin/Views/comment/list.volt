{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/common') }}

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
            <th>评论</th>
            <th>用户</th>
            <th>终端</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set update_url = url({'for':'admin.comment.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.comment.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.comment.restore','id':item.id}) %}
            <tr>
                <td>
                    <p>内容：<a href="javascript:" title="{{ item.content }}">{{ substr(item.content,0,30) }}</a></p>
                    <p>时间：{{ date('Y-m-d H:i',item.create_time) }}，点赞：{{ item.like_count }}</p>
                </td>
                <td>
                    <p>昵称：{{ item.owner.name }}</p>
                    <p>编号：{{ item.owner.id }}</p>
                </td>
                <td>
                    <p>类型：{{ client_type(item.client_type) }}</p>
                    <p>地址：<a href="javascript:" class="kg-ip2region" data-ip="{{ item.client_ip }}">查看</a></p>
                </td>
                <td><input type="checkbox" name="published" value="1" lay-skin="switch" lay-text="是|否" lay-filter="published" data-url="{{ update_url }}" {% if item.published == 1 %}checked="checked"{% endif %}></td>
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

{% block include_js %}

    {{ js_include('admin/js/ip2region.js') }}

{% endblock %}