{% extends 'templates/main.volt' %}

{% block content %}

    {% set owner_url = url({'for':'home.user.show','id':question.owner.id}) %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核内容</legend>
    </fieldset>

    <div class="kg-mod-preview">
        <div class="title">{{ question.title }}</div>
        <div class="meta">
            <span><a href="{{ owner_url }}" target="_blank">{{ question.owner.name }}</a></span>
            <span>{{ date('Y-m-d H:i:s',question.create_time) }}</span>
        </div>
        <div class="content ke-content">{{ question.content }}</div>
        {% if question.tags %}
            <div class="tags">
                {% for item in question.tags %}
                    <span class="layui-btn layui-btn-xs">{{ item.name }}</span>
                {% endfor %}
            </div>
        {% endif %}
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核意见</legend>
    </fieldset>

    {% set moderate_url = url({'for':'admin.question.moderate','id':question.id}) %}

    <form class="layui-form kg-form kg-mod-form" method="POST" action="{{ moderate_url }}">
        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="approve" title="通过" lay-filter="review">
                <input type="radio" name="type" value="reject" title="拒绝" lay-filter="review">
            </div>
        </div>
        <div class="layui-form-item" id="reason-block" style="display:none;">
            <label class="layui-form-label">理由</label>
            <div class="layui-input-block">
                <select name="reason">
                    <option value="">请选择</option>
                    {% for value,name in reasons %}
                        <option value="{{ value }}">{{ name }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button id="kg-submit" class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block link_css %}

    {{ css_link('home/css/content.css') }}

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'form'], function () {

            var $ = layui.jquery;
            var form = layui.form;

            form.on('radio(review)', function (data) {
                var block = $('#reason-block');
                if (data.value === 'approve') {
                    block.hide();
                } else {
                    block.show();
                }
            });

        });

    </script>

{% endblock %}