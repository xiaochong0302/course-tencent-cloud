{% set notice_template = oa.notice_template|json_decode %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.wechat_oa'}) }}">
    <table class="layui-table kg-table layui-form">
        <colgroup>
            <col width="15%">
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>模板名称</th>
            <th>模板编号</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>登录成功通知</td>
            <td><input class="layui-input" type="text" name="notice_template[account_login]" value="{{ notice_template.account_login }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>购买成功提醒</td>
            <td><input class="layui-input" type="text" name="notice_template[order_finish]" value="{{ notice_template.order_finish }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>退款成功通知</td>
            <td><input class="layui-input" type="text" name="notice_template[refund_finish]" value="{{ notice_template.refund_finish }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>课程直播提醒</td>
            <td><input class="layui-input" type="text" name="notice_template[live_begin]" value="{{ notice_template.live_begin }}" lay-verify="required"></td>
        </tr>
        <tr>
            <td>咨询结果通知</td>
            <td><input class="layui-input" type="text" name="notice_template[consult_reply]" value="{{ notice_template.consult_reply }}" lay-verify="required"></td>
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