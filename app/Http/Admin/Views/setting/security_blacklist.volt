<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.security'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启黑名单</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if blacklist.enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if blacklist.enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">受限IP列表</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="content">{{ blacklist.content }}</textarea>
            <div class="layui-form-mid gray">多个IP使用半角逗号分隔</div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="security.blacklist">
        </div>
    </div>
</form>
