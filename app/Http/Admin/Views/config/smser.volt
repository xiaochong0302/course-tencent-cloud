<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.config.smser'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>基础配置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">App ID</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_id" value="{{ smser.app_id }}" layui-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">App Key</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="app_key" value="{{ smser.app_key }}" layui-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">内容签名</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="signature" value="{{ smser.signature }}" placeholder="注意：使用的是签名内容，而非签名ID" layui-verify="required">
        </div>
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>模板配置</legend>
    </fieldset>

    <table class="kg-table layui-table layui-form">
        <colgroup>
            <col width="12%">
            <col width="15%">
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>模板编号</th>
            <th>模板内容</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>身份验证</td>
            <td><input class="layui-input" type="text" name="template[id][verify]" value="{{ template.verify.id }}" lay-verify="required"></td>
            <td><input id="tc-verify" class="layui-input" type="text" name="template[content][verify]" value="{{ template.verify.content }}" readonly="readonly" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc-verify">复制</span></td>
        </tr>
        <tr>
            <td>订单通知</td>
            <td><input class="layui-input" type="text" name="template[id][order]" value="{{ template.order.id }}" lay-verify="required"></td>
            <td><input id="tc-order" class="layui-input" type="text" name="template[content][order]" value="{{ template.order.content }}" readonly="readonly" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc-order">复制</span></td>
        </tr>
        <tr>
            <td>退款通知</td>
            <td><input class="layui-input" type="text" name="template[id][refund]" value="{{ template.refund.id }}" lay-verify="required"></td>
            <td><input id="tc-refund" class="layui-input" type="text" name="template[content][refund]" value="{{ template.refund.content }}" readonly="readonly" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc-refund">复制</span></td>
        </tr>
        <tr>
            <td>直播通知</td>
            <td><input class="layui-input" type="text" name="template[id][live]" value="{{ template.live.id }}" lay-verify="required"></td>
            <td><input id="tc-live" class="layui-input" type="text" name="template[content][live]" value="{{ template.live.content }}" readonly="readonly" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc-live">复制</span></td>
        </tr>
        </tbody>
    </table>

    <div class="layui-form-item" style="margin-top:20px;">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.test.smser'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>短信测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">手机号码</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="phone" lay-verify="phone" placeholder="请先提交相关配置，再进行短信测试哦！">
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

{{ partial('partials/clipboard_tips') }}