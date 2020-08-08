{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro content_label(target) %}
        {% if target == 'course' %}
            课程编号
        {% elseif target == 'page' %}
            单页编号
        {% elseif target == 'link' %}
            链接地址
        {% endif %}
    {%- endmacro %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.carousel.update','id':carousel.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑轮播</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">封面</label>
            <div class="layui-input-inline">
                <img id="img-cover" class="kg-cover" src="{{ carousel.cover }}">
                <input type="hidden" name="cover" value="{{ carousel.cover }}">
            </div>
            <div class="layui-input-inline" style="padding-top:35px;">
                <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">背景色</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="style[bg_color]" value="{{ carousel.style['bg_color'] }}" lay-verify="required">
            </div>
            <div class="layui-inline">
                <div id="style-bg-color"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ carousel.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">概要</label>
            <div class="layui-input-block">
                <textarea class="layui-textarea" name="summary">{{ carousel.summary }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ content_label(carousel.target) }}</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="content" value="{{ carousel.content }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ carousel.priority }}" lay-verify="number">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是" {% if carousel.published == 1 %}checked{% endif %}>
                <input type="radio" name="published" value="0" title="否" {% if carousel.published == 0 %}checked{% endif %}>
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

{% block inline_js %}

    <script>

        layui.use(['jquery', 'colorpicker'], function () {

            var $ = layui.jquery;
            var colorPicker = layui.colorpicker;

            colorPicker.render({
                elem: '#style-bg-color',
                color: '{{ carousel.style['bg_color'] }}',
                predefine: true,
                change: function (color) {
                    $('input[name="style[bg_color]"]').val(color);
                }
            });

        });

    </script>

{% endblock %}