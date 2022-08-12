{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course_user') }}
    {{ partial('macros/course') }}

    <div class="layout-main">
        <div class="my-sidebar">{{ partial('user/console/menu') }}</div>
        <div class="my-content">
            <div class="wrap">
                <div class="my-nav">
                    <span class="title">我的课程</span>
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
                            <th>课程</th>
                            <th>进度</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {% for item in pager.items %}
                            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
                            {% set review_url = url({'for':'home.review.add'},{'course_id':item.course.id}) %}
                            {% set allow_review = item.progress > 30 and item.reviewed == 0 %}
                            <tr>
                                <td>
                                    <p>标题：<a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a></p>
                                    <p class="meta">
                                        类型：<span class="layui-badge layui-bg-gray">{{ model_type(item.course.model) }}</span>
                                        来源：<span class="layui-badge layui-bg-gray">{{ source_type_info(item.source_type) }}</span>
                                        {% if item.expiry_time > 0 %}
                                            期限：{{ date('Y-m-d',item.expiry_time) }}
                                        {% endif %}
                                    </p>
                                </td>
                                <td>
                                    <p>用时：{{ item.duration|duration }}</p>
                                    <p>进度：{{ item.progress }}%</p>
                                </td>
                                <td class="center">
                                    {% if allow_review %}
                                        <button class="layui-btn layui-btn-sm btn-add-review" data-url="{{ review_url }}">评价</button>
                                    {% else %}
                                        <button class="layui-btn layui-btn-sm layui-btn-disabled" title="学习进度过30%才允许评价">评价</button>
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

    {{ js_include('home/js/user.console.js') }}

{% endblock %}