{% extends 'templates/main.volt' %}

{% block content %}

    <div class="page-info wrap">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>{{ page.title }}</legend>
            <div class="layui-field-box page-content" id="preview">
                {{ page.content }}
            </div>
        </fieldset>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/method.min.js', false) }}
    {{ js_include('web/js/markdown.preview.js') }}

{% endblock %}