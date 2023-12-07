{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/order') }}

    {% set status_types = {'0':'全部','1':'待支付','3':'已完成','4':'已关闭','5':'已退款'} %}
    {% set status = request.get('status','trim','0') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的订单</span>
                    {% for key,value in status_types %}
                        {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = (key == '0') ? url({'for':'home.uc.orders'}) : url({'for':'home.uc.orders'},{'status':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col width="12%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>商品</th>
                            <th>价格</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set order_info_url = url({'for':'home.order.info'},{'sn':item.sn}) %}
                            <tr>
                                <td>
                                    <p>{{ item.subject }}</p>
                                    <p class="meta">
                                        <span>编号：{{ item.sn }}</span>
                                        <span>时间：{{ date('Y-m-d',item.create_time) }}</span>
                                    </p>
                                </td>
                                <td class="red">{{ '￥%0.2f'|format(item.amount) }}</td>
                                <td>{{ order_status(item.status) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-sm btn-order-info" data-url="{{ order_info_url }}">详情</button>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('home/js/user.console.js') }}

{% endblock %}