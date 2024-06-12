{% extends 'templates/main.volt' %}

{% block content %}

    {% set title = article.id > 0 ? '编辑文章' : '发布文章' %}
    {% set action_url = article.id > 0 ? url({'for':'home.article.update','id':article.id}) : url({'for':'home.article.create'}) %}
    {% set source_url_display = article.source_type == 1 ? 'display:none;' : 'display:block;' %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>{{ title }}</cite></a>
        </span>
    </div>

    <form class="layui-form" method="POST" action="{{ action_url }}">
        <div class="layout-main">
            <div class="layout-content writer-content wrap">
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <input class="layui-input" type="text" name="title" value="{{ article.title }}" placeholder="请输入标题..." lay-verify="required">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <textarea name="content" class="layui-hide" id="editor-textarea">{{ article.content }}</textarea>
                    </div>
                </div>
            </div>
            <div class="layout-sidebar writer-sidebar wrap">
                {% if category_options|length > 0 %}
                    <div class="layui-form-item">
                        <label class="layui-form-label">文章分类</label>
                        <div class="layui-input-block">
                            <select name="category_id" lay-search="true" lay-verify="required">
                                <option value="">请选择</option>
                                {% for option in category_options %}
                                    {% set selected = article.category_id == option.id ? 'selected="selected"' : '' %}
                                    <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                                {% endfor %}
                            </select>
                        </div>
                    </div>
                {% endif %}
                {% if xm_tags|length > 0 %}
                    <div class="layui-form-item">
                        <label class="layui-form-label">文章标签</label>
                        <div class="layui-input-block">
                            <div id="xm-tag-ids"></div>
                        </div>
                    </div>
                {% endif %}
                <div class="layui-form-item">
                    <label class="layui-form-label">来源类型</label>
                    <div class="layui-input-block">
                        <select name="source_type" lay-filter="source_type" lay-verify="required">
                            <option value="">请选择</option>
                            {% for value,title in source_types %}
                                <option value="{{ value }}" {% if article.source_type == value %}selected="selected"{% endif %}>{{ title }}</option>
                            {% endfor %}
                        </select>
                    </div>
                </div>
                <div id="source-url-block" style="{{ source_url_display }}">
                    <div class="layui-form-item">
                        <label class="layui-form-label">来源网址</label>
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="source_url" value="{{ article.source_url }}">
                        </div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn layui-btn-fluid kg-submit" lay-submit="true" lay-filter="go">确认发布</button>
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
    {{ js_include('home/js/article.edit.js') }}

{% endblock %}