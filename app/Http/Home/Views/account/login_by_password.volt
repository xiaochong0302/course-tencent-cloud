<form class="layui-form account-form" method="POST" action="{{ url({'for':'home.account.pwd_login'}) }}">
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-username"></label>
        <input id="cl-account" class="layui-input" type="text" name="account" autocomplete="off" placeholder="手机 / 邮箱" lay-verify="required">
    </div>
    <div class="layui-form-item">
        <label class="layui-icon layui-icon-password"></label>
        <input class="layui-input" type="password" name="password" autocomplete="off" placeholder="密码" lay-verify="required">
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button id="cl-submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">立即登录</button>
            <input type="hidden" name="return_url" value="{{ return_url }}">
        </div>
    </div>
</form>