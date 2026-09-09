<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.security'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启限流</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if throttle.enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if throttle.enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">计数周期（秒）</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="interval" value="{{ throttle.interval }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">请求频率（次）</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="rate_limit" value="{{ throttle.rate_limit }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="security.throttle">
        </div>
    </div>
</form>
