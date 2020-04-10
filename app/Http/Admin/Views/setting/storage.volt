<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.storage'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>存储桶配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">空间名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="bucket_name" value="{{ storage.bucket_name }}" layui-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">所在区域</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="bucket_region" value="{{ storage.bucket_region }}" layui-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">访问协议</label>
        <div class="layui-input-block">
            <input type="radio" name="bucket_protocol" value="http" title="HTTP" {% if storage.bucket_protocol == "http" %}checked{% endif %}>
            <input type="radio" name="bucket_protocol" value="https" title="HTTPS" {% if storage.bucket_protocol == "https" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">访问域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="bucket_domain" value="{{ storage.bucket_domain }}" lay-verify="required">
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>数据万象</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">访问协议</label>
        <div class="layui-input-block">
            <input type="radio" name="ci_protocol" value="http" title="HTTP" {% if storage.ci_protocol == "http" %}checked{% endif %}>
            <input type="radio" name="ci_protocol" value="https" title="HTTPS" {% if storage.ci_protocol == "https" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">访问域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="ci_domain" value="{{ storage.ci_domain }}" lay-verify="required">
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

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.storage'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>上传测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">测试文件</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="file" value="hello_world.txt" readonly="true">
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