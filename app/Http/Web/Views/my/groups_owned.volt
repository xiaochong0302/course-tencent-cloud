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
            <th>群主</th>
            <th>成员</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set owner_url = url({'for':'web.user.show','id':item.owner.id}) %}
            {% set edit_url = url({'for':'web.im_group.edit','id':item.id}) %}
            {% set users_url = url({'for':'web.im_group.users','id':item.id}) %}
            <tr>
                <td><span title="{{ item.about }}">{{ item.name }}</span> {{ type_info(item.type) }}</td>
                <td><a href="{{ owner_url }}">{{ item.owner.name }}</a></td>
                <td><span class="layui-badge-rim">{{ item.user_count }}</span></td>
                <td>
                    <button class="layui-btn layui-btn-xs layui-bg-blue btn-group-user" data-url="{{ users_url }}">成员</button>
                    <button class="layui-btn layui-btn-xs btn-edit-group" data-url="{{ edit_url }}">编辑</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}