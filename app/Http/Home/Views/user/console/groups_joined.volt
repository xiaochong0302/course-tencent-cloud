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
            <th>名称</th>
            <th>组长</th>
            <th>成员</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set show_url = url({'for':'home.im_group.show','id':item.id}) %}
            {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
            {% set delete_url = url({'for':'home.im.quit_group','id':item.id}) %}
            <tr>
                <td><a href="{{ show_url }}" title="{{ item.about }}">{{ item.name }}</a> {{ type_info(item.type) }}</td>
                <td><a href="{{ owner_url }}">{{ item.owner.name }}</a></td>
                <td>{{ item.user_count }}</td>
                <td>
                    <button class="layui-btn layui-btn-sm kg-delete" data-tips="确定要退出吗？" data-url="{{ delete_url }}">退出</button>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
    {{ partial('partials/pager') }}
{% endif %}
