{% extends 'templates/full.volt' %}

{% block content %}

    {{ partial('partials/macro_refund') }}

    {% set status_types = {'all':'全部','pending':'待处理','canceled':'已取消','approved':'退款中','finished':'已完成'} %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>我的退款</cite></a>
    </div>

    <div class="wrap">
        <div class="order-filter">
            {% set status = request.get('status','trim','all') %}
            {% for key,value in status_types %}
                {% set class = (status == key) ? 'layui-btn layui-btn-sm' : 'none' %}
                {% set url = (key == 'all') ? url({'for':'web.my.refunds'}) : url({'for':'web.my.refunds'},{'status':key}) %}
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
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>退款项目</th>
                    <th>订单金额</th>
                    <th>退款金额</th>
                    <th>创建时间</th>
                    <th>退款状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                {% for item in pager.items %}
                    {% set info_url = url({'for':'web.refund.info'},{'sn':item.sn}) %}
                    <tr>
                        <td>{{ item.subject }}</td>
                        <td><span class="price">{{ '￥%0.2f'|format(item.order.amount) }}</span></td>
                        <td><span class="price">{{ '￥%0.2f'|format(item.amount) }}</span></td>
                        <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                        <td>{{ refund_status(item.status) }}</td>
                        <td align="center">
                            <a class="layui-btn layui-btn-sm" href="{{ info_url }}">退款详情</a>
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