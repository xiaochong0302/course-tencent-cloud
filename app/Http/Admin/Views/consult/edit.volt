{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.consult.update','id':consult.id}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑咨询</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">提问</label>
            <div class="layui-input-block">
                <div class="layui-form-mid gray">{{ consult.question }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">回复</label>
            <div class="layui-input-block">
                <textarea name="answer" class="layui-textarea">{{ consult.answer }}</textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    {% set checked = value == consult.published ? 'checked="checked"' : '' %}
                    <input type="radio" name="published" value="{{ value }}" title="{{ title }}" {{ checked }}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">私密</label>
            <div class="layui-input-block">
                <input type="radio" name="private" value="1" title="是" {% if consult.private == 1 %}checked="checked"{% endif %}>
                <input type="radio" name="private" value="0" title="否" {% if consult.private == 0 %}checked="checked"{% endif %}>
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