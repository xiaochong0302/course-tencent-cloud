{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/slide') }}

    {% set slide.target_attrs = array_object(slide.target_attrs) %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.slide.update','id':slide.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑轮播</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">目标类型</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ target_info(slide.target) }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">目标信息</label>
            <div class="layui-input-block">
                <div class="layui-form-mid">{{ target_attrs_info(slide.target_attrs) }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">封面</label>
            <div class="layui-input-inline">
                <img id="img-cover" class="kg-cover" src="{{ slide.cover }}">
                <input type="hidden" name="cover" value="{{ slide.cover }}">
            </div>
            <div class="layui-input-inline" style="padding-top:35px;">
                <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ slide.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ slide.priority }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是" {% if slide.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="否" {% if slide.published == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('admin/js/cover.upload.js') }}

{% endblock %}