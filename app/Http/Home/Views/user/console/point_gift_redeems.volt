{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point_gift') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">兑换记录</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-size="lg" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>物品名称</th>
                            <th>消耗积分</th>
                            <th>兑换状态</th>
                            <th>兑换时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set gift_url = url({'for':'home.point_gift.show','id':item.gift.id}) %}
                            <tr>
                                <td><a href="{{ gift_url }}" target="_blank">{{ item.gift.name }}</a> {{ gift_type(item.gift.type) }}</td>
                                <td>{{ item.gift.point }}</td>
                                <td>{{ redeem_status(item.status) }}</td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
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