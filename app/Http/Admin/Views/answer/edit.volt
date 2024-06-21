{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.answer.update','id':answer.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑答案</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">问题标题</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ question.title }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">回答内容</label>
            <div class="layui-input-block">
                <textarea name="content" class="layui-hide" id="editor-textarea">{{ answer.content }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布状态</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    {% set checked = value == answer.published ? 'checked="checked"' : '' %}
                    <input type="radio" name="published" value="{{ value }}" title="{{ title }}" {{ checked }}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-input-block">
            <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="referer" value="{{ request.getHTTPReferer() }}">
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('admin/js/content.editor.js') }}

{% endblock %}