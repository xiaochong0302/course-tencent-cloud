{% extends 'templates/full.volt' %}

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
                            <col width="15%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>成员数</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set delete_group_url = url({'for':'web.my.delete_group'}) %}
                            <tr>
                                <td><span title="{{ item.about|e }}">{{ item.name }}</span> {{ type_info(item.type) }}</td>
                                <td><span class="layui-badge-rim">{{ item.user_count }}</span></td>
                                <td><a class="layui-btn layui-btn-sm btn-delete-group" href="javascript:" data-groupId="{{ item.id }}" data-url="{{ delete_group_url }}">退出群组</a></td>
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

    {{ js_include('web/js/my.im.js') }}

{% endblock %}