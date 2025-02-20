{% extends 'templates/main.volt' %}

{% block content %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>编辑回答</cite></a>
        </span>
    </div>

    <div class="layout-main">
        <div class="layout-content">
            <div class="writer-content wrap">
                <form class="layui-form" method="POST" action="{{ url({'for':'home.answer.update','id':answer.id}) }}">
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="title" value="{{ question.title }}" disabled="disabled">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <textarea name="content" class="layui-hide" id="editor-textarea">{{ answer.content }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item center">
                        <div class="layui-input-block">
                            <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">发布回答</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="layout-sidebar">
            <div class="writer-sidebar wrap">
                {{ partial('answer/tips') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('home/js/content.editor.js') }}

{% endblock %}
