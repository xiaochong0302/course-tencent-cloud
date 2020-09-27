{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.storage'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>存储桶配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">空间名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="bucket" value="{{ cos.bucket }}" layui-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">所在区域</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="region" value="{{ cos.region }}" layui-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">访问协议</label>
            <div class="layui-input-block">
                <input type="radio" name="protocol" value="http" title="HTTP" {% if cos.protocol == "http" %}checked="checked"{% endif %}>
                <input type="radio" name="protocol" value="https" title="HTTPS" {% if cos.protocol == "https" %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">访问域名</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="domain" value="{{ cos.domain }}" lay-verify="required">
            </div>
        </div>
        <fieldset class="layui-elem-field layui-field-title">
            <legend>图片处理样式</legend>
        </fieldset>
        <div class="layui-form-item">
            <table class="layui-table" lay-size="lg">
                <colgroup>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>样式名称</th>
                    <th>样式描述</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>avatar_160</td>
                    <td>imageMogr2/thumbnail/160x/interlace/0</td>
                </tr>
                <tr>
                    <td>cover_270</td>
                    <td>mageMogr2/thumbnail/270x/interlace/0</td>
                </tr>
                <tr>
                    <td>slide_1100</td>
                    <td>imageMogr2/thumbnail/1100x/interlace/0</td>
                </tr>
                </tbody>
            </table>
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
                <input class="layui-input" type="text" name="file" value="hello_world.txt" readonly="readonly">
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

{% endblock %}