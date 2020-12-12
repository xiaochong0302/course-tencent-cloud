<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.wechat'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if oa.enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if oa.enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_id" value="{{ oa.app_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App Secret</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_secret" value="{{ oa.app_secret }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App Token</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_token" value="{{ oa.app_token }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">Aes Key</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="aes_key" value="{{ oa.aes_key }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">通知地址</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_url" value="{{ oa.notify_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="oauth.weixin">
        </div>
    </div>
</form>