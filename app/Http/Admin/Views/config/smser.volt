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
            <col width="10%">
            <col width="12%">
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>类型</th>
            <th>模板编号</th>
            <th>模板内容</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>注册帐号</td>
            <td><span class="layui-badge">验证码</span></td>
            <td><input class="layui-input" type="text" name="template[id][register]" value="{{ template.register.id }}" lay-verify="required"></td>
            <td><input id="tc1" class="layui-input" type="text" name="template[content][register]" value="{{ template.register.content }}" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc1">复制</span></td>
        </tr>
        <tr>
            <td>重置密码</td>
            <td><span class="layui-badge">验证码</span></td>
            <td><input class="layui-input" type="text" name="template[id][reset_password]" value="{{ template.reset_password.id }}" lay-verify="required"></td>
            <td><input id="tc2" class="layui-input" type="text" name="template[content][reset_password]" value="{{ template.reset_password.content }}" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc2">复制</span></td>
        </tr>
        <tr>
            <td>购买课程</td>
            <td><span class="layui-badge layui-bg-blue">通知</span></td>
            <td><input class="layui-input" type="text" name="template[id][buy_course]" value="{{ template.buy_course.id }}" lay-verify="required"></td>
            <td><input id="tc3" class="layui-input" type="text" name="template[content][buy_course]" value="{{ template.buy_course.content }}" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc3">复制</span></td>
        </tr>
        <tr>
            <td>购买会员</td>
            <td><span class="layui-badge layui-bg-blue">通知</span></td>
            <td><input class="layui-input" type="text" name="template[id][buy_member]" value="{{ template.buy_member.id }}" lay-verify="required"></td>
            <td><input id="tc4" class="layui-input" type="text" name="template[content][buy_member]" value="{{ template.buy_member.content }}" lay-verify="required"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc4">复制</span></td>
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