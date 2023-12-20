{% extends 'templates/main.volt' %}

{% block content %}

    {% set back_url = url({'for':'admin.course.chapters','id':course.id}) %}
    {% set add_chapter_url = url({'for':'admin.chapter.add'},{'type':'chapter','course_id':course.id}) %}
    {% set add_lesson_url = url({'for':'admin.chapter.add'},{'type':'lesson','course_id':course.id,'parent_id':chapter.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a class="kg-back" href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>返回</a>
                <a><cite>{{ course.title }}</cite></a>
                <a><cite>{{ chapter.title }}</cite></a>
                <a><cite>课时管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_chapter_url }}"><i class="layui-icon layui-icon-add-1"></i>添加章</a>
            <a class="layui-btn layui-btn-sm" href="{{ add_lesson_url }}"><i class="layui-icon layui-icon-add-1"></i>添加课</a>
        </div>
    </div>

    {% if course.model == 1 %}
        {{ partial('chapter/lessons_vod') }}
    {% elseif course.model == 2 %}
        {{ partial('chapter/lessons_live') }}
    {% elseif course.model == 3 %}
        {{ partial('chapter/lessons_read') }}
    {% elseif course.model == 4 %}
        {{ partial('chapter/lessons_offline') }}
    {% endif %}

{% endblock %}