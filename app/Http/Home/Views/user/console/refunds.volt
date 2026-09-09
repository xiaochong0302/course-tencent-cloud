{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/refund') }}

    {% set status_types = {'0':'全部','1':'待处理','2':'已取消','3':'已审核','4':'已拒绝','5':'已完成'} %}
    {% set status = request.get('status','int','0') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的退款</span>
                    {% for key,value in status_types %}
                        {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = (key == 'all') ? url({'for':'home.uc.refunds'}) : url({'for':'home.uc.refunds'},{'status':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_items > 0 %}
                    <table class="layui-table order-table" lay-skin="line" lay-size="lg">
                        <colgroup>
                            <col width="40%">
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>商品</th>
                            <th>金额</th>
                            <th>时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set refund_info_url = url({'for':'home.refund.info'},{'sn':item.sn}) %}
                            <tr>
                                <td>{{ item.subject }}</td>
                                <td class="red">{{ '￥%0.2f'|format(item.amount) }}</td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
                                <td>{{ refund_status(item.status) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-sm btn-refund-info" data-url="{{ refund_info_url }}">详情</button>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% else %}
                    {{ partial('partials/empty') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}
