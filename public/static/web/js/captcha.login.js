var $ = layui.jquery;
var layer = layui.layer;
var captcha = new TencentCaptcha(
    $('#captcha-btn')[0],
    $('#captcha-btn').attr('data-app-id'),
    function (res) {
        if (res.ret === 0) {
            $('#ticket').val(res.ticket);
            $('#rand').val(res.randstr);
            $('#captcha-btn').remove();
            $('#verify-btn').removeClass('layui-hide');
            $('#submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
        }
    }
);