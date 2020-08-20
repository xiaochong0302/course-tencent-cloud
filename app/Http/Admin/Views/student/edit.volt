{% extends 'templates/main.volt' %}

{% block content %}

    {% set expiry_editable = relation.source_type in [1,3] %}

    <form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.student.update'}) }}">
        <fieldset class="layui-elem-field layui-field-title">
            <legend>编辑学员</legend>
        </fieldset>
        <div class="layui-form-item">
            <label class="layui-form-label">课程名称</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ course.title }}</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学员名称</label>
            <div class="layui-input-block">
                <div class="layui-form-mid layui-word-aux">{{ student.name }}</div>
            </div>
        </div>
        {% if expiry_editable %}
            <div class="layui-form-item">
                <label class="layui-form-label">过期时间</label>
                <div class="layui-input-block">
                    <input class="layui-input" type="text" name="expiry_time" autocomplete="off" value="{{ date('Y-m-d H:i:s',relation.expiry_time) }}" lay-verify="required">
                </div>
            </div>
        {% else %}
            <div class="layui-form-item">
                <label class="layui-form-label">过期时间</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid layui-word-aux">{{ date('Y-m-d H:i:s',relation.expiry_time) }}</div>
                </div>
            </div>
        {% endif %}
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
                <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
                <input type="hidden" name="relation_id" value="{{ relation.id }}"/>
            </div>
        </div>
    </form>

{% endblock %}

{% block inline_js %}

    <script>

        layui.use(['laydate'], function () {

            var laydate = layui.laydate;

            laydate.render({
                elem: 'input[name=expiry_time]',
                type: 'datetime'
            });

        });

    </script>

{% endblock %}