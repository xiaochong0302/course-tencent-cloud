{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course_user') }}

    {% set add_url = url({'for':'admin.course.add_user','id':course.id}) %}
    {% set search_url = url({'for':'admin.course.search_user','id':course.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back"><i class="layui-icon layui-icon-return"></i>返回</a>
                <a><cite>{{ course.title }}</cite></a>
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
            <col>
            <col>
            <col width="12%">
        </colgroup>
        <thead>
        <tr>
            <th>用户信息</th>
            <th>学习情况</th>
            <th>来源类型</th>
            <th>加入时间</th>
            <th>过期时间</th>
            <th>最近学习</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in pager.items %}
            {% set learnings_url = url({'for':'admin.course.learnings','id':item.course_id},{'user_id':item.user_id,'plan_id':item.plan_id}) %}
            {% set user_url = url({'for':'home.user.show','id':item.user_id}) %}
            {% set expiry_time = item.expiry_time > 0 ? date('Y-m-d H:i:s',item.expiry_time) : 'N/A' %}
            {% set active_time = item.active_time > 0 ? date('Y-m-d H:i:s',item.active_time) : 'N/A' %}
            <tr>
                <td><a href="{{ user_url }}">{{ item.user.name }}</a>（{{ item.user.id }}）</td>
                <td>
                    <p>进度：{{ item.progress }}%</p>
                    <p>时长：{{ item.duration|duration }}</p>
                </td>
                <td>{{ source_type(item.source_type) }}</td>
                <td>{{ date('Y-m-d H:i:s',item.create_time) }}</td>
                <td>{{ expiry_time }}</td>
                <td>{{ active_time }}</td>
                <td class="kg-center">
                    <a class="layui-btn layui-btn-sm kg-learning" href="javascript:" data-url="{{ learnings_url }}">学习记录</a>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

    {{ partial('partials/pager') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery'], function () {

            var $ = layui.jquery;

            $('.kg-learning').on('click', function () {
                var url = $(this).data('url');
                layer.open({
                    type: 2,
                    title: '学习记录',
                    resize: false,
                    area: ['80%', '80%'],
                    content: [url]
                });
            });

        });

    </script>

{% endblock %}