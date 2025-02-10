{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.nav.create'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>添加导航</legend>
        </fieldset>
        {% if parent_id > 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">父级</label>
                <div class="layui-input-block">
                    <select name="parent_id" lay-verify="required">
                        <option value="">选择父类</option>
                        {% for nav in top_navs %}
                            <option value="{{ nav.id }}" {% if nav.id == parent_id %}selected{% endif %}>{{ nav.name }}</option>
                        {% endfor %}
                    </select>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="name" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">地址</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="url" lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="priority" value="10" lay-verify="number">
            </div>
        </div>
        {% if parent_id == 0 %}
            <div class="layui-form-item">
                <label class="layui-form-label">位置</label>
                <div class="layui-input-block">
                    <input type="radio" name="position" value="1" title="顶部" checked="checked">
                    <input type="radio" name="position" value="2" title="底部">
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label">目标</label>
            <div class="layui-input-block">
                <input type="radio" name="target" value="_blank" title="新窗口" checked="checked">
                <input type="radio" name="target" value="_self" title="原窗口">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                <input type="hidden" name="parent_id" value="{{ parent_id }}">
            </div>
        </div>
    </form>

{% endblock %}
