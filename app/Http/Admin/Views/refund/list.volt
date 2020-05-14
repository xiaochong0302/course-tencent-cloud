{{ partial('refund/macro') }}

<div class="kg-nav">
    <div class="kg-nav-left">
        <span class="layui-breadcrumb">
            <a><cite>退款管理</cite></a>
        </span>
    </div>
    <div class="kg-nav-right">
        <a class="layui-btn layui-btn-sm" href="{{ url({'for':'admin.refund.search'}) }}">
            <i class="layui-icon layui-icon-search"></i>搜索退款
        </a>
    </div>
</div>

<table class="kg-table layui-table">
    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col width="10%">
    </colgroup>
    <thead>
    <tr>
        <th>商品信息</th>
        <th>买家信息</th>
        <th>退款金额</th>
        <th>退款状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    {% for item in pager.items %}
        <tr>
            <td>
                <p>商品：{{ item.order.subject }}</p>
                <p>单号：{{ item.order.sn }}</p>
            </td>
            <td>
                <p>昵称：{{ item.user.name }}</p>
                <p>编号：{{ item.user.id }}</p>
            </td>
            <td>￥{{ item.amount }}</td>
            <td>{{ refund_status(item) }}</td>
            <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            <td align="center">
                <a class="layui-btn layui-btn-sm layui-bg-green" href="{{ url({'for':'admin.refund.show','id':item.id}) }}">详情</a>
            </td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ partial('partials/pager') }}