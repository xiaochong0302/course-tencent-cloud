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

<form class="layui-form kg-form">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>推流测试</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">Stream Name</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stream_name" value="chapter_{{ chapter.id }}" readonly="readonly">
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

<script>

    layui.use(['jquery', 'layer', 'laydate'], function () {

        var $ = layui.jquery;
        var layer = layui.layer;
        var laydate = layui.laydate;

        laydate.render({
            elem: 'input[name=start_time]',
            type: 'datetime'
        });

        laydate.render({
            elem: 'input[name=end_time]',
            type: 'datetime'
        });

        $('#show-push-test').on('click', function () {
            var streamName = $('input[name=stream_name]').val();
            var url = '/admin/test/live/push?stream=' + streamName;
            layer.open({
                type: 2,
                title: '推流测试',
                resize: false,
                area: ['680px', '380px'],
                content: [url, 'no']
            });
        });

    });

</script>