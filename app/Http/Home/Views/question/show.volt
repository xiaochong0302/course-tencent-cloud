{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/question') }}

    {% set question_list_url = url({'for':'home.question.list'}) %}
    {% set add_answer_url = url({'for':'home.answer.add'},{'question_id':question.id}) %}
    {% set answer_list_url = url({'for':'home.question.answers','id':question.id}) %}
    {% set related_url = url({'for':'home.question.related','id':question.id}) %}
    {% set owner_url = url({'for':'home.user.show','id':question.owner.id}) %}

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
                    <span class="owner">
                        <a href="{{ owner_url }}">{{ question.owner.name }}</a>
                    </span>
                    <span class="view">{{ question.view_count }} 阅读</span>
                    <span class="answer">{{ question.answer_count }} 回答</span>
                    <span class="time" title="{{ date('Y-m-d H:i:s',question.create_time) }}">{{ question.create_time|time_ago }}</span>
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
            <div class="answer-wrap wrap">
                <div id="answer-list" data-url="{{ answer_list_url }}"></div>
            </div>
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
                                    <a href="{{ owner_url }}" title="{{ question.owner.about }}">{{ question.owner.name }}</a>
                                </div>
                                <div class="title layui-elip">{{ question.owner.title|default('初出江湖') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {% if question.me.answered == 0 %}
                <div class="sidebar wrap">
                    <button class="layui-btn layui-btn-fluid btn-answer" data-url="{{ add_answer_url }}">回答问题</button>
                </div>
            {% endif %}
            <div class="sidebar" id="sidebar-related" data-url="{{ related_url }}"></div>
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