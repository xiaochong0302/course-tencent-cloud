{% set offline.start_time = offline.start_time > 0 ? date('Y-m-d H:i:s',offline.start_time) : '' %}
{% set offline.end_time = offline.end_time > 0 ? date('Y-m-d H:i:s',offline.end_time) : '' %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.content','id':chapter.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="start_time" autocomplete="off" value="{{ offline.start_time }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">结束时间</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="end_time" autocomplete="off" value="{{ offline.end_time }}" lay-verify="required">
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