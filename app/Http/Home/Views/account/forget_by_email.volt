<form class="layui-form account-form" method="POST" action="{{ action_url }}">
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-email"></label>
        <input id="cv-email" class="layui-input" type="text" name="email" autocomplete="off" placeholder="邮箱" lay-verify="email">
    </div>
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-password"></label>
        <input class="layui-input" type="password" name="new_password" autocomplete="off" placeholder="新密码" lay-verify="required">
    </div>
    <div class="layui-form-item">
        <div class="layui-input-inline verify-input-inline">
            <label class="layui-icon layui-icon-vercode"></label>
            <input class="layui-input" type="text" name="verify_code" autocomplete="off" placeholder="验证码" lay-verify="required">
        </div>
        <div class="layui-input-inline verify-btn-inline">
            <button id="cv-email-emit-btn" class="layui-btn layui-btn-disabled" type="button" disabled="disabled">获取验证码</button>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button id="cv-email-submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">重置密码</button>
            <input id="cv-email-captcha-ticket" type="hidden" name="captcha[ticket]">
            <input id="cv-email-captcha-rand" type="hidden" name="captcha[rand]">
        </div>
    </div>
</form>