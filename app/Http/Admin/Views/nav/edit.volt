{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.nav.update','id':nav.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑导航</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" value="{{ nav.name }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">地址</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="url" value="{{ nav.url }}" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="{{ nav.priority }}" lay-verify="number">
            </div>
        </div>
        {% if nav.parent_id == 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">位置</label>
                <div class="layui-input-block">
                    <input type="radio" name="position" value="1" title="顶部" {% if nav.position == 1 %}checked="checked"{% endif %}>
                    <input type="radio" name="position" value="2" title="底部" {% if nav.position == 2 %}checked="checked"{% endif %}>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">目标</label>
            <div class="layui-input-block">
                <input type="radio" name="target" value="_blank" title="新窗口" {% if nav.target == '_blank' %}checked="checked"{% endif %}>
                <input type="radio" name="target" value="_self" title="原窗口" {% if nav.target == '_self' %}checked="checked"{% endif %}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                <input type="radio" name="published" value="1" title="是" {% if nav.published == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="published" value="0" title="否" {% if nav.published == 0 %}checked="checked"{% endif %}>
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

{% endblock %}
