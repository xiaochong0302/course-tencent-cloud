<form class="layui-form kg-form" method="POST" action="{{ update_url }}">
    <div class="layui-form-item">
        <div class="layui-inline">
            <label class="layui-form-label">虚构订阅</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="text" name="fake_user_count" value="{{ course.fake_user_count }}" lay-verify="number">
            </div>
            <div class="layui-form-mid layui-word-aux">人</div>
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
    {% if course.model in [1,2,3] %}
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
    {% endif %}
    <div class="layui-form-item">
        <label class="layui-form-label"></label>
        <div class="layui-input-block">
            <button id="sale-submit" class="layui-btn" lay-submit="true" lay-filter="go">提交</button>
            <button type="button" class="kg-back layui-btn layui-btn-primary">返回</button>
        </div>
    </div>
</form>