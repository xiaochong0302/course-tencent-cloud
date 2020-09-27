{% extends 'templates/layer.volt' %}

{% block content %}

    <form class="layui-form">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>手机推流</legend>
        </fieldset>
        <div class="layui-form-item">
            <div class="center">
                <div class="qrcode-sm"><img src="{{ qrcode }}" alt="二维码图片"></div>
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
    </form>

{% endblock %}

{% block include_js %}

    {{ js_include('lib/clipboard.min.js') }}
    {{ js_include('home/js/copy.js') }}

{% endblock %}