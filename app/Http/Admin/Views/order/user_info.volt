<fieldset class="layui-elem-field layui-field-title">
    <legend>用户信息</legend>
</fieldset>

<table class="kg-table layui-table">
    <tr>
        <th>编号</th>
        <th>昵称</th>
        <th>手机</th>
        <th>邮箱</th>
    </tr>
    <tr>
        <td>{{ user.id }}</td>
        <td>{{ user.name }}</td>
        <td>{% if account.phone %} {{ account.phone }} {% else %} 未知 {% endif %}</td>
        <td>{% if account.email %} {{ account.email }} {% else %} 未知 {% endif %}</td>
    </tr>
</table>