<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.student.create'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>添加学员</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">课程编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="course_id" value="{{ course_id }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">用户编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="user_id" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">过期时间</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="expire_time" lay-verify="required">
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

<script>

    layui.use(['laydate'], function () {

        var laydate = layui.laydate;

        laydate.render({
            elem: 'input[name=expire_time]',
            type: 'datetime'
        });

    });

</script>