{% extends 'templates/main.volt' %}

{% block content %}

    {% set closed_tips_display = site.status == 'closed' ? 'display:block' : 'display:none' %}
    {% set analytics_script_display = site.analytics_enabled == 1 ? 'display:block' : 'display:none' %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.site'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>站点配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">站点状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="normal" title="正常" lay-filter="status" {% if site.status == 'normal' %}checked="checked"{% endif %}>
                <input type="radio" name="status" value="closed" title="关闭" lay-filter="status" {% if site.status == 'closed' %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="closed-tips-block" style="{{ closed_tips_display }}">
            <div class="layui-form-item">
                <label class="layui-form-label">关闭原因</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="closed_tips" value="{{ site.closed_tips }}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">首页版式</label>
            <div class="layui-input-block">
                <input type="radio" name="index_tpl_type" value="simple" title="简洁" {% if site.index_tpl_type == 'simple' %}checked="checked"{% endif %}>
                <input type="radio" name="index_tpl_type" value="full" title="丰富" {% if site.index_tpl_type == 'full' %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Logo</label>
            <div class="layui-inline" style="width:40%;">
                <input class="layui-input" type="text" name="logo" placeholder="请确保存储已正确配置" value="{{ site.logo }}">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" type="button" id="upload-logo">上传</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">Favicon</label>
            <div class="layui-inline" style="width:40%;">
                <input class="layui-input" type="text" name="favicon" placeholder="请确保存储已正确配置" value="{{ site.favicon }}">
            </div>
            <div class="layui-inline">
                <button class="layui-btn" type="button" id="upload-favicon">上传</button>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ site.title }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站URL</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="url" value="{{ site.url }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关键字</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="keywords" value="{{ site.keywords }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站描述</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="description" value="{{ site.description }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">版权信息</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="copyright" value="{{ site.copyright }}">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">ICP备案号</label>
                <div class="kg-input-inline">
                    <input class="layui-input" type="text" name="icp_sn" value="{{ site.icp_sn }}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">备案链接</label>
                <div class="kg-input-inline" style="width:500px;">
                    <input class="layui-input" type="text" name="icp_link" value="{{ site.icp_link }}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">ISP备案号</label>
                <div class="kg-input-inline">
                    <input class="layui-input" type="text" name="isp_sn" value="{{ site.isp_sn }}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">备案链接</label>
                <div class="kg-input-inline" style="width:500px;">
                    <input class="layui-input" type="text" name="isp_link" value="{{ site.isp_link }}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">公安备案号</label>
                <div class="kg-input-inline">
                    <input class="layui-input" type="text" name="police_sn" value="{{ site.police_sn }}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">备案链接</label>
                <div class="kg-input-inline" style="width:500px;">
                    <input class="layui-input" type="text" name="police_link" value="{{ site.police_link }}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">企业信用代码</label>
                <div class="kg-input-inline">
                    <input class="layui-input" type="text" name="company_sn" value="{{ site.company_sn }}">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">电子执照链接</label>
                <div class="kg-input-inline" style="width:500px;">
                    <input class="layui-input" type="text" name="company_sn_link" value="{{ site.company_sn_link }}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">开启统计</label>
            <div class="layui-input-block">
                <input type="radio" name="analytics_enabled" value="1" title="是" lay-filter="analytics_enabled" {% if site.analytics_enabled == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="analytics_enabled" value="0" title="否" lay-filter="analytics_enabled" {% if site.analytics_enabled == 0 %}checked="checked"{% endif %}>
            </div>
        </div>
        <div id="analytics-script-block" style="{{ analytics_script_display }}">
            <div class="layui-form-item">
                <label class="layui-form-label">统计代码</label>
                <div class="layui-input-block">
                    <textarea name="analytics_script" class="layui-textarea" placeholder="使用百度统计等第三方统计分析站点流量">{{ site.analytics_script }}</textarea>
                </div>
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

        layui.use(['jquery', 'form', 'layer', 'upload'], function () {

            var $ = layui.jquery;
            var form = layui.form;
            var upload = layui.upload;

            form.on('radio(status)', function (data) {
                var block = $('#closed-tips-block');
                if (data.value === 'closed') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            form.on('radio(analytics_enabled)', function (data) {
                var block = $('#analytics-script-block');
                if (data.value === '1') {
                    block.show();
                } else {
                    block.hide();
                }
            });

            upload.render({
                elem: '#upload-logo',
                url: '/admin/upload/icon/img',
                accept: 'images',
                acceptMime: 'image/*',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=logo]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

            upload.render({
                elem: '#upload-favicon',
                url: '/admin/upload/icon/img',
                accept: 'images',
                acceptMime: 'image/*',
                before: function () {
                    layer.load();
                },
                done: function (res, index, upload) {
                    $('input[name=favicon]').val(res.data.url);
                    layer.closeAll('loading');
                },
                error: function (index, upload) {
                    layer.msg('上传文件失败', {icon: 2});
                }
            });

        });

    </script>

{% endblock %}