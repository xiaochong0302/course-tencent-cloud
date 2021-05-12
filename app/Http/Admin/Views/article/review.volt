{% extends 'templates/main.volt' %}

{% block content %}

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核内容</legend>
    </fieldset>

    <div class="kg-mod-preview">
        <div class="title">{{ article.title }}</div>
        <div class="content markdown-body">{{ article.content }}</div>
        {% if article.tags %}
            <div class="tags">
                {% for item in article.tags %}
                    <span class="layui-btn layui-btn-xs">{{ item['name'] }}</span>
                {% endfor %}
            </div>
        {% endif %}
    </div>

    <fieldset class="layui-elem-field layui-field-title">
        <legend>审核意见</legend>
    </fieldset>

    <form class="layui-form kg-form kg-review-form" method="POST" action="{{ url({'for':'admin.article.review','id':article.id}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">审核</label>
            <div class="layui-input-block">
                <input type="radio" name="type" value="approve" title="通过" lay-filter="review">
                <input type="radio" name="type" value="reject" title="拒绝" lay-filter="review">
            </div>
        </div>
        <div id="reason-block" style="display:none;">
            <div class="layui-form-item">
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

    {{ css_link('home/css/markdown.css') }}

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