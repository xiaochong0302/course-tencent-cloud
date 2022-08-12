{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    {% set published_types = {'0':'全部','1':'审核中','2':'已发布','3':'未通过'} %}
    {% set published = request.get('published','trim','0') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的文章</span>
                    {% for key,value in published_types %}
                        {% set class = (published == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = (key == '0') ? url({'for':'home.uc.articles'}) : url({'for':'home.uc.articles'},{'published':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col width="15%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>文章</th>
                            <th>浏览</th>
                            <th>点赞</th>
                            <th>评论</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set show_url = url({'for':'home.article.show','id':item.id}) %}
                            {% set edit_url = url({'for':'home.article.edit','id':item.id}) %}
                            {% set delete_url = url({'for':'home.article.delete','id':item.id}) %}
                            <tr>
                                <td>
                                    {% if item.published == 2 %}
                                        <p>标题：<a href="{{ show_url }}" target="_blank">{{ item.title }}</a></p>
                                    {% else %}
                                        <p>标题：<a href="{{ edit_url }}" target="_blank">{{ item.title }}</a></p>
                                    {% endif %}
                                    <p class="meta">
                                        来源：<span class="layui-badge layui-bg-gray">{{ source_type(item.source_type) }}</span>
                                        时间：<span class="layui-badge layui-bg-gray">{{ item.create_time|time_ago }}</span>
                                        状态：<span class="layui-badge layui-bg-gray">{{ publish_status(item.published) }}</span>
                                    </p>
                                </td>
                                <td>{{ item.view_count }}</td>
                                <td>{{ item.like_count }}</td>
                                <td>{{ item.comment_count }}</td>
                                <td class="center">
                                    <a href="{{ edit_url }}" class="layui-btn layui-btn-xs layui-bg-blue">编辑</a>
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