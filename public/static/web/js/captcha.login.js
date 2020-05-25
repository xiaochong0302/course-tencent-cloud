var $ = layui.jquery;
var layer = layui.layer;
var captcha = new TencentCaptcha(
    $('#captcha-btn')[0],
    $('#captcha-btn').attr('data-app-id'),
    function (res) {
        if (res.ret === 0) {
            $('input[name=ticket]').val(res.ticket);
            $('input[name=rand]').val(res.randstr);
            $('#captcha-btn').remove();
            $('#submit-btn').removeAttr('disabled');
            $('#verify-btn').removeClass('layui-hide');
        }
    }
);