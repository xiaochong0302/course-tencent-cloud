{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_order') }}

    {% set status_types = {'all':'全部订单','pending':'待支付','finished':'已完成','closed':'已关闭','refunded':'已退款'} %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>我的订单</cite></a>
    </div>

    <div class="container">
        <div class="order-filter">
            {% set status = request.get('status','trim','all') %}
            {% for key,val in status_types %}
                {% set class = (status == key) ? 'layui-btn layui-btn-sm' : 'none' %}
                {% set url = (key == 'all') ? url({'for':'web.my.orders'}) : url({'for':'web.my.orders'},{'status':key}) %}
                <a class="{{ class }}" href="{{ url }}">{{ val }}</a>
            {% endfor %}
        </div>
        {% if pager.total_pages > 0 %}
            <table class="layui-table kg-table" lay-size="lg" lay-skin="nob">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>信息</th>
                    <th>金额</th>
                    <th>时间</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {% for item in pager.items %}
                    <tr>
                        <td>
                            <p>名称：{{ item.subject }}</p>
                            <p>单号：{{ item.sn }}</p>
                        </td>
                        <td>￥{{ item.amount }}</td>
                        <td>{{ date('Y-m-d H:i',item.create_time) }}</td>
                        <td>{{ order_status(item.status) }}</td>
                        <td align="center">
                            <a class="btn-view layui-btn layui-btn-xs" data-url="">详情</a>
                        </td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
            {{ partial('partials/pager') }}
        {% endif %}
    </div>

{% endblock %}