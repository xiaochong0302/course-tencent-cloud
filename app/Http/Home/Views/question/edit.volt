{% extends 'templates/main.volt' %}

{% block content %}

    {% set title = question.id > 0 ? '编辑问题' : '发布问题' %}
    {% set action_url = question.id > 0 ? url({'for':'home.question.update','id':question.id}) : url({'for':'home.question.create'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>{{ title }}</cite></a>
        </span>
    </div>

    <form class="layui-form" method="POST" action="{{ action_url }}">
        <div class="layout-main">
            <div class="layout-content">
                <div class="writer-content wrap">
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="title" value="{{ question.title }}" placeholder="请输入标题..." lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <textarea name="content" class="layui-hide" id="editor-textarea">{{ question.content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layout-sidebar">
                <div class="writer-sidebar wrap">
                    {% if category_options|length > 0 %}
                        <div class="layui-form-item">
                            <label class="layui-form-label">问题分类</label>
                            <div class="layui-input-block">
                                <select name="category_id" lay-search="true" lay-verify="required">
                                    <option value="">请选择</option>
                                    {% for option in category_options %}
                                        {% set selected = question.category_id == option.id ? 'selected="selected"' : '' %}
                                        <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                                    {% endfor %}
                                </select>
                            </div>
                        </div>
                    {% endif %}
                    {% if xm_tags|length > 0 %}
                        <div class="layui-form-item">
                            <label class="layui-form-label">问题标签</label>
                            <div class="layui-input-block">
                                <div id="xm-tag-ids"></div>
                            </div>
                        </div>
                    {% endif %}
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-fluid kg-submit" lay-submit="true" lay-filter="go">确认发布</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="layui-hide">
        <input type="hidden" name="xm_tags" value='{{ xm_tags|json_encode }}'>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/xm-select.js') }}
    {{ js_include('lib/kindeditor/kindeditor.min.js') }}
    {{ js_include('home/js/content.editor.js') }}
    {{ js_include('home/js/question.edit.js') }}

{% endblock %}
