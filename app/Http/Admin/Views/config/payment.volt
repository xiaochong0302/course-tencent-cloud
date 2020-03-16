<div class="layui-tab layui-tab-brief">
    <ul class="layui-tab-title kg-tab-title">
        <li class="layui-this">支付宝支付</li>
        <li>微信支付</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            {{ partial('config/payment_alipay') }}
        </div>
        <div class="layui-tab-item">
            {{ partial('config/payment_wechat') }}
        </div>
    </div>
</div>