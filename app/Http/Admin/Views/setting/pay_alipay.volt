<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.pay'}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">开启支付</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if alipay.enabled == "1" %}checked{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if alipay.enabled == "0" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_id" value="{{ alipay.app_id }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Public Key</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="public_key" lay-verify="required">{{ alipay.public_key }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Private Key</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="private_key" lay-verify="required">{{ alipay.private_key }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">Notify Url</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_url" value="{{ alipay.notify_url }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="pay.alipay">
        </div>
    </div>

</form>

<form class="layui-form kg-form">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>支付测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">支付项目</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="subject" value="支付测试0.01元" readonly="true">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">支付金额</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="total_amount" value="0.01" readonly="true">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="show-alipay-test">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;

        $('#show-alipay-test').on('click', function () {
            var url = '/admin/test/alipay';
            layer.open({
                type: 2,
                title: '支付宝 - 支付测试',
                resize: false,
                area: ['640px', '300px'],
                content: [url, 'no']
            });
        });

    });

</script>