<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.im'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启服务</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if cs.enabled == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if cs.enabled == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">客服1用户编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="user1_id" value="{{ cs.user1_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">客服2用户编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="user2_id" value="{{ cs.user2_id }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">客服3用户编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="user3_id" value="{{ cs.user3_id }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="im.cs">
        </div>
    </div>
</form>