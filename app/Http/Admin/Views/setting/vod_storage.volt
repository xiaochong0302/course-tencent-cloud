<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vod'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">子应用ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="sub_app_id" value="{{ vod.sub_app_id }}" placeholder="未开通子应用填写主应用ID" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分发协议</label>
        <div class="layui-input-block">
            <input type="radio" name="protocol" value="https" title="HTTPS" {% if vod.protocol == "https" %}checked="checked"{% endif %}>
            <input type="radio" name="protocol" value="http" title="HTTP" {% if vod.protocol == "http" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分发域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="domain" value="{{ vod.domain }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">存储方式</label>
        <div class="layui-input-block">
            <input type="radio" name="storage_type" value="nearby" title="就近存储" lay-filter="storage_type" {% if vod.storage_type == "nearby" %}checked="checked"{% endif %}>
            <input type="radio" name="storage_type" value="fixed" title="固定区域" lay-filter="storage_type" {% if vod.storage_type == "fixed" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="storage-region-block" style="{{ storage_region_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">所在区域</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="storage_region" value="{{ vod.storage_region }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">原始视频处理</label>
        <div class="layui-input-block">
            <input type="radio" name="keep_origin_media" value="1" title="转码后保留" {% if vod.keep_origin_media == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="keep_origin_media" value="0" title="转码后删除" {% if vod.keep_origin_media == "0" %}checked="checked"{% endif %}>
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

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.vod'}) }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>接口测试</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">请求方法</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="file" value="DescribeTranscodeTemplates" readonly="readonly">
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
