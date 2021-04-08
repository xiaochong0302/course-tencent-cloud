{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    {% set article_list_url = url({'for':'home.article.list'}) %}
    {% set article_cate_url = url({'for':'home.article.list'},{'category_id':article.category.id}) %}
    {% set owner_url = url({'for':'home.user.show','id':article.owner.id}) %}
    {% set favorite_url = url({'for':'home.article.favorite','id':article.id}) %}
    {% set like_url = url({'for':'home.article.like','id':article.id}) %}
    {% set favorited_class = article.me.favorited ? 'layui-icon-star-fill' : 'layui-icon-star' %}
    {% set liked_class = article.me.liked ? 'active' : '' %}
    {% set article.owner.title = article.owner.title ? article.owner.title : '默默无名' %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ article_list_url }}">专栏</a>
            <a href="{{ article_cate_url }}">{{ article.category.name }}</a>
            <a><cite>文章详情</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">

        <div class="action-sticky">
            <div class="item">
                <div class="icon" data-url="{{ like_url }}">
                    <i class="layui-icon layui-icon-praise icon-praise {{ liked_class }}"></i>
                </div>
                <div class="text">{{ article.like_count }}</div>
            </div>
            <div class="item">
                <div class="icon icon-reply">
                    <i class="layui-icon layui-icon-reply-fill"></i>
                </div>
                <div class="text">{{ article.comment_count }}</div>
            </div>
            <div class="item">
                <div class="icon" data-url="{{ favorite_url }}">
                    <i class="layui-icon layui-icon-star icon-star {{ favorited_class }}"></i>
                </div>
                <div class="text">{{ article.favorite_count }}</div>
            </div>
        </div>

        <div class="layout-content">
            <div class="article-info wrap">
                <div class="title">{{ article.title }}</div>
                <div class="meta">
                    <span class="source layui-badge layui-bg-green">{{ source_type(article.source_type) }}</span>
                    <span class="owner">
                        <a href="{{ owner_url }}">{{ article.owner.name }}</a>
                    </span>
                    <span class="view">{{ article.view_count }} 阅读</span>
                    <span class="word">{{ article.word_count }} 字数</span>
                    <span class="time">{{ article.create_time|time_ago }}</span>
                </div>
                <div class="content markdown-body">{{ article.content }}</div>
                {% if article.tags %}
                    <div class="tags">
                        {% for item in article.tags %}
                            {% set url = url({'for':'home.article.list'},{'tag_id':item.id}) %}
                            <a href="{{ url }}" class="layui-btn layui-btn-xs">{{ item.name }}</a>
                        {% endfor %}
                    </div>
                {% endif %}
                {% if article.source_type == 1 %}
                    <div class="source-tips">本作品系原创，转载请注明出处</div>
                {% elseif article.source_url %}
                    <div class="source-tips">
                        <a href="{{ article.source_url }}" target="_blank">阅读原文</a>
                    </div>
                {% endif %}
            </div>
            <div class="comment-wrap" id="comment-wrap">
                <div class="comment-form">

                </div>
                <div class="comment-list">

                </div>
            </div>
        </div>

        {% set related_article_url = url({'for':'home.article.related','id':article.id}) %}

        <div class="layout-sidebar">
            <div class="sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">关于作者</div>
                    <div class="layui-card-body">
                        <div class="sidebar-user-card clearfix">
                            <div class="avatar">
                                <img src="{{ article.owner.avatar }}!avatar_160" alt="{{ article.owner.name }}">
                            </div>
                            <div class="info">
                                <div class="name layui-elip">
                                    <a href="{{ owner_url }}" title="{{ article.owner.about }}">{{ article.owner.name }}</a>
                                </div>
                                <div class="title layui-elip">{{ article.owner.title }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar" id="related-article-list" data-url="{{ related_article_url }}"></div>
        </div>

    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/article.show.js') }}

{% endblock %}