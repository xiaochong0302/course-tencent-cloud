<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.security'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启私密访问</label>
        <div class="layui-input-inline">
            <input type="radio" name="private" value="1" title="是" {% if access.private == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="private" value="0" title="否" {% if access.private == 0 %}checked="checked"{% endif %}>
        </div>
        <div class="layui-form-mid">
            <span class="layui-font-gray">开启私密访问后，只有登录用户才能访问。</span>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">允许登录失败次数</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="failed_login_limit" value="{{ access.failed_login_limit }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">登录失败锁定秒数</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="failed_login_lock" value="{{ access.failed_login_lock }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">限制设备同时在线</label>
        <div class="layui-input-block">
            <input type="radio" name="mutex_login" value="1" title="是" {% if access.mutex_login == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="mutex_login" value="0" title="否" {% if access.mutex_login == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item" style="margin-bottom:20px;">
        <label class="layui-form-label">同时在线设备数</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="mutex_client_limit" value="{{ access.mutex_client_limit }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="security.access">
        </div>
    </div>
</form>
