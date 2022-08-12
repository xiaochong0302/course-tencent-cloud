{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/answer') }}

    {% set published_types = {'0':'全部','1':'审核中','2':'已发布','3':'未通过'} %}
    {% set published = request.get('published','trim','0') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的回答</span>
                    {% for key,value in published_types %}
                        {% set class = (published == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = (key == '0') ? url({'for':'home.uc.answers'}) : url({'for':'home.uc.answers'},{'published':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table review-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col width="15%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>内容</th>
                            <th>点赞</th>
                            <th>评论</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set question_url = url({'for':'home.question.show','id':item.question.id}) %}
                            {% set edit_url = url({'for':'home.answer.edit','id':item.id}) %}
                            {% set delete_url = url({'for':'home.answer.delete','id':item.id}) %}
                            <tr>
                                <td>
                                    <p>提问：<a href="{{ question_url }}" target="_blank">{{ item.question.title }}</a></p>
                                    <p>回答：{{ substr(item.summary,0,32) }}</p>
                                    <p class="meta">
                                        时间：<span class="layui-badge layui-bg-gray">{{ item.create_time|time_ago }}</span>
                                        状态：<span class="layui-badge layui-bg-gray">{{ publish_status(item.published) }}</span>
                                    </p>
                                </td>
                                <td>{{ item.like_count }}</td>
                                <td>{{ item.comment_count }}</td>
                                <td>
                                    <a href="{{ edit_url }}" class="layui-btn layui-btn-xs layui-bg-blue">修改</a>
                                    <a href="javascript:" class="layui-btn layui-btn-xs layui-bg-red kg-delete" data-url="{{ delete_url }}">删除</a>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}