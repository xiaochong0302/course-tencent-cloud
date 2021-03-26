{% extends 'templates/layer.volt' %}

{% block content %}

    {{ partial('macros/user') }}

    <table class="layui-table mt0">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>头像</th>
            <th>名称</th>
            <th>地区</th>
            <th>性别</th>
            <th>加入时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set user_url = url({'for':'home.user.show','id':item.user.id}) %}
            {% set delete_url = url({'for':'home.im_group_user.delete'},{'group_id':group.id,'user_id':item.user.id}) %}
            {% set is_owner = item.user.id == group.owner.id ? 1 : 0 %}
            <tr>
                <td class="center">
                    <img class="avatar-sm" src="{{ item.user.avatar }}!avatar_160" alt="{{ item.user.name }}">
                </td>
                <td><a href="{{ user_url }}" title="{{ item.user.about }}" target="_blank">{{ item.user.name }}</a>（{{ item.user.id }}）</td>
                <td>{{ item.user.area }}</td>
                <td>{{ gender_info(item.user.gender) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td class="center">
                    {% if is_owner == 0 %}
                        <button class="layui-btn layui-btn-sm layui-bg-red kg-delete" data-url="{{ delete_url }}">删除</button>
                    {% else %}
                        <button class="layui-btn layui-btn-sm layui-btn-disabled">删除</button>
                    {% endif %}
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

