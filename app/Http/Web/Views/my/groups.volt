{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro type_info(value) %}
        {% if value == 'course' %}
            <span class="layui-badge layui-bg-green">课</span>
        {% elseif value == 'chat' %}
            <span class="layui-badge layui-bg-blue">聊</span>
        {% endif %}
    {%- endmacro %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav-title">我的群组</div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-size="lg">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col width="15%">
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
                            {% set manage_url = url({'for':'web.im_group.users','id':item.id}) %}
                            {% set delete_url = url({'for':'web.im.quit_group','id':item.id}) %}
                            <tr>
                                <td><span title="{{ item.about }}">{{ item.name }}</span> {{ type_info(item.type) }}</td>
                                <td><a href="{{ owner_url }}">{{ item.owner.name }}</a></td>
                                <td><span class="layui-badge-rim">{{ item.user_count }}</span></td>
                                <td>
                                    {% if auth_user.id == item.owner.id %}
                                        <button class="layui-btn layui-btn-sm layui-bg-blue btn-manage-group" data-url="{{ manage_url }}">管理</button>
                                    {% else %}
                                        <button class="layui-btn layui-btn-sm kg-delete" data-tips="确定要退出吗？" data-url="{{ delete_url }}">退出</button>
                                    {% endif %}
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                    {{ partial('partials/pager') }}
                {% endif %}
            </div>
        </div>
    </div>

{% endblock %}

{% block include_js %}

    {{ js_include('web/js/my.js') }}

{% endblock %}