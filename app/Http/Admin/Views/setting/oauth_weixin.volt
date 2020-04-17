<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.oauth'}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">开启登录</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if weixin.enabled == "1" %}checked{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if weixin.enabled == "0" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_id" value="{{ weixin.app_id }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">App Secret</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_secret" value="{{ weixin.app_secret }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Redirect Uri</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="redirect_uri" value="{{ weixin.redirect_uri }}" lay-verify="required">
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

<form class="layui-form kg-form">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>登录测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-inline">
            <span id="qq-login-btn" class="layui-btn layui-btn-primary layui-btn-fluid">微信登录</span>
        </div>
    </div>

</form>

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;

    });

</script>