{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/question') }}

    {% set question_list_url = url({'for':'home.question.list'}) %}
    {% set question_report_url = url({'for':'home.question.report','id':question.id}) %}
    {% set question_edit_url = url({'for':'home.question.edit','id':question.id}) %}
    {% set question_delete_url = url({'for':'home.question.delete','id':question.id}) %}
    {% set question_owner_url = url({'for':'home.user.show','id':question.owner.id}) %}
    {% set question_related_url = url({'for':'home.question.related','id':question.id}) %}
    {% set answer_add_url = url({'for':'home.answer.add'},{'question_id':question.id}) %}
    {% set answer_list_url = url({'for':'home.question.answers','id':question.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a href="{{ question_list_url }}">问答</a>
            <a><cite>详情</cite></a>
        </span>
        <span class="share">
            <a href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat icon-wechat"></i></a>
            <a href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq icon-qq"></i></a>
            <a href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo icon-weibo"></i></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-sticky">
            {{ partial('question/sticky') }}
        </div>
        <div class="layout-content">
            <div class="article-info wrap">
                <div class="title">{{ question.title }}</div>
                <div class="meta">
                    <div class="left">
                        {% if question.published == 1 %}
                            <span class="review layui-badge">审核中</span>
                        {% endif %}
                        <span class="owner"><a href="{{ question_owner_url }}">{{ question.owner.name }}</a></span>
                        <span class="time">{{ question.create_time|time_ago }}</span>
                        <span class="view">{{ question.view_count }} 阅读</span>
                        <span class="answer">{{ question.answer_count }} 回答</span>
                    </div>
                    <div class="right">
                        <span class="question-report" data-url="{{ question_report_url }}">举报</span>
                        {% if auth_user.id == question.owner.id %}
                            <span class="question-edit" data-url="{{ question_edit_url }}">编辑</span>
                            <span class="question-delete" data-url="{{ question_delete_url }}">删除</span>
                        {% endif %}
                    </div>
                </div>
                <div class="content markdown-body">{{ question.content }}</div>
                {% if question.tags %}
                    <div class="tags">
                        {% for item in question.tags %}
                            {% set url = url({'for':'home.question.list'},{'tag_id':item.id}) %}
                            <a href="{{ url }}" class="layui-btn layui-btn-xs">{{ item.name }}</a>
                        {% endfor %}
                    </div>
                {% endif %}
            </div>
            <div id="answer-anchor"></div>
            {% if question.me.answered == 0 %}
                <div class="answer-wrap wrap">
                    <button class="layui-btn layui-btn-fluid btn-answer" data-url="{{ answer_add_url }}">回答问题</button>
                </div>
            {% endif %}
            {% if question.answer_count > 0 %}
                <div class="answer-wrap wrap">
                    <div class="layui-tab layui-tab-brief search-tab">
                        <ul class="layui-tab-title">
                            <li class="layui-this" data-url="{{ answer_list_url }}?sort=popular">热门回答</li>
                            <li data-url="{{ answer_list_url }}?sort=latest">最新回答</li>
                        </ul>
                        <div class="layui-tab-content">
                            <div class="layui-tab-item layui-show">
                                <div id="answer-list" data-url="{{ answer_list_url }}?sort=popular"></div>
                            </div>
                        </div>
                    </div>
                </div>
            {% endif %}
        </div>
        <div class="layout-sidebar">
            <div class="sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">关于作者</div>
                    <div class="layui-card-body">
                        <div class="sidebar-user-card clearfix">
                            <div class="avatar">
                                <img src="{{ question.owner.avatar }}!avatar_160" alt="{{ question.owner.name }}">
                            </div>
                            <div class="info">
                                <div class="name layui-elip">
                                    <a href="{{ question_owner_url }}" title="{{ question.owner.about }}">{{ question.owner.name }}</a>
                                </div>
                                <div class="title layui-elip">{{ question.owner.title|default('初出江湖') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar" id="sidebar-related" data-url="{{ question_related_url }}"></div>
        </div>
    </div>

    {% set share_url = full_url({'for':'home.share'},{'id':question.id,'type':'question','referer':auth_user.id}) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ question.title }}">
        <input type="hidden" name="share.pic" value="">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/markdown.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/question.show.js') }}
    {{ js_include('home/js/question.share.js') }}
    {{ js_include('home/js/answer.js') }}

{% endblock %}