<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.course.update','id':course.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">标题</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="title" value="{{ course.title }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">封面</label>
        <div class="layui-input-inline">
            <img id="img-cover" class="kg-cover" src="{{ course.cover }}">
            <input type="hidden" name="cover" value="{{ course.cover }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">分类</label>
        <div class="layui-input-block">
            <div id="xm-category-ids"></div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">讲师</label>
        <div class="layui-input-block">
            <div id="xm-teacher-ids"></div>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">难度</label>
        <div class="layui-input-block">
            <input type="radio" name="level" value="1" title="入门" {% if course.level == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="level" value="2" title="初级" {% if course.level == 2 %}checked="checked"{% endif %}>
            <input type="radio" name="level" value="3" title="中级" {% if course.level == 3 %}checked="checked"{% endif %}>
            <input type="radio" name="level" value="4" title="高级" {% if course.level == 4 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn kg-submit" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>
