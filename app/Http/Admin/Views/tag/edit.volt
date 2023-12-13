{% extends 'templates/main.volt' %}

{% block content %}

    {% set scope_style = tag2.scopes == 'all' ? 'display:none;' : '' %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.tag.update','id':tag2.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑标签</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">图标</label>
            <div class="layui-input-inline" style="width: 80px;">
                <img id="img-icon" class="kg-icon" src="{{ tag2.icon }}">
                <input type="hidden" name="icon" value="{{ tag2.icon }}">
            </div>
            <div class="layui-input-inline" style="padding-top:15px;">
                <button id="change-icon" class="layui-btn layui-btn-sm" type="button">更换</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ tag2.name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">范围</label>
            <div class="layui-input-block">
                <input type="radio" name="scope_type" value="all" title="全部" lay-filter="scope_type" {% if tag2.scopes == 'all' %}checked="checked"{% endif %}>
                <input type="radio" name="scope_type" value="custom" title="自定" lay-filter="scope_type" {% if tag2.scopes != 'all' %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item" id="scope-block" style="{{ scope_style }}">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                {% for value,title in scope_types %}
                    <input type="checkbox" name="scopes[]" value="{{ value }}" title="{{ title }}" {% if value in tag2.scopes %}checked="checked"{% endif %}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是" {% if tag2.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="否" {% if tag2.published == 0 %}checked="checked"{% endif %}>
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

    {{ js_include('admin/js/icon.upload.js') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(scope_type)', function (data) {
                var block = $('#scope-block');
                if (data.value === 'custom') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}