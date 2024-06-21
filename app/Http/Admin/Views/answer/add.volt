{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.answer.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>回答问题</legend>
        </fieldset>
        <div class="layui-form-item">
            <div class="layui-input-block" style="margin:0;">
                <input class="layui-input" type="text" name="title" value="{{ question.title }}" readonly="readonly">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block" style="margin:0;">
                <textarea name="content" class="layui-hide" id="editor-textarea"></textarea>
            </div>
        </div>
        <div class="layui-input-block kg-center" style="margin:0;">
            <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="referer" value="{{ request.getHTTPReferer() }}">
            <input type="hidden" name="question_id" value="{{ question.id }}">
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('admin/js/content.editor.js') }}

{% endblock %}