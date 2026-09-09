<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">封面</label>
        <div class="layui-input-inline">
            <img id="img-cover" class="kg-cover" src="{{ course.cover }}">
            <input type="hidden" name="cover" value="{{ course.cover }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ course.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-block">
            <select name="category_id" lay-search="true">
                <option value="0">请选择</option>
                {% for option in category_options %}
                    {% set selected = course.category_id == option.id ? 'selected' : '' %}
                    <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">讲师</label>
        <div class="layui-input-block">
            <select name="teacher_id" lay-search="true">
                <option value="0">请选择</option>
                {% for option in teacher_options %}
                    {% set selected = course.teacher_id == option.id ? 'selected' : '' %}
                    <option value="{{ option.id }}" {{ selected }}>{{ option.name }}（{{ option.id }}）</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">难度</label>
        <div class="layui-input-block">
            {% for value,title in level_types %}
                {% set checked = course.level == value ? 'checked' : '' %}
                <input type="radio" name="level" value="{{ value }}" title="{{ title }}" {{ checked }}>
            {% endfor %}
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推荐</label>
        <div class="layui-input-block">
            <input type="radio" name="featured" value="1" title="是" {% if course.featured == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="featured" value="0" title="否" {% if course.featured == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if course.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if course.published == 0 %}checked="checked"{% endif %}>
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
