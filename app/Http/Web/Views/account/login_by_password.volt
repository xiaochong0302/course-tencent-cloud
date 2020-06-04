<form class="layui-form account-form" method="POST" action="{{ url({'for':'web.account.pwd_login'}) }}">
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
        </div>
    </div>
    <div id="captcha-block" class="layui-form-item">
        <div class="layui-input-block">
            <button id="captcha-btn" class="layui-btn layui-btn-primary layui-btn-fluid" type="button" data-app-id="{{ captcha.app_id }}">点击完成验证</button>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button id="submit-btn" class="layui-btn layui-btn-fluid layui-btn-disabled" disabled="disabled" lay-submit="true" lay-filter="go">立即登录</button>
            <input type="hidden" name="return_url" value="{{ return_url }}">
            <input id="ticket" type="hidden" name="ticket">
            <input id="rand" type="hidden" name="rand">
        </div>
    </div>
</form>