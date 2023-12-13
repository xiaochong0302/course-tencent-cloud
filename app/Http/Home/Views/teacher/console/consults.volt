{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/consult') }}

    {% set status_types = {'all':'全部','pending':'待回复','replied':'已回复'} %}
    {% set status = request.get('status','trim','all') %}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('teacher/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">课程咨询</span>
                    {% for key,value in status_types %}
                        {% set class = (status == key) ? 'layui-btn layui-btn-xs' : 'none' %}
                        {% set url = url({'for':'home.tc.consults'},{'status':key}) %}
                        <a class="{{ class }}" href="{{ url }}">{{ value }}</a>
                    {% endfor %}
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table consult-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col width="15%">
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
                            {% set answer = item.answer ? item.answer : '<span class="gray">等待回复中...</span>' %}
                            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
                            {% set show_url = url({'for':'home.consult.show','id':item.id}) %}
                            {% set reply_url = url({'for':'home.consult.reply','id':item.id}) %}
                            <tr>
                                <td>
                                    <p>课程：<a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a></p>
                                    <p class="content layui-elip" title="{{ item.question }}">咨询：{{ item.question }}</p>
                                    <p class="content layui-elip" title="{{ item.answer }}">回复：{{ answer }}</p>
                                </td>
                                <td>{{ date('Y-m-d',item.create_time) }}</td>
                                <td>
                                    <button class="layui-btn layui-btn-xs layui-bg-green btn-show-consult" data-url="{{ show_url }}">详情</button>
                                    <button class="layui-btn layui-btn-xs layui-bg-blue btn-reply-consult" data-url="{{ reply_url }}">回复</button>
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

    {{ js_include('home/js/teacher.console.js') }}

{% endblock %}