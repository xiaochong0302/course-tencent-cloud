{% extends 'templates/main.volt' %}

{% block content %}

    {% set closed_tips_display = site.status == 'normal' ? 'style="display:none;"' : '' %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.site'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>站点配置</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">站点状态</label>
            <div class="layui-input-block">
                <input type="radio" name="status" value="normal" title="正常" lay-filter="status" {% if site.status == "normal" %}checked{% endif %}>
                <input type="radio" name="status" value="closed" title="关闭" lay-filter="status" {% if site.status == "closed" %}checked{% endif %}>
            </div>
        </div>
        <div id="closed-tips-block" {{ closed_tips_display }}>
            <div class="layui-form-item">
                <label class="layui-form-label">关闭原因</label>
                <div class="layui-input-block">
                    <textarea name="closed_tips" class="layui-textarea">{{ site.closed_tips }}</textarea>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" value="{{ site.title }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">网站URL</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="base_url" value="{{ site.base_url }}">
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
                <div class="kg-input-inline">
                    <input class="layui-input" type="text" name="icp_link" value="{{ site.icp_link }}">
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
                <div class="kg-input-inline">
                    <input class="layui-input" type="text" name="police_link" value="{{ site.police_link }}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">站点统计</label>
            <div class="layui-input-block">
                <textarea name="analytics" class="layui-textarea" placeholder="使用百度统计，腾讯分析等第三方统计分析站点流量">{{ site.analytics }}</textarea>
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

        layui.use(['jquery', 'form', 'layer'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(status)', function (data) {
                var block = $('#closed-tips-block');
                if (data.value === 'closed') {
                    block.show();
                } else {
                    block.hide();
                }
            });

        });

    </script>

{% endblock %}