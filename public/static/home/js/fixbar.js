layui.use(['jquery', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var showWechatCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.wechat + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showQQCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.qq + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showTouTiaoCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.toutiao + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showDouYinCode = function () {
        var content = '<div class="qrcode"><img src="' + window.contact.douyin + '" alt="扫码关注"></div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var showPhoneCode = function () {
        var content = '<div class="layui-font-32 layui-font-red layui-padding-5">';
        content += '<i class="iconfont icon-phone layui-padding-1 layui-font-28"></i>' + window.contact.phone;
        content += '</div>';
        layer.open({
            type: 1,
            title: false,
            closeBtn: 0,
            shadeClose: true,
            content: content,
        });
    }

    var bars = [];

    if (window.contact.wechat) {
        bars.push({
            type: 'wechat',
            icon: 'layui-icon-login-wechat',
        });
    }

    if (window.contact.qq) {
        bars.push({
            type: 'qq',
            icon: 'layui-icon-login-qq',
        });
    }

    if (window.contact.phone) {
        bars.push({
            type: 'phone',
            content: '<i class="iconfont icon-phone layui-font-30"></i>',
        });
    }

    util.fixbar({
        bars: bars,
        click: function (type) {
            if (type === 'wechat') {
                showWechatCode();
            } else if (type === 'qq') {
                showQQCode();
            } else if (type === 'phone') {
                showPhoneCode();
            }
        }
    });

    $('.contact > .wechat').on('click', function () {
        showWechatCode();
    });

    $('.contact > .qq').on('click', function () {
        showQQCode();
    });

    $('.contact > .toutiao').on('click', function () {
        showTouTiaoCode();
    });

    $('.contact > .douyin').on('click', function () {
        showDouYinCode();
    });

    $('.contact > .phone').on('click', function () {
        showPhoneCode();
    });

});
