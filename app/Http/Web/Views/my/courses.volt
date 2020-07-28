{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('partials/macro_course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('my/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav-title">我的课程</div>
                {% if pager.total_pages > 0 %}
                    <table class="layui-table">
                        <colgroup>
                            <col>
                            <col>
                            <col width="12%">
                        </colgroup>
                        <thead>
                        <tr>
                            <th>课程</th>
                            <th>进度</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'web.course.show','id':item.course.id}) %}
                            {% set review_url = url({'for':'web.review.add'},{'id':item.course.id}) %}
                            <tr>
                                <td>
                                    <p>标题：<a href="{{ course_url }}">{{ item.course.title }}</a> {{ model_info(item.course.model) }}</p>
                                    <p>期限：{{ date('Y-m-d',item.expiry_time) }}</p>
                                </td>
                                <td>
                                    <p>用时：{{ item.duration|duration }}</p>
                                    <p>进度：{{ item.progress }}%</p>
                                </td>
                                <td align="center">
                                    <button class="layui-btn layui-btn-sm btn-add-review" data-url="{{ review_url }}">评价</button>
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