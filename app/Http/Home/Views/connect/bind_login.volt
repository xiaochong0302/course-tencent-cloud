<form class="layui-form account-form" method="POST" action="{{ url({'for':'home.oauth.bind_login'}) }}">
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-username"></label>
        <input class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
    </div>
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-password"></label>
        <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <div class="agree">
                <div class="left"><input id="login-agree" type="checkbox" name="agree" checked="checked" lay-skin="primary"></div>
                <div class="right">我已阅读并同意<a href="{{ terms_url }}" target="_blank">《用户协议》</a>和<a href="{{ privacy_url }}" target="_blank">《隐私政策》</a></div>
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button id="submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">登录并绑定已有帐号</button>
            <input type="hidden" name="provider" value="{{ provider }}">
            <input type="hidden" name="code" value="{{ request.get('code') }}">
            <input type="hidden" name="state" value="{{ request.get('state') }}">
            <input type="hidden" name="open_user" value='{{ open_user|json_encode }}'>
        </div>
    </div>
</form>