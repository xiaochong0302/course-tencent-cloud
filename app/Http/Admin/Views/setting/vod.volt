{% extends 'templates/main.volt' %}

{% block content %}

    {% set storage_region_display = vod.storage_type == 'fixed' ? 'display:block' : 'display:none' %}
    {% set wmk_tpl_display = vod.wmk_enabled == 1 ? 'display:block' : 'display:none' %}
    {% set key_anti_display = vod.key_anti_enabled == 1 ? 'display:block': 'display:none' %}
    {% set video_quality = vod.video_quality|json_decode(true) %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vod'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>存储配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">存储方式</label>
            <div class="layui-input-block">
                <input type="radio" name="storage_type" value="nearby" title="就近存储" lay-filter="storage_type" {% if vod.storage_type == "nearby" %}checked="checked"{% endif %}>
                <input type="radio" name="storage_type" value="fixed" title="固定区域" lay-filter="storage_type" {% if vod.storage_type == "fixed" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="storage-region-block" style="{{ storage_region_display }}">
            <div class="layui-form-item">
                <label class="layui-form-label">所在区域</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="storage_region" value="{{ vod.storage_region }}">
                </div>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>转码配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">视频格式</label>
            <div class="layui-input-block">
                <input type="radio" name="video_format" value="hls" title="HLS" lay-filter="video_format" {% if vod.video_format == "hls" %}checked="checked"{% endif %}>
                <input type="radio" name="video_format" value="mp4" title="MP4" disabled="disabled" lay-filter="video_format" {% if vod.video_format == "mp4" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">音频格式</label>
            <div class="layui-input-block">
                <input type="radio" name="audio_format" value="mp3" title="MP3" lay-filter="audio_format" {% if vod.audio_format == "mp3" %}checked="checked"{% endif %}>
                <input type="radio" name="audio_format" value="m4a" title="M4A" disabled="disabled" lay-filter="audio_format" {% if vod.audio_format == "m4a" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">视频码率</label>
            <div class="layui-input-block">
                <input type="checkbox" name="video_quality[]" value="hd" title="高清" {% if 'hd' in video_quality %}checked="checked"{% endif %}>
                <input type="checkbox" name="video_quality[]" value="sd" title="标清" {% if 'sd' in video_quality %}checked="checked"{% endif %}>
                <input type="checkbox" name="video_quality[]" value="fd" title="极速" {% if 'fd' in video_quality %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开启水印</label>
            <div class="layui-input-block">
                <input type="radio" name="wmk_enabled" value="1" title="是" lay-filter="wmk_enabled" {% if vod.wmk_enabled == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="wmk_enabled" value="0" title="否" lay-filter="wmk_enabled" {% if vod.wmk_enabled == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="wmk-tpl-block" style="{{ wmk_tpl_display }}">
            <div class="layui-form-item">
                <label class="layui-form-label">水印模板ID</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="wmk_tpl_id" value="{{ vod.wmk_tpl_id }}">
                </div>
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>主分发配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">分发协议</label>
            <div class="layui-input-block">
                <input type="radio" name="protocol" value="https" title="HTTPS" {% if vod.protocol == "https" %}checked="checked"{% endif %}>
                <input type="radio" name="protocol" value="http" title="HTTP" {% if vod.protocol == "http" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">分发域名</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="domain" value="{{ vod.domain }}" lay-verify="required">
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>Key防盗链</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">开启防盗链</label>
            <div class="layui-input-block">
                <input type="radio" name="key_anti_enabled" value="1" title="是" lay-filter="key_anti_enabled" {% if vod.key_anti_enabled == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="key_anti_enabled" value="0" title="否" lay-filter="key_anti_enabled" {% if vod.key_anti_enabled == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="key-anti-block" style="{{ key_anti_display }}">
            <div class="layui-form-item">
                <label class="layui-form-label">防盗链Key</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="key_anti_key" value="{{ vod.key_anti_key }}">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">有效时间（秒）</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="key_anti_expiry" value="{{ vod.key_anti_expiry }}">
                </div>
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

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.vod'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>接口测试</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">请求方法</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="file" value="DescribeTranscodeTemplates" readonly="readonly">
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

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(storage_type)', function (data) {
                var block = $('#storage-region-block');
                if (data.value === 'fixed') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(wmk_enabled)', function (data) {
                var block = $('#wmk-tpl-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(key_anti_enabled)', function (data) {
                var block = $('#key-anti-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}