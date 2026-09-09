<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vod'}) }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>Key防盗链</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">开启防盗</label>
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
        <div class="layui-form-item">
            <label class="layui-form-label">IP限制数</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="key_anti_ip_limit" value="{{ vod.key_anti_ip_limit }}">
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
