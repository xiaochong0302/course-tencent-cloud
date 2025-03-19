layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;

    $('.btn-pay').on('click', function () {

        var channel = $(this).data('channel');
        var createUrl = $('input[name=trade_create_url]').val();
        var statusUrl = $('input[name=trade_status_url]').val();
        var forwardUrl = $('input[name=forward_url]').val();
        var orderSn = $('input[name=order_sn]').val();
        var $qrBlock = $('#' + channel + '-qrcode');
        var $tradeSn = $('input[name=' + channel + '_trade_sn]');
        var qrTitle = channel === 'alipay' ? '支付宝扫码支付' : '微信扫码支付';
        var qrHtml = $qrBlock.html();

        if (qrHtml.length === 0) {
            var postData = {
                order_sn: orderSn,
                channel: channel === 'alipay' ? 1 : 2
            };
            $.post(createUrl, postData, function (res) {
                qrHtml = '<div class="qrcode"><img src="' + res.qrcode + '" alt="支付二维码"></div>';
                showQrLayer(qrTitle, qrHtml);
                $qrBlock.html(qrHtml);
                $tradeSn.val(res.sn);
                var interval = setInterval(function () {
                    var queryData = {sn: res.sn};
                    $.get(statusUrl, queryData, function (res) {
                        if (res.status === 2) {
                            clearInterval(interval);
                            $('#pay-layer').html('<div class="success-tips">支付成功</div>');
                            setTimeout(function () {
                                window.location.href = forwardUrl;
                            }, 5000);
                        }
                    });
                }, 5000);
            });
        } else {
            showQrLayer(qrTitle, qrHtml);
        }
    });

    function showQrLayer(title, content) {
        layer.open({
            type: 1,
            id: 'pay-layer',
            title: title,
            content: content,
            area: ['640px', '320px'],
            resize: false
        })
    }

});
