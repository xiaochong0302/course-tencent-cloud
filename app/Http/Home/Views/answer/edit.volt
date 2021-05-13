{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>编辑回答</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="writer-content wrap">
            <form class="layui-form" method="POST" action="{{ url({'for':'home.answer.update','id':answer.id}) }}">
                <div class="layui-form-item">
                    <div class="layui-input-block" style="margin:0;">
                        <input class="layui-input" type="text" name="title" value="{{ question.title }}" readonly="readonly">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block" style="margin:0;">
                        <div id="vditor"></div>
                        <textarea name="content" class="layui-hide" id="vditor-textarea">{{ answer.content }}</textarea>
                    </div>
                </div>
                <div class="layui-form-item center">
                    <div class="layui-input-block" style="margin:0;">
                        <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">发布回答</button>
                        <input type="hidden" name="referer" value="{{ referer }}">
                    </div>
                </div>
            </form>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('home/js/answer.js') }}
    {{ js_include('home/js/vditor.js') }}

{% endblock %}