{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.trade.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索交易</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">订单编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="order_id" placeholder="订单编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="owner_id" placeholder="用户编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">交易平台</label>
            <div class="layui-input-block">
                {% for value,title in channel_types %}
                    <input type="radio" name="channel" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">交易状态</label>
            <div class="layui-input-block">
                {% for value,title in status_types %}
                    <input type="radio" name="status" value="{{ value }}" title="{{ title }}">
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">创建时间</label>
            <div class="layui-input-inline">
                <input class="layui-input time-range" type="text" name="start_time" autocomplete="off">
            </div>
            <div class="layui-form-mid"> -</div>
            <div class="layui-input-inline">
                <input class="layui-input time-range" type="text" name="end_time" autocomplete="off">
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

        layui.use(['laydate'], function () {

            var laydate = layui.laydate;

            lay('.time-range').each(function () {
                laydate.render({
                    elem: this,
                    type: 'datetime',
                    trigger: 'click'
                });
            });

        });

    </script>

{% endblock %}