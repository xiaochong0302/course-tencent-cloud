{% if pager.total_pages > 0 %}
    <table class="layui-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="18%">
        </colgroup>
        <thead>
        <tr>
            <th>头像</th>
            <th>名称</th>
            <th>类型</th>
            <th>成员</th>
            <th>讨论</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'home.im_group.show','id':item.id}) %}
            {% set edit_url = url({'for':'home.im_group.edit','id':item.id}) %}
            {% set users_url = url({'for':'home.im_group.manage_users','id':item.id},{'limit':10}) %}
            <tr>
                <td class="center">
                    <img class="avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                </td>
                <td><a href="{{ show_url }}" title="{{ item.about }}" target="_blank">{{ item.name }}</a></td>
                <td>{{ type_info(item.type) }}</td>
                <td>{{ item.user_count }}</td>
                <td>{{ item.msg_count }}</td>
                <td class="center">
                    <span class="layui-btn layui-btn-xs layui-bg-blue btn-group-user" data-url="{{ users_url }}">成员</span>
                    <span class="layui-btn layui-btn-xs btn-edit-group" data-url="{{ edit_url }}">编辑</span>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}