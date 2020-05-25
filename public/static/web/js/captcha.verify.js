var $ = layui.jquery;
var layer = layui.layer;
var captcha = new TencentCaptcha(
    $('#cv-captcha-btn')[0],
    $('#cv-captcha-btn').attr('data-app-id'),
    function (res) {
        if (res.ret === 0) {
            $.ajax({
                type: 'POST',
                url: '/verify/code',
                data: {
                    account: $('#cv-account').val(),
                    ticket: res.ticket,
                    rand: res.randstr
                },
                success: function (res) {
                    var icon = res.code === 0 ? 1 : 2;
                    if (res.msg) {
                        layer.msg(res.msg, {icon: icon});
                    }
                }
            });
            $('#cv-captcha-btn').remove();
            $('#cv-submit-btn').removeAttr('disabled');
            $('#cv-verify-btn').removeClass('layui-hide');
        }
    }
);