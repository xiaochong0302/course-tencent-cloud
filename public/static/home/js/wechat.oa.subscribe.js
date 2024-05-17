layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var interval = null;

    var $qrcode = $('.wechat-scan-box > .qrcode');

    if ($qrcode.length > 0) {
        showQrCode();
    }

    function showQrCode() {
        $.get('/wechat/oa/subscribe/qrcode', function (res) {
            $qrcode.html('<img alt="关注微信公众号" src="' + res.qrcode.url + '">');
            interval = setInterval(function () {
                queryStatus();
            }, 1500);
        });
    }

    function queryStatus() {
        $.get('/wechat/oa/subscribe/status', function (res) {
            if (res.status === 1) {
                clearInterval(interval);
                layer.msg('关注微信公众号成功', {icon: 1});
                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            }
        });
    }

});
