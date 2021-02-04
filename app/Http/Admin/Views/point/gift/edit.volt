{% extends 'templates/main.volt' %}

{% block content %}

    {% set update_url = url({'for':'admin.point_gift.update','id':gift.id}) %}

    {% if gift.type == 1 %}
        <form class="layui-form kg-form" method="POST" action="{{ update_url }}">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>编辑礼品</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">课程封面</label>
                <div class="layui-input-inline">
                    <img id="img-cover" class="kg-cover" src="{{ gift.cover }}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">课程名称</label>
                <div class="layui-form-mid layui-word-aux">{{ gift.name }}</div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">所需积分</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="point" value="{{ gift.point }}" lay-verify="number">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">库存数量</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="stock" value="{{ gift.stock }}" lay-verify="number">
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
    {% endif %}

    {% if gift.type == 2 %}
        <form class="layui-form kg-form" method="POST" action="{{ update_url }}">
            <fieldset class="layui-elem-field layui-field-title">
                <legend>编辑礼品</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">商品封面</label>
                <div class="layui-input-inline">
                    <img id="img-cover" class="kg-cover" src="{{ gift.cover }}">
                    <input type="hidden" name="cover" value="{{ gift.cover }}">
                </div>
                <div class="layui-input-inline" style="padding-top:35px;">
                    <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品名称</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="name" value="{{ gift.name }}" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品详情</label>
                <div class="layui-input-block">
                    <div id="vditor"></div>
                    <textarea name="details" class="layui-hide" id="vditor-textarea">{{ gift.details }}</textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">所需积分</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="point" value="{{ gift.point }}" lay-verify="number">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">库存数量</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="stock" value="{{ gift.stock }}" lay-verify="number">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"></label>
                <div class="layui-input-block">
                    <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
                    <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                </div>
            </div>
        </form>
    {% endif %}

{% endblock %}

{% block link_css %}

    {{ css_link('https://cdn.jsdelivr.net/npm/vditor/dist/index.css', false) }}

{% endblock %}

{% block include_js %}

    {{ js_include('https://cdn.jsdelivr.net/npm/vditor/dist/index.min.js', false) }}
    {{ js_include('admin/js/cover.upload.js') }}
    {{ js_include('admin/js/vditor.js') }}

{% endblock %}