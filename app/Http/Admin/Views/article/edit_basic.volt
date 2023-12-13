{% set source_url_display = article.source_type == 1 ? 'display:none' : 'display:block' %}

<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <label class="layui-form-label">封面</label>
        <div class="layui-input-inline">
            <img id="img-cover" class="kg-cover" src="{{ article.cover }}">
            <input type="hidden" name="cover" value="{{ article.cover }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ article.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-block">
            <select name="category_id" lay-search="true">
                <option value="">请选择</option>
                {% for option in category_options %}
                    {% set selected = article.category_id == option.id ? 'selected="selected"' : '' %}
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
        <label class="layui-form-label">来源类型</label>
        <div class="layui-input-block">
            {% for value,title in source_types %}
                <input type="radio" name="source_type" value="{{ value }}" title="{{ title }}" {% if article.source_type == value %}checked="checked"{% endif %} lay-filter="source_type">
            {% endfor %}
        </div>
    </div>
    <div id="source-url-block" style="{{ source_url_display }}">
        <div class="layui-form-item">
            <label class="layui-form-label">来源网址</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="source_url" value="{{ article.source_url }}">
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">发布状态</label>
        <div class="layui-input-block">
            {% for value,title in publish_types %}
                {% set checked = value == article.published ? 'checked="checked"' : '' %}
                <input type="radio" name="published" value="{{ value }}" title="{{ title }}" {{ checked }}>
            {% endfor %}
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推荐文章</label>
        <div class="layui-input-block">
            <input type="radio" name="featured" value="1" title="是" {% if article.featured == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="featured" value="0" title="否" {% if article.featured == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关闭评论</label>
        <div class="layui-input-block">
            <input type="radio" name="closed" value="1" title="是" {% if article.closed == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="closed" value="0" title="否" {% if article.closed == 0 %}checked="checked"{% endif %}>
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
