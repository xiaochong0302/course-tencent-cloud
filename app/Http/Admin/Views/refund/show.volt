{{ partial('order/macro') }}
{{ partial('trade/macro') }}
{{ partial('refund/macro') }}

<fieldset class="layui-elem-field layui-field-title">
    <legend>退款信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>退款序号</th>
        <th>退款金额</th>
        <th>退款备注</th>
        <th>退款状态</th>
        <th>创建时间</th>
    </tr>
    <tr>
        <td>{{ refund.sn }}</td>
        <td>￥{{ refund.amount }}</td>
        <td>
            {% if refund.apply_note %}
                <p class="layui-elip" title="{{ refund.apply_note }}">{{ refund.apply_note }}</p>
            {% endif %}
            {% if refund.review_note %}
                <p class="layui-elip" title="{{ refund.review_note }}">{{ refund.review_note }}</p>
            {% endif %}
        </td>
        <td>{{ refund_status(refund) }}</td>
        <td>{{ date('Y-m-d H:i:s',refund.create_time) }}</td>
    </tr>
</table>

<br>

{% if refund.status == 'pending' %}
    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.refund.review','id':refund.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>审核退款</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">审核结果</label>
            <div class="layui-input-block">
                <input type="radio" name="review_status" value="approved" title="同意">
                <input type="radio" name="review_status" value="refused" title="拒绝">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">审核说明</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="review_note" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>
{% else %}
    <div class="kg-text-center">
        <button class="layui-btn layui-bg-gray kg-back">返回上页</button>
    </div>
{% endif %}

<br>

{{ partial('trade/trade_info') }}

<br>

{{ partial('order/order_info') }}

<br>

{{ partial('order/user_info') }}