{% set notice_template = oa.notice_template|json_decode %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.wechat'}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开启</label>
        <div class="layui-input-block">
            <input type="radio" name="enabled" value="1" title="是" {% if oa.enabled == "1" %}checked="checked"{% endif %}>
            <input type="radio" name="enabled" value="0" title="否" {% if oa.enabled == "0" %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_id" value="{{ oa.app_id }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App Secret</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_secret" value="{{ oa.app_secret }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">App Token</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_token" value="{{ oa.app_token }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">Aes Key</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="aes_key" value="{{ oa.aes_key }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">Notify Url</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="notify_url" value="{{ oa.notify_url }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="wechat.oa">
        </div>
    </div>
</form>
<fieldset class="layui-elem-field layui-field-title">
    <legend>模板配置</legend>
</fieldset>
<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.wechat'}) }}">
    <table class="layui-table kg-table layui-form">
        <colgroup>
            <col width="12%">
            <col width="40%">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>模板编号</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>登录成功通知</td>
            <td><input class="layui-input" type="text" name="notice_template[account_login]" value="{{ notice_template.account_login }}" lay-verify="required"></td>
            <td></td>
        </tr>
        <tr>
            <td>购买成功提醒</td>
            <td><input class="layui-input" type="text" name="notice_template[order_finish]" value="{{ notice_template.order_finish }}" lay-verify="required"></td>
            <td></td>
        </tr>
        <tr>
            <td>退款成功通知</td>
            <td><input class="layui-input" type="text" name="notice_template[refund_finish]" value="{{ notice_template.refund_finish }}" lay-verify="required"></td>
            <td></td>
        </tr>
        <tr>
            <td>课程直播提醒</td>
            <td><input class="layui-input" type="text" name="notice_template[live_begin]" value="{{ notice_template.live_begin }}" lay-verify="required"></td>
            <td></td>
        </tr>
        <tr>
            <td>咨询结果通知</td>
            <td><input class="layui-input" type="text" name="notice_template[consult_reply]" value="{{ notice_template.consult_reply }}" lay-verify="required"></td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <br>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="section" value="wechat.oa">
        </div>
    </div>
</form>