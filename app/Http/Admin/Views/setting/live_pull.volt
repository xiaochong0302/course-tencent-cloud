<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.live'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>基础配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">拉流协议</label>
        <div class="layui-input-block">
            <input type="radio" name="pull_protocol" value="http" title="HTTP" {% if live.pull_protocol == "http" %}checked{% endif %}>
            <input type="radio" name="pull_protocol" value="https" title="HTTPS" {% if live.pull_protocol == "https" %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">拉流域名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="pull_domain" value="{{ live.pull_domain }}" layui-verify="required">
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>鉴权配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">开启鉴权</label>
        <div class="layui-input-block">
            <input type="radio" name="pull_auth_enabled" value="1" title="是" lay-filter="pull_auth_enabled" {% if live.pull_auth_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="pull_auth_enabled" value="0" title="否" lay-filter="pull_auth_enabled" {% if live.pull_auth_enabled == 0 %}checked{% endif %}>
        </div>
    </div>

    <div id="pull-auth-block" {% if live.pull_auth_enabled == '0' %}style="display:none;"{% endif %}>
        <div class="layui-form-item">
            <label class="layui-form-label">鉴权密钥</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="pull_auth_key" value="{{ live.pull_auth_key }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效时间（秒）</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="pull_auth_delta" value="{{ live.pull_auth_delta }}">
            </div>
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>转码配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">开启转码</label>
        <div class="layui-input-block">
            <input type="radio" name="pull_trans_enabled" value="1" title="是" lay-filter="pull_trans_enabled" {% if live.pull_trans_enabled == 1 %}checked{% endif %}>
            <input type="radio" name="pull_trans_enabled" value="0" title="否" lay-filter="pull_trans_enabled" {% if live.pull_trans_enabled == 0 %}checked{% endif %}>
        </div>
    </div>

    <table class="kg-table layui-table layui-form" id="ptt-block" {% if live.pull_trans_enabled == '0' %}style="display:none;"{% endif %}>
        <colgroup>
            <col>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>模板名称</th>
            <th>模板描述</th>
            <th>视频码率（kbps）</th>
            <th>视频高度（px）</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><input class="layui-input" type="text" name="ptt[id][fd]" value="{{ ptt.fd.id }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[summary][fd]" value="{{ ptt.fd.summary }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[bit_rate][fd]" value="{{ ptt.fd.bit_rate }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[height][fd]" value="{{ ptt.fd.height }}" readonly="readonly"></td>
        </tr>
        <tr>
            <td><input class="layui-input" type="text" name="ptt[id][sd]" value="{{ ptt.sd.id }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[summary][sd]" value="{{ ptt.sd.summary }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[bit_rate][sd]" value="{{ ptt.sd.bit_rate }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[height][sd]" value="{{ ptt.sd.height }}" readonly="readonly"></td>
        </tr>
        <tr>
            <td><input class="layui-input" type="text" name="ptt[id][hd]" value="{{ ptt.hd.id }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[summary][hd]" value="{{ ptt.hd.summary }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[bit_rate][hd]" value="{{ ptt.hd.bit_rate }}" readonly="readonly"></td>
            <td><input class="layui-input" type="text" name="ptt[height][hd]" value="{{ ptt.hd.height }}" readonly="readonly"></td>
        </tr>
        </tbody>
    </table>

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
        <legend>拉流测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">Stream Name</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_name" value="test" readonly="readonly">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="show-pull-test">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

<script>

    layui.use(['jquery', 'form', 'layer'], function () {

        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        form.on('radio(pull_auth_enabled)', function (data) {
            var block = $('#pull-auth-block');
            if (data.value === '1') {
                block.show();
            } else {
                block.hide();
            }
        });

        form.on('radio(pull_trans_enabled)', function (data) {
            var block = $('#ptt-block');
            if (data.value === '1') {
                block.show();
            } else {
                block.hide();
            }
        });

        $('#show-pull-test').on('click', function () {
            var url = '/admin/test/live/pull';
            layer.open({
                type: 2,
                title: '拉流测试',
                resize: false,
                area: ['720px', '445px'],
                content: [url, 'no']
            });
        });

    });

</script>