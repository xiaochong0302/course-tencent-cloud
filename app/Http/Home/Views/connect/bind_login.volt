<form class="layui-form account-form" method="POST" action="{{ url({'for':'home.oauth.bind_login'}) }}">
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
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button id="submit-btn" class="layui-btn layui-btn-fluid" lay-submit="true" lay-filter="go">登录并绑定已有帐号</button>
            <input type="hidden" name="provider" value="{{ provider }}">
            <input type="hidden" name="code" value="{{ request.get('code') }}">
            <input type="hidden" name="state" value="{{ request.get('state') }}">
        </div>
    </div>
</form>