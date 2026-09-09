<form class="layui-form kg-form" method="POST" action="{{ update_user_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">手机</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="phone" value="{{ account.phone }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="email" value="{{ account.email }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="password" placeholder="不修改密码请留空">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="type" value="account">
        </div>
    </div>
</form>
