{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的咨询</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table consult-table">
                        <colgroup>
                            <col>
                            <col>
                            <col width="20%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>内容</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set answer = item.answer ? item.answer : '请耐心等待回复吧' %}
                            {% set show_url = url({'for':'web.consult.show','id':item.id}) %}
                            {% set edit_url = url({'for':'web.consult.edit','id':item.id}) %}
                            {% set delete_url = url({'for':'web.consult.delete','id':item.id}) %}
                            <tr>
                                <td>
                                    <p class="question layui-elip" title="{{ item.question }}">提问：{{ item.question }}</p>
                                    <p class="answer layui-elip" title="{{ item.answer }}">回复：{{ answer }}</p>
                                </td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
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

    {{ js_include('web/js/my.js') }}

{% endblock %}