{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.contact'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>联系方式</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">开启服务</label>
            <div class="layui-input-block">
                <input type="radio" name="enabled" value="1" title="是" {% if contact.enabled == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="enabled" value="0" title="否" {% if contact.enabled == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">微信二维码</label>
            <div class="layui-inline" style="width:40%;">
                <input class="layui-input" type="text" name="wechat" placeholder="请确保存储已正确配置" value="{{ contact.wechat }}">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" type="button" id="upload-wechat">上传</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">QQ二维码</label>
            <div class="layui-inline" style="width:40%;">
                <input class="layui-input" type="text" name="qq" placeholder="请确保存储已正确配置" value="{{ contact.qq }}">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" type="button" id="upload-qq">上传</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">头条二维码</label>
            <div class="layui-inline" style="width:40%;">
                <input class="layui-input" type="text" name="toutiao" placeholder="请确保存储已正确配置" value="{{ contact.toutiao }}">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" type="button" id="upload-toutiao">上传</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">抖音二维码</label>
            <div class="layui-inline" style="width:40%;">
                <input class="layui-input" type="text" name="douyin" placeholder="请确保存储已正确配置" value="{{ contact.douyin }}">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" type="button" id="upload-douyin">上传</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">微博帐号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="weibo" value="{{ contact.weibo }}" placeholder="https://weibo.com/u/{账号}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">知乎帐号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="zhihu" value="{{ contact.zhihu }}" placeholder="https://www.zhihu.com/people/{帐号}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系电话</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="phone" value="{{ contact.phone }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系邮箱</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="email" value="{{ contact.email }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">联系地址</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="address" value="{{ contact.address }}">
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

{% block inline_js %}

    <script>

        layui.use(['jquery', 'layer', 'upload'], function () {

            var $ = layui.jquery;
            var upload = layui.upload;

            upload.render({
                elem: '#upload-wechat',
                url: '/admin/upload/icon/img',
                exts: 'gif|jpg|png',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=wechat]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

            upload.render({
                elem: '#upload-qq',
                url: '/admin/upload/icon/img',
                exts: 'gif|jpg|png',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=qq]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

            upload.render({
                elem: '#upload-toutiao',
                url: '/admin/upload/icon/img',
                exts: 'gif|jpg|png',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=toutiao]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

            upload.render({
                elem: '#upload-douyin',
                url: '/admin/upload/icon/img',
                exts: 'gif|jpg|png',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=douyin]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

        });

    </script>

{% endblock %}