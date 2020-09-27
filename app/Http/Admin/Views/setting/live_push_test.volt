{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>手机推流</legend>
        </fieldset>
        <div class="layui-form-item">
            <div class="kg-center">
                <img class="kg-qrcode" src="{{ qrcode }}" alt="二维码图片">
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>OBS推流</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">推流地址</label>
            <div class="layui-input-inline" style="width:350px;">
                <input id="tc1" class="layui-input" type="text" name="obs_fms_url" value="{{ obs.fms_url }}" readonly="readonly">
            </div>
            <div class="layui-input-inline" style="width:100px;">
                <span class="kg-copy layui-btn" data-clipboard-target="#tc1">复制</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推流名称</label>
            <div class="layui-input-inline" style="width:350px;">
                <input id="tc2" class="layui-input" type="text" name="obs_stream_code" value="{{ obs.stream_code }}" readonly="readonly">
            </div>
            <div class="layui-input-inline" style="width:100px;">
                <span class="kg-copy layui-btn" data-clipboard-target="#tc2">复制</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">相关文档</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">
                    <a href="https://cloud.tencent.com/document/product/267/32732" target="_blank">最佳实践 - 直播推流</a>
                </div>
            </div>
        </div>
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('admin/js/copy.js') }}

{% endblock %}