{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.page.update','id':page.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑单页</legend>
    </fieldset>

    <div class="layui-tabs">
        <ul class="layui-tabs-header">
            <li class="layui-this">基本信息</li>
            <li>详细内容</li>
            <li>搜索优化</li>
        </ul>
        <div class="layui-tabs-body">
            <div class="layui-tabs-item layui-show">
                {{ partial('page/edit_basic') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('page/edit_desc') }}
            </div>
            <div class="layui-tabs-item">
                {{ partial('page/edit_seo') }}
            </div>
        </div>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('lib/vditor/dist/index.css') }}

{% endblock %}

{% block include_js %}

    {{ js_include('lib/vditor/dist/index.min.js') }}
    {{ js_include('admin/js/content.vditor.js') }}

{% endblock %}
