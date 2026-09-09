<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.security'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启手机号注册</label>
        <div class="layui-input-block">
            <input type="radio" name="register_with_phone" value="1" title="是" {% if register.register_with_phone == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="register_with_phone" value="0" title="否" {% if register.register_with_phone == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">开启邮箱注册</label>
        <div class="layui-input-block">
            <input type="radio" name="register_with_email" value="1" title="是" {% if register.register_with_email == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="register_with_email" value="0" title="否" {% if register.register_with_email == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="security.register">
        </div>
    </div>
</form>
