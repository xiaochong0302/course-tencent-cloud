{% if pager.total_pages > 0 %}
    <table class="layui-table" lay-size="lg" lay-skin="line">
        <colgroup>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>问题</th>
            <th>回答</th>
            <th>浏览</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set question_url = url({'for':'home.question.show','id':item.id}) %}
            {% set favorite_url = url({'for':'home.question.favorite','id':item.id}) %}
            <tr>
                <td><a href="{{ question_url }}" target="_blank">{{ item.title }}</a></td>
                <td>{{ item.answer_count }}</td>
                <td>{{ item.view_count }}</td>
                <td class="center">
                    <button class="layui-btn layui-btn-sm layui-bg-red kg-delete" data-tips="确定要取消收藏吗？" data-url="{{ favorite_url }}">取消</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}