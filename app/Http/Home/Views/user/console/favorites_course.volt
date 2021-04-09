{% if pager.total_pages > 0 %}
    <table class="layui-table" lay-size="lg">
        <colgroup>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>课程</th>
            <th>学员</th>
            <th>评分</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set course_url = url({'for':'home.course.show','id':item.id}) %}
            {% set favorite_url = url({'for':'home.course.favorite','id':item.id}) %}
            <tr>
                <td>
                    <a href="{{ course_url }}" target="_blank">{{ item.title }}</a>
                    <span class="layui-badge layui-bg-gray">{{ model_info(item.model) }}</span>
                </td>
                <td>{{ item.user_count }}</td>
                <td>{{ "%0.1f"|format(item.rating) }}</td>
                <td class="center">
                    <button class="layui-btn layui-btn-sm kg-delete" data-tips="确定要取消收藏吗？" data-url="{{ favorite_url }}">取消</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}