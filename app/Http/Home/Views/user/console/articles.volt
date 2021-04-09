{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    <div class="layout-main clearfix">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的文章</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>文章</th>
                            <th>浏览</th>
                            <th>点赞</th>
                            <th>评论</th>
                            <th>收藏</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set article_url = url({'for':'home.article.show','id':item.id}) %}
                            <tr>
                                <td>
                                    <p>标题：<a href="{{ article_url }}" target="_blank">{{ item.title }}</a></p>
                                    <p class="meta">
                                        来源：<span class="layui-badge layui-bg-gray">{{ source_type(item.source_type) }}</span>
                                        分类：<span class="layui-badge layui-bg-gray">{{ item.category.name }}</span>
                                        时间：{{ item.create_time|time_ago }}
                                    </p>
                                </td>
                                <td>{{ item.view_count }}</td>
                                <td>{{ item.like_count }}</td>
                                <td>{{ item.comment_count }}</td>
                                <td>{{ item.favorite_count }}</td>
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

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}