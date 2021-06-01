{% extends 'templates/layer.volt' %}

{% block content %}

    {% set update_url = url({'for':'home.im_group.update','id':group.id}) %}
    {% set name_readonly = group.type == 1 ? 'readonly="readonly"' : '' %}

    <form class="layui-form" method="post" action="{{ update_url }}">
        <div class="layui-form-item">
            <label class="layui-form-label">头像</label>
            <div class="layui-input-inline" style="width: 110px;">
                <img id="img-avatar" class="my-avatar" src="{{ group.avatar }}">
                <input type="hidden" name="avatar" value="{{ group.avatar }}">
            </div>
            <div class="layui-input-inline" style="padding-top:35px;">
                <button id="change-avatar" class="layui-btn layui-btn-sm" type="button">更换</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ group.name }}" {{ name_readonly }} lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">简介</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="about" lay-verify="required">{{ group.about }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button class="layui-btn layui-btn-primary" type="reset">重置</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/upload.avatar.js') }}

{% endblock %}