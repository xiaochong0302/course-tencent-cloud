{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/question') }}

    {% set share_url = share_url('question',question.id,auth_user.id) %}
    {% set qrcode_url = url({'for':'home.qrcode'},{'text':share_url}) %}
    {% set question_report_url = url({'for':'home.report.add'},{'item_id':question.id,'item_type':107}) %}
    {% set question_edit_url = url({'for':'home.question.edit','id':question.id}) %}
    {% set question_delete_url = url({'for':'home.question.delete','id':question.id}) %}
    {% set question_show_url = url({'for':'home.question.show','id':question.id}) %}
    {% set question_owner_url = url({'for':'home.user.show','id':question.owner.id}) %}
    {% set question_related_url = url({'for':'home.question.related','id':question.id}) %}
    {% set answer_add_url = url({'for':'home.answer.add'},{'question_id':question.id}) %}
    {% set answer_list_url = url({'for':'home.question.answers','id':question.id}) %}
    {% set answer_id = request.getQuery('answer_id','int',0) %}
    {% set answer_url = url({'for':'home.answer.info','id':answer_id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>问答</cite></a>
            <a><cite>详情</cite></a>
        </span>
        <span class="share">
            <a class="share-wechat" href="javascript:" title="分享到微信"><i class="layui-icon layui-icon-login-wechat"></i></a>
            <a class="share-qq" href="javascript:" title="分享到QQ空间"><i class="layui-icon layui-icon-login-qq"></i></a>
            <a class="share-weibo" href="javascript:" title="分享到微博"><i class="layui-icon layui-icon-login-weibo"></i></a>
            <a class="share-link kg-copy" href="javascript:" title="复制链接" data-clipboard-text="{{ share_url }}"><i class="layui-icon layui-icon-share"></i></a>
        </span>
    </div>

    <div class="layout-main">
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
                        <span class="kg-report" data-url="{{ question_report_url }}">举报</span>
                        {% if question.me.owned == 1 %}
                            <span class="question-edit" data-url="{{ question_edit_url }}">编辑</span>
                            <span class="question-delete" data-url="{{ question_delete_url }}">删除</span>
                        {% endif %}
                    </div>
                </div>
                <div class="content ke-content kg-zoom">{{ question.content }}</div>
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
            {% if question.closed == 1 %}
                <div class="wrap center gray">
                    <i class="layui-icon layui-icon-close-fill"></i> 问题已关闭
                </div>
            {% endif %}
            {% if answer_id > 0 %}
                <div class="answer-wrap wrap">
                    <div id="answer-info" data-url="{{ answer_url }}"></div>
                </div>
                {% if question.answer_count > 0 %}
                    <div class="center wrap">
                        <a class="green" href="{{ question_show_url }}">查看全部 {{ question.answer_count }} 个回答</a>
                    </div>
                {% endif %}
            {% elseif question.answer_count > 0 %}
                <div class="answer-wrap wrap">
                    {{ partial('question/show_answers') }}
                </div>
            {% endif %}
        </div>
        <div class="layout-sidebar">
            <div class="sidebar">
                {{ partial('question/show_owner') }}
            </div>
            {% if question.me.allow_answer == 1 %}
                <div class="sidebar wrap">
                    <button class="layui-btn layui-btn-danger layui-btn-fluid btn-answer" data-url="{{ answer_add_url }}">回答问题</button>
                </div>
            {% endif %}
            <div class="sidebar" id="sidebar-related" data-url="{{ question_related_url }}"></div>
        </div>
    </div>

    <div class="layout-sticky">
        {{ partial('question/sticky') }}
    </div>

    <div class="layui-hide">
        <input type="hidden" name="share.title" value="{{ question.title }}">
        <input type="hidden" name="share.pic" value="">
        <input type="hidden" name="share.url" value="{{ share_url }}">
        <input type="hidden" name="share.qrcode" value="{{ qrcode_url }}">
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/question.show.js') }}
    {{ js_include('home/js/question.share.js') }}
    {{ js_include('home/js/answer.js') }}
    {{ js_include('home/js/comment.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}