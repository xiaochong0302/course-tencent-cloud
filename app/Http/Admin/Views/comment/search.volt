<fieldset class="layui-elem-field layui-field-title">
    <legend>搜索评论</legend>
</fieldset>

<form class="layui-form" method="GET" action="{{ url({'for':'admin.comment.list'}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">评论编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="id" placeholder="评论编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">课程编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="course_id" placeholder="课程编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">章节编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="chapter_id" placeholder="章节编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">用户编号</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="author_id" placeholder="用户编号精确匹配">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是">
            <input type="radio" name="published" value="0" title="否">
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
            <button class="layui-btn" lay-submit>提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>

</form>

<script>

    layui.use('form', function () {
        var form = layui.form;
    });

</script>