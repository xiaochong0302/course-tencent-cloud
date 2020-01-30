{%- macro content_title(model) %}
    {% if model == 'vod' %}
        点播信息
    {% elseif model == 'live' %}
        直播信息
    {% elseif model == 'read' %}
        文章信息
    {% endif %}
{%- endmacro %}

<fieldset class="layui-elem-field layui-field-title">
    <legend>编辑课时</legend>
</fieldset>

<div class="layui-tab layui-tab-brief">

    <ul class="layui-tab-title kg-tab-title">
        <li class="layui-this">基本信息</li>
        <li>{{ content_title(course.model) }}</li>
    </ul>

    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            {{ partial('chapter/edit_lesson_basic') }}
        </div>
        <div class="layui-tab-item">
            {% if course.model == 'vod' %}
                {{ partial('chapter/edit_lesson_vod') }}
            {% elseif course.model == 'live' %}
                {{ partial('chapter/edit_lesson_live') }}
            {% elseif course.model == 'read' %}
                {{ partial('chapter/edit_lesson_read') }}
            {% endif %}
        </div>
    </div>

</div>