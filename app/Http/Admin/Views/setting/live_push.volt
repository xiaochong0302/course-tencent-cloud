{% set push_auth_display = push.auth_enabled == 1 ? 'display:block' : 'display:none' %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.live'}) }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>基础配置</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">推流域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="domain" value="{{ push.domain }}" lay-verify="required">
        </div>
    </div>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>鉴权配置</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">开启鉴权</label>
        <div class="layui-input-block">
            <input type="radio" name="auth_enabled" value="1" title="是" lay-filter="push_auth_enabled" {% if push.auth_enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="auth_enabled" value="0" title="否" lay-filter="push_auth_enabled" {% if push.auth_enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="push-auth-block" style="{{ push_auth_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">鉴权密钥</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="auth_key" value="{{ push.auth_key }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效时间（秒）</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="auth_delta" value="{{ push.auth_delta }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="live.push">
        </div>
    </div>
</form>

<form class="layui-form kg-form">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>推流测试</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">Stream Name</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_name" value="test" readonly="readonly">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="show-push-test">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>