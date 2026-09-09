<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.sms'}) }}">
    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col width="12%">
            <col width="12%">
            <col width="12%">
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>启用模板</th>
            <th>模板编号</th>
            <th>模板内容</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>用户身份验证</td>
            <td>
                <input type="radio" name="template[verify][enabled]" value="1" title="是" disabled="disabled" {% if template.verify.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="template[verify][enabled]" value="0" title="否" disabled="disabled" {% if template.verify.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="template[verify][id]" value="{{ template.verify.id }}" lay-verify="required"></td>
            <td><input id="tc-verify" class="layui-input" type="text" value="验证码：{1}，{2} 分钟内有效，如非本人操作请忽略。" readonly="readonly"></td>
            <td><span class="kg-copy layui-btn" data-clipboard-target="#tc-verify">复制</span></td>
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
