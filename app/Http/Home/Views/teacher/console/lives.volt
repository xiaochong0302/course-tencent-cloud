{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('teacher/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">课程直播</span>
                </div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table" lay-skin="line">
                        <colgroup>
                            <col>
                            <col>
                            <col width="12%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>课程/章节</th>
                            <th>直播时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
                            {% set chapter_url = url({'for':'home.chapter.show','id':item.chapter.id}) %}
                            {% set live_url = url({'for':'home.tc.live','id':item.chapter.id}) %}
                            {% set allow_push = (item.start_time - 1800 < time()) and (time() < item.start_time + 1800) %}
                            <tr>
                                <td>
                                    <p>课程：<a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a></p>
                                    <p>章节：<a href="{{ chapter_url }}" target="_blank">{{ item.chapter.title }}</a></p>
                                </td>
                                <td>
                                    <p>开始：{{ date('Y-m-d H:i',item.start_time) }}</p>
                                    <p>结束：{{ date('Y-m-d H:i',item.end_time) }}</p>
                                </td>
                                <td class="center">
                                    {% if allow_push %}
                                        <button class="layui-btn layui-btn-sm btn-live-push" data-url="{{ live_url }}">推流</button>
                                    {% else %}
                                        <button class="layui-btn layui-btn-sm layui-btn-disabled" title="开播前后半小时之内才允许推流">推流</button>
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

    {{ js_include('home/js/teacher.console.js') }}

{% endblock %}