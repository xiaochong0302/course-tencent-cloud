{% if pager.total_pages > 0 %}
    <table class="layui-table" lay-size="lg">
        <colgroup>
            <col>
            <col>
            <col>
            <col width="18%">
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>成员</th>
            <th>讨论</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set edit_url = url({'for':'web.igm.edit','id':item.id}) %}
            {% set users_url = url({'for':'web.igm.users','id':item.id}) %}
            <tr>
                <td><span title="{{ item.about }}">{{ item.name }}</span> {{ type_info(item.type) }}</td>
                <td><span class="layui-badge-rim">{{ item.user_count }}</span></td>
                <td><span class="layui-badge-rim">{{ item.msg_count }}</span></td>
                <td>
                    <span class="layui-btn layui-btn-xs layui-bg-blue btn-group-user" data-url="{{ users_url }}">成员</span>
                    <span class="layui-btn layui-btn-xs btn-edit-group" data-url="{{ edit_url }}">编辑</span>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}