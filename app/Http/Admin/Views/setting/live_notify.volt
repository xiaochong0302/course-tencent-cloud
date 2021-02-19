<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.live'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">回调密钥</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="auth_key" value="{{ notify.auth_key }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推流回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_begin_url" value="{{ notify.stream_begin_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">断流回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_end_url" value="{{ notify.stream_end_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">录制回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="record_url" value="{{ notify.record_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">截图回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="snapshot_url" value="{{ notify.snapshot_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">鉴黄回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="porn_url" value="{{ notify.porn_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="live.notify">
        </div>
    </div>
</form>