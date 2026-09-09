layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var $phoneAgree = $('#cv-phone-agree');
    var $emailAgree = $('#cv-email-agree');
    var $phoneSubmit = $('#cv-phone-submit-btn');
    var $emailSubmit = $('#cv-email-submit-btn');

    $phoneSubmit.on('click', function () {
        if ($phoneAgree.prop('checked') === false) {
            layer.msg('请同意《用户协议》和《隐私政策》', {icon: 2});
            return false;
        }
    });

    $emailSubmit.on('click', function () {
        if ($emailAgree.prop('checked') === false) {
            layer.msg('请同意《用户协议》和《隐私政策》', {icon: 2});
            return false;
        }
    });

});