{%- macro content_label(target) %}
    {% if target == 'course' %}
        课程编号
    {% elseif target == 'page' %}
        单页编号
    {% elseif target == 'link' %}
        链接地址
    {% endif %}
{%- endmacro %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.slide.update','id':slide.id}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑轮播</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">封面</label>
        <div class="layui-input-inline">
            {% if slide.cover %}
                <img id="cover-img" class="kg-cover" src="{{ ci_img(slide.cover) }}">
            {% else %}
                <img id="cover-img" class="kg-cover" src="{{ image('admin/img/default_cover.png') }}">
            {% endif %}
            <input type="hidden" name="cover" value="{{ ci_img(slide.cover) }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <a href="javascript:" class="layui-btn layui-btn-sm" id="choose-cover">编辑</a>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ slide.title }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">概要</label>
        <div class="layui-input-block">
            <textarea class="layui-textarea" name="summary">{{ slide.summary }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">{{ content_label(slide.target) }}</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="content" value="{{ slide.content }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">排序</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="priority" value="{{ slide.priority }}" lay-verify="number">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if slide.published == 1 %}checked{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if slide.published == 0 %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

{{ partial('partials/cover_uploader') }}