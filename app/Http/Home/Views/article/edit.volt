{% extends 'templates/main.volt' %}

{% block content %}

    {% set title = article.id > 0 ? '编辑文章' : '撰写文章' %}
    {% set action_url = article.id > 0 ? url({'for':'home.article.update','id':article.id}) : url({'for':'home.article.create'}) %}
    {% set source_url_display = article.source_type == 1 ? 'display:none;' : 'display:block;' %}

    <div class="writer-breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>{{ title }}</cite></a>
        </span>
        <span class="publish">
            <a href="javascript:" class="layui-btn layui-btn-sm">发布文章 <i class="layui-icon layui-icon-triangle-d"></i></a>
        </span>
    </div>

    <div class="layout-main">
        <form class="layui-form" method="POST" action="{{ action_url }}">
            <div class="writer-content wrap">
                <div class="layui-form-item first-form-item">
                    <div class="layui-input-block">
                        <input class="layui-input" type="text" name="title" value="{{ article.title }}" placeholder="请输入标题..." lay-verify="required">
                    </div>
                </div>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <div id="vditor"></div>
                        <textarea name="content" class="layui-hide" id="vditor-textarea">{{ article.content }}</textarea>
                    </div>
                </div>
            </div>
            <div id="layer-publish" style="display:none;">
                <div class="writer-sidebar">
                    <div class="layui-form-item">
                        <label class="layui-form-label">分类标签</label>
                        <div class="layui-input-block">
                            <div id="xm-tag-ids"></div>
                        </div>
                    </div>
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
                    <div class="layui-form-item last-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-fluid kg-submit" lay-submit="true" lay-filter="go">确认发布</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="layui-hide">
        <input type="hidden" name="xm_tags" value='{{ xm_tags|json_encode }}'>
    </div>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('lib/xm-select.js') }}
    {{ js_include('home/js/article.edit.js') }}
    {{ js_include('home/js/vditor.js') }}

{% endblock %}