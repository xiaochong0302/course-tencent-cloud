<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.review.update','id':review.id}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑评价</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">评分</label>
        <div class="layui-input-block">
            <div id="rating">{{ review.rating }}</div>
            <input type="hidden" name="rating" value="{{ review.rating }}"/>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">评价</label>
        <div class="layui-input-block">
            <textarea name="content" class="layui-textarea" lay-verify="required">{{ review.content }}</textarea>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if review.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if review.published == 0 %}checked="checked"{% endif %}>
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

    layui.use(['jquery', 'rate'], function () {

        var $ = layui.jquery;
        var rate = layui.rate;

        rate.render({
            elem: '#rating',
            value: $('#rating').text(),
            choose: function (value) {
                $('input[name=rating]').val(value);
            }
        });

    });

</script>