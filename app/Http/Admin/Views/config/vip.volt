<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.config.vip'}) }}">

    <fieldset class="layui-elem-field layui-field-title">
        <legend>会员设置</legend>
    </fieldset>

    <div class="layui-form-item">
        <label class="layui-form-label">一月价格</label>
        <div class="layui-input-inline">
            <input class="layui-input" type="text" name="one_month" value="{{ vip.one_month }}" lay-verify="number">
        </div>
        <div class="layui-form-mid layui-word-aux">元</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">三月价格</label>
        <div class="layui-input-inline">
            <input class="layui-input" type="text" name="three_month" value="{{ vip.three_month }}" lay-verify="number">
        </div>
        <div class="layui-form-mid layui-word-aux">元</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">半年价格</label>
        <div class="layui-input-inline">
            <input class="layui-input" type="text" name="six_month" value="{{ vip.six_month }}" lay-verify="number">
        </div>
        <div class="layui-form-mid layui-word-aux">元</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">一年价格</label>
        <div class="layui-input-inline">
            <input class="layui-input" type="text" name="twelve_month" value="{{ vip.twelve_month }}" lay-verify="number">
        </div>
        <div class="layui-form-mid layui-word-aux">元</div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>