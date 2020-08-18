<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.im'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">应用名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ im.title }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>