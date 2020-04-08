<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.course.update','id':course.id}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">相关课程</label>
        <div class="layui-input-block">
            <div id="xm-course-ids"></div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

{{ js_include('admin/js/xm-course.js') }}

<script>
    xmCourse({{ xm_courses|json_encode }}, '/admin/xm/course/all');
</script>
