{% if register_with_email %}
    <form class="layui-form account-form" method="POST" action="{{ action_url }}">
        <div class="layui-form-item">
            <label class="layui-icon layui-icon-email"></label>
            <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="邮箱" lay-verify="email">
        </div>
        <div class="layui-form-item">
            <label class="layui-icon layui-icon-password"></label>
            <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码（字母数字特殊字符6-16位）" lay-verify="required">
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline verify-input-inline">
                <label class="layui-icon layui-icon-vercode"></label>
                <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
            </div>
            <div class="layui-input-inline verify-btn-inline">
                <button id="cv-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button id="cv-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">注册帐号</button>
                <input type="hidden" name="return_url" value="{{ return_url }}">
                <input id="cv-captcha-enabled" type="hidden" value="{{ captcha.enabled }}">
                <input id="cv-captcha-appId" type="hidden" value="{{ captcha.app_id }}">
                <input id="cv-captcha-ticket" type="hidden" name="captcha[ticket]">
                <input id="cv-captcha-rand" type="hidden" name="captcha[rand]">
            </div>
        </div>
    </form>
{% else %}
    <div class="register-close-tips">
        <i class="layui-icon layui-icon-tips"></i> 邮箱注册已关闭
    </div>
{% endif %}