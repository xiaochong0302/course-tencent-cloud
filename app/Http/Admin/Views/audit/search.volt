{% extends 'templates/main.volt' %}

{% block content %}

    <form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.audit.list'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>搜索记录</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">用户编号</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_id" placeholder="用户编号精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">用户IP</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="user_ip" placeholder="用户IP精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">请求路由</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="req_route" placeholder="请求路由精确匹配">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">请求路径</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="req_path" placeholder="请求路径精确匹配">
            </div>
        </div>
        <div class="layui-form-item" id="time-range">
            <label class="layui-form-label">请求时间</label>
            <div class="layui-input-inline">
                <input class="layui-input" id="start-time" type="text" name="create_time[]">
            </div>
            <div class="layui-form-mid">-</div>
            <div class="layui-input-inline">
                <input class="layui-input" id="end-time" type="text" name="create_time[]">
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

            laydate.render({
                elem: '#time-range',
                type: 'datetime',
                range: ['#start-time', '#end-time'],
            });

        });

    </script>

{% endblock %}
