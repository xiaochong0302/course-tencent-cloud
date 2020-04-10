<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.setting.vip'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>会员设置</legend>
    </fieldset>

    {% for item in vips %}
        <div class="layui-form-item">
            <label class="layui-form-label">{{ item.title }}</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="vip[{{ item.id }}]" value="{{ item.price }}" lay-verify="number">
            </div>
            <div class="layui-form-mid layui-word-aux">元</div>
        </div>
    {% endfor %}

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>