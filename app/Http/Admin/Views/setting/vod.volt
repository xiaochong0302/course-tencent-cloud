<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vod'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>存储配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">存储方式</label>
        <div class="layui-input-block">
            <input type="radio" name="storage_type" value="nearby" title="就近存储" lay-filter="storage-type" {% if vod.storage_type == "nearby" %}checked{% endif %}>
            <input type="radio" name="storage_type" value="fixed" title="固定区域" lay-filter="storage-type" {% if vod.storage_type == "fixed" %}checked{% endif %}>
        </div>
    </div>

    <div id="storage-region-block" class="layui-form-item" {% if vod.storage_type == 'nearby' %}style="display:none;"{% endif %}>
        <label class="layui-form-label">所在区域</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="storage_region" value="{{ vod.storage_region }}">
        </div>
    </div>


    <fieldset class="layui-elem-field layui-field-title">
        <legend>转码配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">视频格式</label>
        <div class="layui-input-block">
            <input type="radio" name="video_format" value="hls" title="HLS" lay-filter="video-format" {% if vod.video_format == "hls" %}checked{% endif %}>
            <input type="radio" name="video_format" value="mp4" title="MP4" lay-filter="video-format" {% if vod.video_format == "mp4" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">视频模板ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="video_template" readonly="true" layui-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">音频格式</label>
        <div class="layui-input-block">
            <input type="radio" name="audio_format" value="m4a" title="M4A" lay-filter="audio-format" {% if vod.audio_format == "m4a" %}checked{% endif %}>
            <input type="radio" name="audio_format" value="mp3" title="MP3" lay-filter="audio-format" {% if vod.audio_format == "mp3" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">音频模板ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="audio_template" readonly="true" layui-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">开启水印</label>
        <div class="layui-input-block">
            <input type="radio" name="watermark_enabled" value="1" title="是" lay-filter="watermark-enabled" {% if vod.watermark_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="watermark_enabled" value="0" title="否" lay-filter="watermark-enabled" {% if vod.watermark_enabled == 0 %}checked{% endif %}>
        </div>
    </div>

    <div id="watermark-template-block" class="layui-form-item" {% if vod.watermark_enabled == 0 %}style="display:none;"{% endif %}>
        <label class="layui-form-label">水印模板ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="watermark_template" value="{{ vod.watermark_template }}">
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>主分发配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">分发协议</label>
        <div class="layui-input-block">
            <input type="radio" name="dist_protocol" value="https" title="HTTPS" {% if vod.dist_protocol == "https" %}checked{% endif %}>
            <input type="radio" name="dist_protocol" value="http" title="HTTP" {% if vod.dist_protocol == "http" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">分发域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="dist_domain" value="{{ vod.dist_domain }}" lay-verify="required">
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>Key防盗链</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">开启防盗链</label>
        <div class="layui-input-block">
            <input type="radio" name="key_anti_enabled" value="1" title="是" lay-filter="key-anti-enabled" {% if vod.key_anti_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="key_anti_enabled" value="0" title="否" lay-filter="key-anti-enabled" {% if vod.key_anti_enabled == 0 %}checked{% endif %}>
        </div>
    </div>

    <div id="key-anti-block" class="layui-form-item" {% if vod.key_anti_enabled == 0 %}style="display:none;"{% endif %}>
        <div class="layui-form-item">
            <label class="layui-form-label">防盗链Key</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="key_anti_key" value="{{ vod.key_anti_key }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效期（秒）</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="key_anti_expiry" value="{{ vod.key_anti_expiry }}" lay-verify="required">
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
            <input class="layui-input" type="text" name="file" value="DescribeAudioTrackTemplates" readonly="true">
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

<script>

    layui.use(['jquery', 'form'], function () {

        var $ = layui.jquery;
        var form = layui.form;

        var changeVideoTemplate = function (format) {
            var template = $('input[name=video_template]');
            if (format == 'mp4') {
                template.val('10,20,30');
            } else {
                template.val('210,220,230');
            }
        };

        var changeAudioTemplate = function (format) {
            var template = $('input[name=audio_template]');
            if (format == 'mp3') {
                template.val('1010');
            } else {
                template.val('1110');
            }
        };

        form.on('radio(storage-type)', function (data) {
            var block = $('#storage-region-block');
            if (data.value == 'fixed') {
                block.show();
            } else {
                block.hide();
            }
        });

        form.on('radio(watermark-enabled)', function (data) {
            var block = $('#watermark-template-block');
            if (data.value == 1) {
                block.show();
            } else {
                block.hide();
            }
        });

        form.on('radio(key-anti-enabled)', function (data) {
            var block = $('#key-anti-block');
            if (data.value == 1) {
                block.show();
            } else {
                block.hide();
            }
        });

        form.on('radio(video-format)', function (data) {
            changeVideoTemplate(data.value);
        });

        form.on('radio(audio-format)', function (data) {
            changeAudioTemplate(data.value);
        });

        var videoFormat = $('input[name=video_format]:checked').val();
        var audioFormat = $('input[name=audio_format]:checked').val();

        changeVideoTemplate(videoFormat);
        changeAudioTemplate(audioFormat);

    });

</script>