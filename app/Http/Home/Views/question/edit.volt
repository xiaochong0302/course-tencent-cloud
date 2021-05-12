{% extends 'templates/main.volt' %}

{% block content %}

    {% set title = question.id > 0 ? '编辑问题' : '提问题' %}
    {% set action_url = question.id > 0 ? url({'for':'home.question.update','id':question.id}) : url({'for':'home.question.create'}) %}

    <div class="breadcrumb">
        <span class="layui-breadcrumb">
            <a href="/">首页</a>
            <a><cite>{{ title }}</cite></a>
        </span>
    </div>

    <form class="layui-form" method="POST" action="{{ action_url }}">
        <div class="layout-main clearfix">
            <div class="layout-content">
                <div class="writer-content wrap">
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <input class="layui-input" type="text" name="title" value="{{ question.title }}" placeholder="请输入标题..." lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <div id="vditor"></div>
                            <textarea name="content" class="layui-hide" id="vditor-textarea">{{ question.content }}</textarea>
                        </div>
                    </div>
                    <div class="layui-form-item center">
                        <div class="layui-input-block">
                            <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">发布问题</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="layout-sidebar">
                <div class="layui-card">
                    <div class="layui-card-header">基本信息</div>
                    <div class="layui-card-body">
                        <div class="writer-sidebar">
                            <div class="layui-form-item">
                                <label class="layui-form-label">标签</label>
                                <div class="layui-input-block">
                                    <div id="xm-tag-ids"></div>
                                    <input type="hidden" name="xm_tags" value='{{ xm_tags|json_encode }}'>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('lib/xm-select.js') }}
    {{ js_include('home/js/question.edit.js') }}
    {{ js_include('home/js/vditor.js') }}

{% endblock %}