<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.live'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">回调密钥</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_auth_key" value="{{ live.notify_auth_key }}">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推流回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_stream_begin_url" value="{{ live.notify_stream_begin_url }}" layui-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">断流回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_stream_end_url" value="{{ live.notify_stream_end_url }}" layui-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">录制回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_record_url" value="{{ live.notify_record_url }}" layui-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">截图回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_snapshot_url" value="{{ live.notify_snapshot_url }}" layui-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">鉴黄回调</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_porn_url" value="{{ live.notify_porn_url }}" layui-verify="required">
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