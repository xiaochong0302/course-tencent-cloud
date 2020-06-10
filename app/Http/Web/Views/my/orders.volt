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
            {% for key,value in status_types %}
                {% set class = (status == key) ? 'layui-btn layui-btn-sm' : 'none' %}
                {% set url = (key == 'all') ? url({'for':'web.my.orders'}) : url({'for':'web.my.orders'},{'status':key}) %}
                <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
            {% endfor %}
        </div>
        {% if pager.total_pages > 0 %}
            <table class="layui-table order-table kg-table" lay-size="lg" lay-skin="nob">
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>基本信息</th>
                    <th>订单金额</th>
                    <th>创建时间</th>
                    <th>订单状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {% for item in pager.items %}
                    {% set info_url = url({'for':'web.order.info'},{'sn':item.sn}) %}
                    <tr>
                        <td>
                            <p>名称：{{ item.subject }}</p>
                            <p>单号：{{ item.sn }}</p>
                        </td>
                        <td><span class="price">{{ '￥%0.2f'|format(item.amount) }}</span></td>
                        <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                        <td>{{ order_status(item.status) }}</td>
                        <td align="center">
                            <a class="layui-btn layui-btn-sm" href="{{ info_url }}">订单详情</a>
                        </td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
            {{ partial('partials/pager') }}
        {% else %}
            <div class="search-empty">未发现相关记录</div>
        {% endif %}
    </div>

{% endblock %}