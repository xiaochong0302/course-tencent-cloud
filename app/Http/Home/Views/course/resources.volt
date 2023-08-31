{% if items|length > 0 %}
<table class="layui-table" lay-skin="line">
    <tr>
        <th>名称</th>
        <th>大小</th>
        <th width="15%">操作</th>
    </tr>
    {% for item in items %}
        <tr>
            <td>{{ item.name }}</td>
            <td>{{ item.size|human_size }}</td>
            {% if item.me.owned == 1 and auth_user.id > 0 %}
                <td><a class="layui-btn layui-btn-sm" href="{{ item.url }}" target="_blank">下载</a></td>
            {% else %}
                <td><a class="layui-btn layui-btn-sm layui-btn-disabled">下载</a></td>
            {% endif %}
        </tr>
    {% endfor %}
</table>
{% endif %}