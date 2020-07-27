{{ partial('trade/macro') }}

<table class="kg-table layui-table">
    <tr>
        <td>状态</td>
        <td>时间</td>
    </tr>
    {% for item in statuses %}
        <tr>
            <td>{{ trade_status(item.status) }}</td>
            <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
        </tr>
    {% endfor %}
</table>