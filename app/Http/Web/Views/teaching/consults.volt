{% extends 'templates/main.volt' %}

{% block content %}

    {% set status_types = {'all':'全部','pending':'待回复','replied':'已回复'} %}
    {% set status = request.get('status','trim','all') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('teaching/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">用户咨询</span>
                    {% for key,value in status_types %}
                        {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = url({'for':'web.teaching.consults'},{'status':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
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
                            {% set answer = item.answer ? item.answer : '<span class="gray">等待回复ing...</span>' %}
                            {% set course_url = url({'for':'web.course.show','id':item.course.id}) %}
                            {% set show_url = url({'for':'web.consult.show','id':item.id}) %}
                            {% set reply_url = url({'for':'web.consult.reply','id':item.id}) %}
                            {% set delete_url = url({'for':'web.consult.delete','id':item.id}) %}
                            <tr>
                                <td>
                                    <p>课程：<a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a></p>
                                    <p class="question layui-elip" title="{{ item.question }}">提问：{{ item.question }}</p>
                                    <p class="answer layui-elip" title="{{ item.answer }}">回复：{{ answer }}</p>
                                </td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-xs layui-bg-green btn-show-consult" data-url="{{ show_url }}">详情</button>
                                    <button class="layui-btn layui-btn-xs layui-bg-blue btn-reply-consult" data-url="{{ reply_url }}">回复</button>
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

    {{ js_include('web/js/teaching.js') }}

{% endblock %}