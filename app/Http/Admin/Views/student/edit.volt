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

    <div class="layui-form-item">
        <label class="layui-form-label">锁定</label>
        <div class="layui-input-block">
            <input type="radio" name="locked" value="1" title="是" {% if course_student.locked == 1 %}checked{% endif %}>
            <input type="radio" name="locked" value="0" title="否" {% if course_student.locked == 0 %}checked{% endif %}>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">过期时间</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="expire_time" autocomplete="off" value="{{ date('Y-m-d H:i:s',course_student.expire_time) }}" lay-verify="required">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
            <input type="hidden" name="course_id" value="{{ course_student.course_id }}"/>
            <input type="hidden" name="user_id" value="{{ course_student.user_id }}"/>
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