{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form" method="POST" action="{{ url({'for':'home.answer.update','id':answer.id}) }}">
        <div class="layui-form-item">
            <div class="layui-input-block" style="margin:0;">
                <div id="vditor"></div>
                <textarea name="content" class="layui-hide" id="vditor-textarea">{{ answer.content }}</textarea>
            </div>
        </div>
        <div class="layui-form-item center">
            <div class="layui-input-block" style="margin:0;">
                <button class="layui-btn kg-submit" lay-submit="true" lay-filter="edit_answer">提交回答</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('home/js/answer.js') }}
    {{ js_include('home/js/vditor.js') }}

{% endblock %}