<form class="layui-form kg-form">

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-inline" style="width:150px;margin-left:110px;">
            <img src="/qrcode/img?text={{ push_url|url_encode }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">OBS推流地址</label>
        <div class="layui-input-inline" style="width:350px;">
            <input id="tc1" class="layui-input" type="text" name="obs.fms_url" value="{{ obs.fms_url }}" readonly="true">
        </div>
        <div class="layui-input-inline" style="width:100px;">
            <span class="kg-copy layui-btn" data-clipboard-target="#tc1">复制</span>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">OBS推流名称</label>
        <div class="layui-input-inline" style="width:350px;">
            <input id="tc2" class="layui-input" type="text" name="obs_stream_code" value="{{ obs.stream_code }}" readonly="true">
        </div>
        <div class="layui-input-inline" style="width:100px;">
            <span class="kg-copy layui-btn" data-clipboard-target="#tc2">复制</span>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">相关文档</label>
        <div class="layui-input-block">
            <div class="layui-form-mid layui-word-aux">
                <a href="https://cloud.tencent.com/document/product/267/32732" target="_blank">最佳实践 - 直播推流</a>
            </div>
        </div>
    </div>

</form>

{{ partial('partials/clipboard_tips') }}