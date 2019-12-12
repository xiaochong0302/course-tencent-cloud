{% set free = course.market_price == 0 %}

{% set expiry_options = ['30':'一个月','90':'三个月','180':'半年','365':'一年','1095':'三年'] %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.course.update','id':course.id}) }}">

    <div class="layui-form-item">
        <label class="layui-form-label">模式</label>
        <div class="layui-input-block">
            <input type="radio" name="price_mode" value="free" title="免费" lay-filter="price-mode" {% if free %}checked="checked"{% endif %}>
            <input type="radio" name="price_mode" value="charge" title="收费" lay-filter="price-mode" {% if not free %}checked="checked"{% endif %}>
        </div>
    </div>

    <div id="price-block" {% if free %}style="display:none;"{% endif %}>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">市场价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="market_price" value="{{ course.market_price }}" lay-filter="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">会员价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="vip_price" value="{{ course.vip_price }}" lay-filter="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">有效期限</label>
            <div class="layui-input-block">
                {% for key,value in expiry_options %}
                    <input type="radio" name="expiry" title="{{ value }}" value="{{ key }}" {% if key == course.expiry %}checked{% endif %}>
                {% endfor %}
            </div>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button id="sale-submit" class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>

</form>

<script>

    layui.use(['jquery', 'form', 'layer'], function () {

        var $ = layui.jquery;
        var form = layui.form;

        form.on('radio(price-mode)', function (data) {
            var priceBlock = $('#price-block');
            if (data.value == 'free') {
                priceBlock.hide();
            } else {
                priceBlock.show();
            }
        });

    });

</script>
