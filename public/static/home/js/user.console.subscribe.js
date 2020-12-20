layui.use(['jquery'], function () {

    var $ = layui.jquery;
    var subscribed = $('input[name=subscribed]').val();
    var interval = null;

    if (subscribed === '0') {
        showQrCode();
        interval = setInterval(function () {
            queryStatus();
        }, 5000);
    }

    function showQrCode() {
        $.get('/wechat/oa/subscribe/qrcode', function (res) {
            $('#sub-qrcode').html('<img alt="扫码关注" src="' + res.qrcode + '">');
        });
    }

    function queryStatus() {
        $.get('/wechat/oa/subscribe/status', function (res) {
            if (res.status === 1) {
                clearInterval(interval);
                $('#sub-tips').addClass('success').html('关注公众号成功');
            }
        });
    }

});