<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.im'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启服务</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if main.enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if main.enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">应用名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ main.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">消息最大长度</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="msg_max_length" value="{{ main.msg_max_length }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开启图片上传</label>
        <div class="layui-input-block">
            <input type="radio" name="upload_img_enabled" value="1" title="是" {% if main.upload_img_enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="upload_img_enabled" value="0" title="否" {% if main.upload_img_enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开启文件上传</label>
        <div class="layui-input-block">
            <input type="radio" name="upload_file_enabled" value="1" title="是" {% if main.upload_file_enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="upload_file_enabled" value="0" title="否" {% if main.upload_file_enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开启音频栏</label>
        <div class="layui-input-block">
            <input type="radio" name="tool_audio_enabled" value="1" title="是" {% if main.tool_audio_enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="tool_audio_enabled" value="0" title="否" {% if main.tool_audio_enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开启视频栏</label>
        <div class="layui-input-block">
            <input type="radio" name="tool_video_enabled" value="1" title="是" {% if main.tool_video_enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="tool_video_enabled" value="0" title="否" {% if main.tool_video_enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="im.main">
        </div>
    </div>
</form>