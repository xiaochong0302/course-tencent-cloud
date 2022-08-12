{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的咨询</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table consult-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col width="20%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>内容</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set item.answer = item.answer ? item.answer : '请耐心等待回复吧' %}
                            {% set show_url = url({'for':'home.consult.show','id':item.id}) %}
                            {% set edit_url = url({'for':'home.consult.edit','id':item.id}) %}
                            {% set delete_url = url({'for':'home.consult.delete','id':item.id}) %}
                            <tr>
                                <td>
                                    <p class="content layui-elip" title="{{ item.question }}">咨询：{{ item.question }}</p>
                                    <p class="content layui-elip" title="{{ item.answer }}">回复：{{ item.answer }}</p>
                                    <p class="time">时间：{{ item.create_time|time_ago }}</p>
                                </td>
                                <td>
                                    <button class="layui-btn layui-btn-xs layui-bg-green btn-show-consult" data-url="{{ show_url }}">详情</button>
                                    <button class="layui-btn layui-btn-xs layui-bg-blue btn-edit-consult" data-url="{{ edit_url }}">修改</button>
                                    <button class="layui-btn layui-btn-xs layui-bg-red kg-delete" data-url="{{ delete_url }}">删除</button>
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

    {{ js_include('home/js/user.console.js') }}

{% endblock %}