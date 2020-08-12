{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.im_group.update','id':group.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑群组</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">头像</label>
            <div class="layui-input-inline" style="width: 110px;">
                <img id="img-avatar" class="kg-avatar" src="{{ group.avatar }}">
                <input type="hidden" name="avatar" value="{{ group.avatar }}">
            </div>
            <div class="layui-input-inline" style="padding-top:35px;">
                <button id="change-avatar" class="layui-btn layui-btn-sm" type="button">更换</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ group.name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">简介</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="about">{{ group.about }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">群主编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" value="{{ group.owner_id }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是" {% if group.published == 1 %}checked{% endif %}>
                <input type="radio" name="published" value="0" title="否" {% if group.published == 0 %}checked{% endif %}>
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

    {{ js_include('admin/js/avatar.upload.js') }}

{% endblock %}