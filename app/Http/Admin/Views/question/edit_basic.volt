<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ question.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-block">
            <select name="category_id" lay-search="true">
                <option value="">请选择</option>
                {% for option in category_options %}
                    {% set selected = question.category_id == option.id ? 'selected="selected"' : '' %}
                    <option value="{{ option.id }}" {{ selected }}>{{ option.name }}</option>
                {% endfor %}
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标签</label>
        <div class="layui-input-block">
            <div id="xm-tag-ids"></div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">发布状态</label>
        <div class="layui-input-block">
            {% for value,title in publish_types %}
                {% set checked = value == question.published ? 'checked="checked"' : '' %}
                <input type="radio" name="published" value="{{ value }}" title="{{ title }}" {{ checked }}>
            {% endfor %}
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推荐问题</label>
        <div class="layui-input-block">
            <input type="radio" name="featured" value="1" title="是" {% if question.featured == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="featured" value="0" title="否" {% if question.featured == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关闭问题</label>
        <div class="layui-input-block">
            <input type="radio" name="closed" value="1" title="是" {% if question.closed == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="closed" value="0" title="否" {% if question.closed == 0 %}checked="checked"{% endif %}>
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