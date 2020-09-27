{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('order/macro') }}

    <table class="layui-table kg-table">
        <tr>
            <td>状态</td>
            <td>时间</td>
        </tr>
        {% for item in status_history %}
            <tr>
                <td>{{ order_status(item.status) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
            </tr>
        {% endfor %}
    </table>

{% endblock %}