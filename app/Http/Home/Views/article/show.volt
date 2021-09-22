{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/article') }}

    {% set article_edit_url = url({'for':'home.article.edit','id':article.id}) %}
    {% set article_delete_url = url({'for':'home.article.delete','id':article.id}) %}
    {% set article_owner_url = url({'for':'home.user.show','id':article.owner.id}) %}
    {% set article_related_url = url({'for':'home.article.related','id':article.id}) %}
    {% set article_report_url = url({'for':'home.report.add'},{'item_id':article.id,'item_type':106}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>专栏</cite></a>
            <a><cite>详情</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat share-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq share-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo share-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-sticky">
            {{ partial('article/sticky') }}
        </div>
        <div class="layout-content">
            <div class="article-info wrap">
                <div class="title">{{ article.title }}</div>
                <div class="meta">
                    <div class="left">
                        {% if article.published == 1 %}
                            <span class="review layui-badge">审核中</span>
                        {% endif %}
                        <span class="source layui-badge layui-bg-blue">{{ source_type(article.source_type) }}</span>
                        <span class="owner"><a href="{{ article_owner_url }}">{{ article.owner.name }}</a></span>
                        <span class="time">{{ article.create_time|time_ago }}</span>
                        <span class="view">{{ article.view_count }} 阅读</span>
                        <span class="comment">{{ article.comment_count }} 评论</span>
                    </div>
                    <div class="right">
                        <span class="kg-report" data-url="{{ article_report_url }}">举报</span>
                        {% if auth_user.id == article.owner.id %}
                            <span class="article-edit" data-url="{{ article_edit_url }}">编辑</span>
                            <span class="kg-delete" data-url="{{ article_delete_url }}">删除</span>
                        {% endif %}
                    </div>
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
                        <a href="{{ article.source_url }}" target="_blank">前往阅读原文</a>
                    </div>
                {% endif %}
            </div>
            <div id="comment-anchor"></div>
            {% if article.closed == 0 %}
                <div class="article-comment wrap">
                    {{ partial('article/comment') }}
                </div>
            {% else %}
                <div class="wrap center gray">
                    <i class="layui-icon layui-icon-close-fill"></i> 评论已关闭
                </div>
            {% endif %}
        </div>
        <div class="layout-sidebar">
            <div class="sidebar">
                {{ partial('article/show_owner') }}
            </div>
            <div class="sidebar" id="sidebar-related" data-url="{{ article_related_url }}"></div>
        </div>
    </div>

    {% set share_url = full_url({'for':'home.share'},{'id':article.id,'type':'article','referer':auth_user.id}) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ article.title }}">
        <input type="hidden" name="share.pic" value="{{ article.cover }}">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/article.show.js') }}
    {{ js_include('home/js/article.share.js') }}
    {{ js_include('home/js/comment.js') }}

{% endblock %}