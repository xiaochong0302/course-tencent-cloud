{% if resources|length > 0 %}
    <table class="layui-table" lay-skin="line">
        <tr>
            <th>名称</th>
            <th>大小</th>
            <th width="15%">操作</th>
        </tr>
        {% for resource in resources %}
            <tr>
                <td>{{ resource.name }}</td>
                <td>{{ resource.size|human_size }}</td>
                {% if resource.me.owned == 1 %}
                    <td><a class="layui-btn layui-btn-sm" href="{{ resource.url }}" target="_blank">下载</a></td>
                {% else %}
                    <td><a class="layui-btn layui-btn-sm layui-btn-disabled">下载</a></td>
                {% endif %}
            </tr>
        {% endfor %}
    </table>
{% else %}
    <div class="no-records">没有相关记录</div>
{% endif %}