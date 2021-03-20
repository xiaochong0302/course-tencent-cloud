{% set free = course.market_price == 0 %}
{% set price_display = course.market_price > 0 ? 'display:block' : 'display:none' %}

<form class="layui-form kg-form" method="POST" action="{{ url({'for':'admin.course.update','id':course.id}) }}">
    <div class="layui-form-item">
        <label class="layui-form-label">模式</label>
        <div class="layui-input-block">
            <input type="radio" name="price_mode" value="free" title="免费" lay-filter="price_mode" {% if free %}checked="checked"{% endif %}>
            <input type="radio" name="price_mode" value="charge" title="收费" lay-filter="price_mode" {% if not free %}checked="checked"{% endif %}>
        </div>
    </div>
    <div id="price-block" style="{{ price_display }}">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">原始价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="origin_price" value="{{ course.origin_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">市场价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="market_price" value="{{ course.market_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">会员价格</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" name="vip_price" value="{{ course.vip_price }}" lay-verify="number">
                </div>
                <div class="layui-form-mid layui-word-aux">元</div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">学习期限</label>
            <div class="layui-input-block">
                {% for key,value in study_expiry_options %}
                    <input type="radio" name="study_expiry" title="{{ value }}" value="{{ key }}" {% if key == course.study_expiry %}checked="checked"{% endif %}>
                {% endfor %}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">退款期限</label>
            <div class="layui-input-block">
                {% for key,value in refund_expiry_options %}
                    <input type="radio" name="refund_expiry" title="{{ value }}" value="{{ key }}" {% if key == course.refund_expiry %}checked="checked"{% endif %}>
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