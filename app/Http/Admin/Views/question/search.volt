{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.question.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索问题</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">问题编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="id" placeholder="问题编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户帐号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" placeholder="用户编号 / 手机号码 / 邮箱地址 精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">问题标题</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="title" placeholder="标题模糊匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">问题分类</label>
            <div class="layui-input-block">
                <select name="category_id" lay-search="true">
                    <option value="">请选择</option>
                    {% for option in category_options %}
                        <option value="{{ option.id }}">{{ option.name }}</option>
                    {% endfor %}
                </select>
            </div>
        </div>
        <div class="layui-form-item" id="time-range">
            <label class="layui-form-label">创建时间</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="start-time" type="text" name="create_time[]" autocomplete="off">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="end-time" type="text" name="create_time[]" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发布状态</label>
            <div class="layui-input-block">
                {% for value,title in publish_types %}
                    <input type="checkbox" name="published[]" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">推荐</label>
            <div class="layui-input-block">
                <input type="radio" name="featured" value="1" title="是">
                <input type="radio" name="featured" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">关闭</label>
            <div class="layui-input-block">
                <input type="radio" name="closed" value="1" title="是">
                <input type="radio" name="closed" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">删除</label>
            <div class="layui-input-block">
                <input type="radio" name="deleted" value="1" title="是">
                <input type="radio" name="deleted" value="0" title="否">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['jquery', 'laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: '#time-range',
                type: 'datetime',
                range: ['#start-time', '#end-time'],
            });

        });

    </script>

{% endblock %}