<form class="layui-form account-form" method="POST" action="{{ url({'for':'home.account.verify_login'}) }}">
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-username"></label>
        <input id="cv-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
    </div>
    <div class="layui-form-item">
        <div class="layui-input-inline verify-input-inline">
            <label class="layui-icon layui-icon-vercode"></label>
            <input class="layui-input" type="text" name="verify_code" autocomplete="off" placeholder="验证码" lay-verify="required">
        </div>
        <div class="layui-input-inline verify-btn-inline">
            <button id="cv-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button id="cv-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">立即登录</button>
            <input id="cv-captcha-ticket" type="hidden" name="captcha[ticket]">
            <input id="cv-captcha-rand" type="hidden" name="captcha[rand]">
            <input type="hidden" name="return_url" value="{{ return_url }}">
        </div>
    </div>
</form>