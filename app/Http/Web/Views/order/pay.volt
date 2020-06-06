{% extends 'templates/full.volt' %}

{% block content %}

    <div class="layui-breadcrumb breadcrumb">
        <a href="/">首页</a>
        <a><cite>支付订单</cite></a>
    </div>

    <div class="payment container">
        <div class="header">
            订单名称：<span>{{ order.subject }}</span>
            订单编号：<span>{{ order.sn }}</span>
            支付金额：<span class="amount">￥{{ order.amount }}</span>
        </div>
        <div class="channel">
            {% set create_url = url({'for':'web.trade.create'}) %}
            {% set status_url = url({'for':'web.trade.status'}) %}
            <a class="alipay pay-btn" href="javascript:" data-channel="alipay"></a>
            <a class="wxpay pay-btn" href="javascript:" data-channel="wxpay"></a>
        </div>
        <div class="footer">
            <span class="tips">友情提示：请在12小时内完成支付，有问题请联系客服</span>
        </div>
    </div>

    <div id="alipay-qr-box" class="layui-hide"></div>
    <div id="wxpay-qr-box" class="layui-hide"></div>

    <div class="layui-hide">
        <input type="hidden" name="trade_create_url" value="{{ url({'for':'web.trade.create'}) }}">
        <input type="hidden" name="trade_status_url" value="{{ url({'for':'web.trade.status'}) }}">
        <input type="hidden" name="forward_url" value="{{ url({'for':'web.course.list'}) }}">
        <input type="hidden" name="order_sn" value="{{ order.sn }}">
        <input type="hidden" name="alipay_trade_sn" value="">
        <input type="hidden" name="wxpay_trade_sn" value="">
    </div>

{% endblock %}

{% block inline_js %}

    <script>
        var $ = layui.jquery;
        var layer = layui.layer;
        var showQrLayer = function (title, content) {
            layer.open({
                type: 1,
                id: 'pay-layer',
                title: title,
                content: content,
                area: ['640px', '320px']
            });
        };
        $('.pay-btn').on('click', function () {
            var channel = $(this).attr('data-channel');
            var createUrl = $('input[name=trade_create_url]').val();
            var statusUrl = $('input[name=trade_status_url]').val();
            var forwardUrl = $('input[name=forward_url]').val();
            var orderSn = $('input[name=order_sn]').val();
            var $qrBox = $('#' + channel + '-qr-box');
            var $snInput = $('input[name=' + channel + '_trade_sn]');
            var qrTitle = channel === 'alipay' ? '支付宝扫码支付' : '微信扫码支付';
            var qrHtml = $qrBox.html();
            if (qrHtml.length === 0) {
                var postData = {order_sn: orderSn, channel: channel};
                $.post(createUrl, postData, function (res) {
                    qrHtml = '<div class="qrcode"><img src="' + res.qrcode_url + '" alt="支付二维码"></div>';
                    showQrLayer(qrTitle, qrHtml);
                    $qrBox.html(qrHtml);
                    $snInput.html(res.sn);
                    var interval = setInterval(function () {
                        var queryData = {sn: res.sn};
                        $.get(statusUrl, queryData, function (res) {
                            if (res.status === 'finished') {
                                clearInterval(interval);
                                var html = '<div class="success-tips">支付成功</div>';
                                $('#pay-layer').html(html);
                                setTimeout(function () {
                                    window.location.href = forwardUrl;
                                }, 5000);
                            }
                        });
                    }, 3000)
                });
            } else {
                showQrLayer(qrTitle, qrHtml);
            }
        });
    </script>

{% endblock %}