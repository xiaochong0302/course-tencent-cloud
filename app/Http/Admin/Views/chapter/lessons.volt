{% extends 'templates/main.volt' %}

{% block content %}

    {{ partial('macros/course') }}

    {%- macro attrs_info(model,attrs) %}
        {% if model == 1 %}
            <span>类型：{{ model_type(model) }}</span>
            {% if attrs['duration'] > 0 %}
                <span>时长：{{ attrs['duration']|duration }}</span>
            {% else %}
                <span>时长：N/A</span>
            {% endif %}
        {% elseif model == 2 %}
            <span>类型：{{ model_type(model) }}</span>
            {% if attrs['start_time'] > 0 %}
                <span>时间：{{ date('Y-m-d H:i',attrs['start_time']) }}</span>
            {% else %}
                <span>时间：N/A</span>
            {% endif %}
        {% elseif model == 3 %}
            <span>类型：{{ model_type(model) }}</span>
            {% if attrs['word_count'] > 0 %}
                <span>字数：{{ attrs['word_count'] }}</span>
            {% else %}
                <span>字数：N/A</span>
            {% endif %}
        {% elseif model == 4 %}
            <span>类型：{{ model_type(model) }}</span>
            {% if attrs['start_time'] > 0 %}
                <span>时间：{{ date('Y-m-d H:i',attrs['start_time']) }}</span>
            {% else %}
                <span>时间：N/A</span>
            {% endif %}
        {% endif %}
    {%- endmacro %}

    {% set back_url = url({'for':'admin.course.chapters','id':course.id}) %}
    {% set add_chapter_url = url({'for':'admin.chapter.add'},{'type':'chapter','course_id':course.id}) %}
    {% set add_lesson_url = url({'for':'admin.chapter.add'},{'type':'lesson','course_id':course.id,'parent_id':chapter.id}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a href="{{ back_url }}"><i class="layui-icon layui-icon-return"></i>返回</a>
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

    <table class="layui-table layui-form kg-table">
        <colgroup>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col>
            <col width="10%">
        </colgroup>
        <thead>
        <tr>
            <th>名称</th>
            <th>学员</th>
            <th>点赞</th>
            <th>评论</th>
            <th>排序</th>
            <th>免费</th>
            <th>发布</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {% for item in lessons %}
            {% set chapter_url = url({'for':'home.chapter.show','id':item.id}) %}
            {% set edit_url = url({'for':'admin.chapter.edit','id':item.id}) %}
            {% set update_url = url({'for':'admin.chapter.update','id':item.id}) %}
            {% set delete_url = url({'for':'admin.chapter.delete','id':item.id}) %}
            {% set restore_url = url({'for':'admin.chapter.restore','id':item.id}) %}
            {% set comments_url = url({'for':'admin.comment.list'},{'item_id':item.id,'item_type':1}) %}
            <tr>
                <td>
                    <p>
                        <a href="{{ edit_url }}">{{ item.title }}</a>
                        <span>({{ item.id }})</span>
                        <span class="layui-badge layui-bg-green">课</span>
                    </p>
                    <p>{{ attrs_info(item.model,item.attrs) }}</p>
                </td>
                <td>{{ item.user_count }}</td>
                <td>{{ item.like_count }}</td>
                <td>{{ item.comment_count }}</td>
                <td><input class="layui-input kg-priority" type="text" name="priority" title="数值越小排序越靠前" value="{{ item.priority }}" data-url="{{ update_url }}"></td>
                <td><input type="checkbox" name="free" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}" {% if item.free == 1 %}checked="checked"{% endif %}>
                </td>
                <td><input type="checkbox" name="published" value="1" lay-text="是|否" lay-skin="switch" lay-filter="go" data-url="{{ update_url }}"
                           {% if item.published == 1 %}checked="checked"{% endif %}></td>
                <td class="center">
                    <div class="kg-dropdown">
                        <button class="layui-btn layui-btn-sm">操作 <i class="layui-icon layui-icon-triangle-d"></i></button>
                        <ul>
                            {% if item.published == 1 %}
                                <li><a href="{{ chapter_url }}" target="_blank">浏览课时</a></li>
                            {% endif %}
                            <li><a href="{{ edit_url }}">编辑课时</a></li>
                            {% if item.deleted == 0 %}
                                <li><a href="javascript:" class="kg-delete" data-url="{{ delete_url }}">删除课时</a></li>
                            {% else %}
                                <li><a href="javascript:" class="kg-restore" data-url="{{ restore_url }}">还原课时</a></li>
                            {% endif %}
                            <hr>
                            <li><a href="{{ comments_url }}">评论管理</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>

{% endblock %}
