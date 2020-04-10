<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.live'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>基础配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">推流域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="push_domain" value="{{ live.push_domain }}" layui-verify="required">
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>鉴权配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">开启鉴权</label>
        <div class="layui-input-block">
            <input type="radio" name="push_auth_enabled" value="1" title="是" {% if live.push_auth_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="push_auth_enabled" value="0" title="否" {% if live.push_auth_enabled == 0 %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">鉴权密钥</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="push_auth_key" value="{{ live.push_auth_key }}">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">有效时间（秒）</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="push_auth_delta" value="{{ live.push_auth_delta }}">
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

<form class="layui-form kg-form">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>推流测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">Stream Name</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_name" value="test" readonly="true">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="show-push-test">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

<script>

    layui.use(['jquery', 'layer'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;

        $('#show-push-test').on('click', function () {
            var url = '/admin/test/live/push';
            layer.open({
                type: 2,
                title: '推流测试',
                resize: false,
                area: ['680px', '380px'],
                content: [url, 'no']
            });
        });

    });

</script>