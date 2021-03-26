{% if pager.total_pages > 0 %}
    <table class="layui-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>头像</th>
            <th>名称</th>
            <th>类型</th>
            <th>组长</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'home.im_group.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set delete_url = url({'for':'home.im.quit_group','id':item.id}) %}
            {% set is_owner = auth_user.id == item.owner.id ? 1 : 0 %}
            <tr>
                <td class="center">
                    <img class="avatar-sm" src="{{ item.avatar }}!avatar_160" alt="{{ item.name }}">
                </td>
                <td><a href="{{ show_url }}" title="{{ item.about }}">{{ item.name }}</a></td>
                <td>{{ type_info(item.type) }}</td>
                <td><a href="{{ owner_url }}">{{ item.owner.name }}</a></td>
                <td class="center">
                    {% if is_owner == 0 %}
                        <button class="layui-btn layui-btn-sm layui-bg-red kg-delete" data-tips="确定要退出吗？" data-url="{{ delete_url }}">退出</button>
                    {% else %}
                        <button class="layui-btn layui-btn-sm layui-btn-disabled">退出</button>
                    {% endif %}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}
