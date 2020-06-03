var $ = layui.jquery;
var layer = layui.layer;
var captcha = new TencentCaptcha(
    $('#cv-captcha-btn')[0],
    $('#cv-captcha-btn').attr('data-app-id'),
    function (res) {
        if (res.ret === 0) {
            $('#cv-ticket').val(res.ticket);
            $('#cv-rand').val(res.randstr);
            $('#cv-captcha-btn').remove();
            $('#cv-verify-btn').removeClass('layui-hide');
            $('#cv-verify-emit').removeClass('layui-btn-disabled').removeAttr('disabled');
        }
    }
);

$('#cv-verify-emit').on('click', function () {
    var account = $('#cv-account').val();
    var regEmail = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
    var regPhone = /^1(3|4|5|6|7|8|9)\d{9}$/;
    var isEmail = regEmail.test(account);
    var isPhone = regPhone.test(account);
    if (isEmail || isPhone) {
        var postUrl = null;
        var postData = {
            ticket: $('#cv-ticket').val(),
            rand: $('#cv-rand').val(),
        };
        if (isPhone) {
            postData.phone = account;
            postUrl = '/verify/sms/code';
        } else if (isEmail) {
            postData.email = account;
            postUrl = '/verify/email/code';
        }
        $.post(postUrl, postData, function (res) {
            if (res.code === 0) {
                $('#cv-submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
                layer.msg('发送验证码成功', {icon: 1});
            } else {
                layer.msg('发送验证码失败', {icon: 2});
            }
        });
    }
});