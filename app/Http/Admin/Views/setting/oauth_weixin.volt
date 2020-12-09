<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.oauth'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启登录</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if weixin_auth.enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if weixin_auth.enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="client_id" value="{{ weixin_auth.client_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App Secret</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="client_secret" value="{{ weixin_auth.client_secret }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">Callback Url</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="redirect_uri" value="{{ weixin_auth.redirect_uri }}" lay-verify="required">
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