<div class="layui-card layui-text">
    <div class="layui-card-header">产品动态</div>
    <div class="layui-card-body">
        <table class="layui-table">
            <colgroup>
                <col width="80%">
                <col>
            </colgroup>
            <tbody>
            {% for release in releases %}
                <tr>
                    <td><a href="{{ release.url }}" target="_blank">{{ release.title }}</a></td>
                    <td>{{ release.date }}</td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
    </div>
</div>