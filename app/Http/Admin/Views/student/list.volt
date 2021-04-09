{% extends 'templates/main.volt' %}

{% block content %}

    {%- macro source_type_info(value) %}
        {% if value == 1 %}
            免费
        {% elseif value == 2 %}
            付费
        {% elseif value == 3 %}
            畅学
        {% elseif value == 4 %}
            导入
        {% elseif value == 5 %}
            积分
        {% elseif value == 6 %}
            抽奖
        {% endif %}
    {%- endmacro %}

    {% set add_url = url({'for':'admin.student.add'}) %}
    {% set search_url = url({'for':'admin.student.search'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back"><i class="layui-icon layui-icon-return"></i>返回</a>
                {% if course %}
                    <a><cite>{{ course.title }}</cite></a>
                {% endif %}
                <a><cite>学员管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_url }}">
                <i class="layui-icon layui-icon-add-1"></i>添加学员
            </a>
            <a class="layui-btn layui-btn-sm" href="{{ search_url }}">
                <i class="layui-icon layui-icon-search"></i>搜索学员
            </a>
        </div>
    </div>

    <table class="layui-table kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>基本信息</th>
            <th>学习情况</th>
            <th>来源类型</th>
            <th>有效期限</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set learning_url = url({'for':'admin.student.learning'},{'course_id':item.course_id,'user_id':item.user_id,'plan_id':item.plan_id}) %}
            {% set course_url = url({'for':'home.course.show','id':item.course.id}) %}
            {% set user_url = url({'for':'home.user.show','id':item.user_id}) %}
            {% set edit_url = url({'for':'admin.student.edit'},{'relation_id':item.id}) %}
            <tr>
                <td>
                    <p>课程：<a href="{{ course_url }}" target="_blank">{{ item.course.title }}</a>（{{ item.course.id }}）</p>
                    <p>学员：<a href="{{ user_url }}" target="_blank">{{ item.user.name }}</a>（{{ item.user.id }}）</p>
                </td>
                <td>
                    <p>进度：<a href="javascript:" class="kg-learning" title="学习记录" data-url="{{ learning_url }}">{{ item.progress }}%</a></p>
                    <p>时长：{{ item.duration|duration }}</p>
                </td>
                <td>{{ source_type_info(item.source_type) }}</td>
                <td>
                    {% if item.source_type in [1,3] %}
                        N/A
                    {% else %}
                        <p>开始：{{ date('Y-m-d H:i',item.create_time) }}</p>
                        <p>结束：{{ date('Y-m-d H:i',item.expiry_time) }}</p>
                    {% endif %}
                </td>
                <td class="center">
                    <div class="layui-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ edit_url }}">编辑学员</a></li>
                            <li><a href="javascript:" class="kg-learning" data-url="{{ learning_url }}">学习记录</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;

            $('.kg-learning').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    id: 'xm-course',
                    type: 2,
                    title: '学习记录',
                    resize: false,
                    area: ['900px', '450px'],
                    content: [url]
                });
            });

        });

    </script>

{% endblock %}