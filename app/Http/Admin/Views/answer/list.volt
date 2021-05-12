{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/answer') }}

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
            <th>信息</th>
            <th>评论</th>
            <th>点赞</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set question_url = url({'for':'home.question.show','id':item.question.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set review_url = url({'for':'admin.answer.review','id':item.id}) %}
            {% set edit_url = url({'for':'admin.answer.edit','id':item.id}) %}
            {% set delete_url = url({'for':'admin.answer.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.answer.restore','id':item.id}) %}
            <tr>
                <td>
                    <P>问题：<a href="{{ question_url }}" target="_blank">{{ item.question.title }}</a></P>
                    <p>回答：{{ item.summary }}</p>
                    <p>作者：<a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a>　创建：{{ date('Y-m-d',item.create_time) }}</p>
                </td>
                <td>{{ item.comment_count }}</td>
                <td>{{ item.like_count }}</td>
                <td>{{ publish_status(item.published) }}</td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ review_url }}">审核回答</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑回答</a></li>
                            {% if item.deleted == 0 %}
                                <a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除回答</a>
                            {% else %}
                                <a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原回答</a>
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