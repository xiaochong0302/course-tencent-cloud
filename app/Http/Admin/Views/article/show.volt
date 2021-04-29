{% extends 'templates/main.volt' %}

{% block content %}

    {% set list_url = url({'for':'admin.article.pending_list'}) %}

    <div class="kg-nav">
        <div class="kg-nav-left">
            <span class="layui-breadcrumb">
                <a href="{{ list_url }}">审核列表</a>
                <a><cite>{{ article.title }}</cite></a>
            </span>
        </div>
    </div>

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.article.review','id':article.id}) }}">
        <div class="layui-form-item">
            <label class="layui-form-label">内容</label>
            <div class="layui-input-block">
                <div class="content markdown-body">{{ article.content|parse_markdown }}</div>
            </div>
        </div>
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
                        {% for value,name in reject_options %}
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