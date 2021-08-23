layui.use(['jquery', 'helper', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var showQQDialog = function () {
        window.open('https://wpa.qq.com/msgrd?v=3&uin=' + window.contact.qq + '&site=qq&menu=yes');
    }

    var showWechatCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.wechat + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    }

    var showTouTiaoCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.toutiao + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content
        });
    }

    $('.icon-wechat').on('click', function () {
        showWechatCode();
    });

    $('.icon-toutiao').on('click', function () {
        showTouTiaoCode();
    });

    util.fixbar({
        bar1: window.contact.qq ? '&#xe676;' : false,
        bar2: window.contact.wechat ? '&#xe677;' : false,
        click: function (type) {
            if (type === 'bar1') {
                showQQDialog();
            } else if (type === 'bar2') {
                showWechatCode();
            }
        }
    });

});