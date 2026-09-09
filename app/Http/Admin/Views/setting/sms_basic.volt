<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.sms'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_id" value="{{ sms.app_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App Key</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_key" value="{{ sms.app_key }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">内容签名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="signature" placeholder="注意：使用的是签名内容，而非签名ID" value="{{ sms.signature }}" lay-verify="required">
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

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.sms'}) }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>短信测试</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号码</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="phone" placeholder="请先提交相关配置，再进行短信测试哦！" lay-verify="phone">
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
