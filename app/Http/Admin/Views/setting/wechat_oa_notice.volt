{% set notice_template = oa.notice_template|json_decode %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.wechat_oa'}) }}">
    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col width="15%">
            <col width="15%">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>模板名称</th>
            <th>启用模板</th>
            <th>模板编号</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>登录成功通知</td>
            <td>
                <input type="radio" name="notice_template[account_login][enabled]" value="1" title="是" {% if notice_template.account_login.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notice_template[account_login][enabled]" value="0" title="否" {% if notice_template.account_login.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="notice_template[account_login][id]" value="{{ notice_template.account_login.id }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>购买成功通知</td>
            <td>
                <input type="radio" name="notice_template[order_finish][enabled]" value="1" title="是" {% if notice_template.order_finish.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notice_template[order_finish][enabled]" value="0" title="否" {% if notice_template.order_finish.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="notice_template[order_finish][id]" value="{{ notice_template.order_finish.id }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>商品发货通知</td>
            <td>
                <input type="radio" name="notice_template[goods_deliver][enabled]" value="1" title="是" {% if notice_template.goods_deliver.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notice_template[goods_deliver][enabled]" value="0" title="否" {% if notice_template.goods_deliver.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="notice_template[goods_deliver][id]" value="{{ notice_template.goods_deliver.id }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>退款成功通知</td>
            <td>
                <input type="radio" name="notice_template[refund_finish][enabled]" value="1" title="是" {% if notice_template.refund_finish.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notice_template[refund_finish][enabled]" value="0" title="否" {% if notice_template.refund_finish.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="notice_template[refund_finish][id]" value="{{ notice_template.refund_finish.id }}" lay-verify="required"></td>
        </tr>
        <!--
        <tr>
            <td>课程直播提醒</td>
            <td>
                <input type="radio" name="notice_template[live_begin][enabled]" value="1" title="是" {% if notice_template.live_begin.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notice_template[live_begin][enabled]" value="0" title="否" {% if notice_template.live_begin.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="notice_template[live_begin][id]" value="{{ notice_template.live_begin.id }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>咨询回复通知</td>
            <td>
                <input type="radio" name="notice_template[consult_reply][enabled]" value="1" title="是" {% if notice_template.consult_reply.enabled == "1" %}checked="checked"{% endif %}>
                <input type="radio" name="notice_template[consult_reply][enabled]" value="0" title="否" {% if notice_template.consult_reply.enabled == "0" %}checked="checked"{% endif %}>
            </td>
            <td><input class="layui-input" type="text" name="notice_template[consult_reply][id]" value="{{ notice_template.consult_reply.id }}" lay-verify="required"></td>
        </tr>
        -->
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