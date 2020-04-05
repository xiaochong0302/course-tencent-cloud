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
        <th>退款原因</th>
        <th>退款状态</th>
        <th>创建时间</th>
    </tr>
    <tr>
        <td>{{ refund.sn }}</td>
        <td>￥{{ refund.amount }}</td>
        <td><span title="{{ refund.apply_note }}">{{ substr(refund.apply_note,0,15) }}</span></td>
        <td>{{ refund_status(refund) }}</td>
        <td>{{ date('Y-m-d H:i',refund.create_time) }}</td>
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
                <input type="radio" name="status" value="approved" title="同意">
                <input type="radio" name="status" value="refused" title="拒绝">
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
    <div style="text-align: center">
        <button class="layui-btn layui-bg-gray kg-back">返回上页</button>
    </div>
{% endif %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>交易信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>交易序号</th>
        <th>交易金额</th>
        <th>交易平台</th>
        <th>交易状态</th>
        <th>创建时间</th>
    </tr>
    <tr>
        <td>{{ trade.sn }}</td>
        <td>￥{{ trade.amount }}</td>
        <td>{{ channel_type(trade.channel) }}</td>
        <td>{{ trade_status(trade.status) }}</td>
        <td>{{ date('Y-m-d H:i',trade.create_time) }}</td>
    </tr>
</table>

<br>

<fieldset class="layui-elem-field layui-field-title">
    <legend>订单信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>订单序号</th>
        <th>商品名称</th>
        <th>订单金额</th>
        <th>订单状态</th>
        <th>创建时间</th>
    </tr>
    <tr>
        <td>{{ order.sn }}</td>
        <td>{{ order.subject }}</td>
        <td>￥{{ order.amount }}</td>
        <td>{{ order_status(order.status) }}</td>
        <td>{{ date('Y-m-d H:i',order.create_time) }}</td>
    </tr>
</table>

<br>

<fieldset class="layui-elem-field layui-field-title">
    <legend>用户信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>编号</th>
        <th>昵称</th>
        <th>邮箱</th>
        <th>手机</th>
    </tr>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{% if account.phone %}{{ account.phone }}{% else %}N/A{% endif %}</td>
        <td>{% if account.email %}{{ account.email }}{% else %}N/A{% endif %}</td>
    </tr>
</table>