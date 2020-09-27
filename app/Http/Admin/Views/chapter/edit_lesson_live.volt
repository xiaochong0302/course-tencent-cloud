{% set live.start_time = live.start_time > 0 ? date('Y-m-d H:i:s',live.start_time) : '' %}
{% set live.end_time = live.end_time > 0 ? date('Y-m-d H:i:s',live.end_time) : '' %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.content','id':chapter.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="start_time" autocomplete="off" value="{{ live.start_time }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">结束时间</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="end_time" autocomplete="off" value="{{ live.end_time }}" lay-verify="required">
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

<form class="layui-form kg-form">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>推流测试</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">Stream Name</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_name" value="{{ stream_name }}" readonly="readonly">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button type="button" class="layui-btn" id="show-push-test">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>