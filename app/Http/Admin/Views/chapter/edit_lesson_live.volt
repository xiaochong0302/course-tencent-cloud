<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.chapter.content','id':chapter.id}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">开始时间</label>
        <div class="layui-input-block">
            {% if live.start_time > 0 %}
                <input class="layui-input" type="text" name="start_time" autocomplete="off" value="{{ date('Y-m-d H:i:s',live.start_time) }}" {% if live.start_time < time() %}readonly="true"{% endif %} lay-verify="required">
            {% else %}
                <input class="layui-input" type="text" name="start_time" autocomplete="off" lay-verify="required">
            {% endif %}
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">结束时间</label>
        <div class="layui-input-block">
            {% if live.end_time > 0 %}
                <input class="layui-input" type="text" name="end_time" autocomplete="off" value="{{ date('Y-m-d H:i:s',live.end_time) }}" {% if live.end_time < time() %}readonly="true"{% endif %} lay-verify="required">
            {% else %}
                <input class="layui-input" type="text" name="end_time" autocomplete="off" lay-verify="required">
            {% endif %}
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
            elem: 'input[name=start_time]',
            type: 'datetime'
        });

        laydate.render({
            elem: 'input[name=end_time]',
            type: 'datetime'
        });

    });

</script>