{% extends 'templates/main.volt' %}

{% block content %}

    {% set back_url = url({'for':'admin.course.list'}) %}
    {% set add_chapter_url = url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'chapter'}) %}
    {% set add_lesson_url = url({'for':'admin.chapter.add'},{'course_id':course.id,'type':'lesson'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>返回</a>
                <a><cite>{{ course.title }}</cite></a>
                <a><cite>章节管理</cite></a>
            </span>
        </div>
        <div class="kg-nav-right">
            <a class="layui-btn layui-btn-sm" href="{{ add_chapter_url }}"><i class="layui-icon layui-icon-add-1"></i>添加章</a>
            <a class="layui-btn layui-btn-sm" href="{{ add_lesson_url }}"><i class="layui-icon layui-icon-add-1"></i>添加课</a>
        </div>
    </div>

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>课时</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in chapters %}
            {% set child_url = url({'for':'admin.chapter.lessons','id':item.id}) %}
            {% set edit_url = url({'for':'admin.chapter.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.chapter.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.chapter.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.chapter.restore','id':item.id}) %}
            <tr>
                <td>
                    <a href="{{ child_url }}"><i class="layui-icon layui-icon-add-circle"></i> {{ item.title }}</a>
                    <span>({{ item.id }})</span>
                    <span class="layui-badge layui-bg-green">章</span>
                </td>
                <td>{{ item.lesson_count }}</td>
                <td><input class="layui-input kg-field" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}" lay-verify="number"></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            <li><a href="{{ edit_url }}">编辑章节</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除章节</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原章节</a></li>
                            {% endif %}
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}
