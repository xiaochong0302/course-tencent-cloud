layui.use(['jquery', 'layer'], function () {

    var $ = layui.jquery;
    var layer = layui.layer;
    var $loginAgree = $('#login-agree');
    var $registerAgree = $('#register-agree');
    var $loginSubmit = $('#submit-btn');
    var $registerSubmit = $('#cv-submit-btn');

    $loginSubmit.on('click', function () {
        if ($loginAgree.prop('checked') === false) {
            layer.msg('请同意《用户协议》和《隐私政策》', {icon: 2});
            return false;
        }
    });

    $registerSubmit.on('click', function () {
        if ($registerAgree.prop('checked') === false) {
            layer.msg('请同意《用户协议》和《隐私政策》', {icon: 2});
            return false;
        }
    });

});