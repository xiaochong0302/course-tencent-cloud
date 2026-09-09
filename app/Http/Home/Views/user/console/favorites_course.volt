{% if pager.total_items > 0 %}
    <table class="layui-table" lay-skin="line" lay-size="lg">
        <colgroup>
            <col>
            <col>
            <col>
            <col width="15%">
        </colgroup>
        <thead>
        <tr>
            <th>标题</th>
            <th>学员</th>
            <th>收藏</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set course_url = url({'for':'home.course.show','id':item.id}) %}
            {% set favorite_url = url({'for':'home.course.favorite','id':item.id}) %}
            <tr>
                <td><a href="{{ course_url }}" target="_blank">{{ item.title }}</a></td>
                <td>{{ item.user_count|human_number }}</td>
                <td>{{ item.favorite_count|human_number }}</td>
                <td>
                    <button class="layui-btn layui-btn-sm layui-btn-danger kg-delete" data-tips="确定要取消收藏吗？" data-url="{{ favorite_url }}">取消</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% else %}
    {{ partial('partials/empty') }}
{% endif %}
