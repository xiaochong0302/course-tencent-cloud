<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.config.website'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>站点配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">关闭网站</label>
        <div class="layui-input-block">
            <input type="radio" name="closed" value="1" title="是" lay-filter="closed" {% if website.closed == "1" %}checked{% endif %}>
            <input type="radio" name="closed" value="0" title="否" lay-filter="closed" {% if website.closed == "0" %}checked{% endif %}>
        </div>
    </div>

    <div id="closed-tips-block" class="layui-form-item" {% if website.closed == 0 %}style="display:none;"{% endif %}>
        <label class="layui-form-label">关闭提示</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="closed_tips" value="{{ website.closed_tips }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">网站名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ website.title }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">关键字</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="keywords" value="{{ website.keywords }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">网站描述</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="description" value="{{ website.description }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">版权信息</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="copyright" value="{{ website.copyright }}">
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">ICP备案号</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="icp_sn" value="{{ website.icp_sn }}">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备案链接</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="icp_link" value="{{ website.icp_link }}">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">公安备案号</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="police_sn" value="{{ website.police_sn }}">
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">备案链接</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="police_link" value="{{ website.police_link }}">
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">站点统计</label>
        <div class="layui-input-block">
            <textarea name="analytics" class="layui-textarea" placeholder="使用百度统计，CNZZ，腾讯分析等第三方统计分析站点流量">{{ website.analytics }}</textarea>
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

<script>

    layui.use(['jquery', 'form'], function () {

        var $ = layui.jquery;
        var form = layui.form;

        form.on('radio(closed)', function (data) {
            var block = $('#closed-tips-block');
            if (data.value == 1) {
                block.show();
            } else {
                block.hide();
            }
        });

    });

</script>