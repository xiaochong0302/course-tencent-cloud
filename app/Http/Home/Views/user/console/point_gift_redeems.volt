{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/point_gift') }}
    {{ partial('macros/sale') }}

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
                            <col>
                        </colgroup>
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>类型</th>
                            <th>积分</th>
                            <th>状态</th>
                            <th>时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set gift_url = url({'for':'home.point_gift.show','id':item.gift.id}) %}
                            <tr>
                                <td><a href="{{ gift_url }}" target="_blank">{{ item.gift.name }}</a></td>
                                <td>{{ sale_item_type(item.gift.type) }}</td>
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
