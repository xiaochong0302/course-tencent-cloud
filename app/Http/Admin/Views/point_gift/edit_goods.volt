<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>编辑礼品</legend>
    </fieldset>
    <div class="layui-form-item">
        <label class="layui-form-label">礼品封面</label>
        <div class="layui-input-inline">
            <img id="img-cover" class="kg-cover" src="{{ gift.cover }}">
            <input type="hidden" name="cover" value="{{ gift.cover }}">
        </div>
        <div class="layui-input-inline" style="padding-top:35px;">
            <button id="change-cover" class="layui-btn layui-btn-sm" type="button">更换</button>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">礼品名称</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="name" value="{{ gift.name }}" lay-verify="required">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">采购地址</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="attrs[url]" value="{{ gift.attrs['url'] }}" placeholder="淘宝或京东等平台商品链接地址" lay-verify="url">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">商品详情</label>
        <div class="layui-input-block">
            <textarea name="details" class="layui-hide" id="editor-textarea">{{ gift.details }}</textarea>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">所需积分</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="point" value="{{ gift.point }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">库存数量</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="stock" value="{{ gift.stock }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">兑换限额</label>
        <div class="layui-input-block">
            <input class="layui-input" type="text" name="redeem_limit" value="{{ gift.redeem_limit }}" lay-verify="number">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">发布</label>
        <div class="layui-input-block">
            <input type="radio" name="published" value="1" title="是" {% if gift.published == 1 %}checked="checked"{% endif %}>
            <input type="radio" name="published" value="0" title="否" {% if gift.published == 0 %}checked="checked"{% endif %}>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button class="kg-submit layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>