<form class="layui-form kg-form" method="GET" action="{{ url({'for':'admin.review.list'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>搜索评价</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">评价编号</label>
        <div class="layui-input-block">
            <input type="text" name="id" placeholder="评价编号精确匹配" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">课程编号</label>
        <div class="layui-input-block">
            <input type="text" name="course_id" placeholder="课程编号精确匹配" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">用户编号</label>
        <div class="layui-input-block">
            <input type="text" name="user_id" placeholder="用户编号精确匹配" class="layui-input">
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
            <button class="layui-btn" lay-submit="true">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>