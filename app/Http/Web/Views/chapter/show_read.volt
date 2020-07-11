{% extends 'templates/full.volt' %}

{% block content %}

    {% set learning_url = url({'for':'web.chapter.learning','id':chapter.id}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a><cite>{{ chapter.course.title }}</cite></a>
            <a><cite>{{ chapter.title }}</cite></a>
        </span>
    </div>

    <div class="layout-main clearfix">
        <div class="layout-content">
            <div class="read-info wrap">{{ chapter.content }}</div>
        </div>
        <div class="layout-sidebar">
            {{ partial('chapter/menu') }}
        </div>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="chapter.id" value="{{ chapter.id }}">
        <input type="hidden" name="chapter.plan_id" value="{{ chapter.me.plan_id }}">
        <input type="hidden" name="chapter.learning_url" value="{{ learning_url }}">
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/read.js') }}

{% endblock %}