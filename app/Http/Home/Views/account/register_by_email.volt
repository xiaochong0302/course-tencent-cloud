{% if register_with_email %}
    <form class="layui-form account-form" method="POST" action="{{ action_url }}">
        <div class="layui-form-item">
            <label class="layui-icon layui-icon-email"></label>
            <input id="cv-email" class="layui-input" type="text" name="email" autocomplete="off" placeholder="邮箱" lay-verify="required|email">
        </div>
        <div class="layui-form-item">
            <label class="layui-icon layui-icon-password"></label>
            <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline verify-input-inline">
                <label class="layui-icon layui-icon-vercode"></label>
                <input class="layui-input" type="text" name="verify_code" placeholder="验证码" lay-verify="required">
            </div>
            <div class="layui-input-inline verify-btn-inline">
                <button id="cv-email-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <div class="agree">
                    <div class="left"><input id="cv-email-agree" type="checkbox" name="agree" checked="checked" lay-skin="primary"></div>
                    <div class="right">我已阅读并同意<a href="{{ terms_url }}" target="_blank">《用户协议》</a>和<a href="{{ privacy_url }}" target="_blank">《隐私政策》</a></div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button id="cv-email-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">注册帐号</button>
                <input id="cv-email-captcha-ticket" type="hidden" name="captcha[ticket]">
                <input id="cv-email-captcha-rand" type="hidden" name="captcha[rand]">
                <input type="hidden" name="return_url" value="{{ return_url }}">
            </div>
        </div>
    </form>
{% else %}
    <div class="register-close-tips">
        <i class="layui-icon layui-icon-tips"></i> 邮箱注册已关闭
    </div>
{% endif %}