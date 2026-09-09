<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vod'}) }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>标准转码</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">开启转码</label>
        <div class="layui-input-block">
            <input type="radio" name="std_trans_enabled" value="1" title="是" lay-filter="std_trans_enabled" {% if vod.std_trans_enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="std_trans_enabled" value="0" title="否" lay-filter="std_trans_enabled" {% if vod.std_trans_enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="standard-transcode-block" style="{{ std_trans_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">转码类型</label>
            <div class="layui-input-block">
                <input type="radio" name="transcode_type" value="normal" title="普通转码" {% if vod.transcode_type == "normal" %}checked="checked"{% endif %}>
                <input type="radio" name="transcode_type" value="tes" title="极速高清" {% if vod.transcode_type == "tes" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">视频质量</label>
            <div class="layui-input-block">
                <input type="checkbox" name="video_quality[]" value="hd" title="高清" {% if 'hd' in video_quality %}checked="checked"{% endif %}>
                <input type="checkbox" name="video_quality[]" value="sd" title="标清" {% if 'sd' in video_quality %}checked="checked"{% endif %}>
                <input type="checkbox" name="video_quality[]" value="fd" title="流畅" {% if 'fd' in video_quality %}checked="checked"{% endif %}>
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
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>
