var $ = layui.jquery;
var layer = layui.layer;

var timeCounting = false;
var $account = $('#cv-account');
var $emit = $('#cv-verify-emit');

var captcha = new TencentCaptcha(
    $emit[0],
    $('#cv-app-id').val(),
    function (res) {
        if (res.ret === 0) {
            $('#cv-ticket').val(res.ticket);
            $('#cv-rand').val(res.randstr);
            if (isEmail($account.val()) || isPhone($account.val())) {
                var postUrl = null;
                var postData = {
                    ticket: $('#cv-ticket').val(),
                    rand: $('#cv-rand').val(),
                };
                if (isPhone($account.val())) {
                    postData.phone = $account.val();
                    postUrl = '/verify/sms/code';
                } else if (isEmail($account.val())) {
                    postData.email = $account.val();
                    postUrl = '/verify/email/code';
                }
                $.ajax({
                    type: 'POST',
                    url: postUrl,
                    data: postData,
                    success: function (res) {

                    },
                    error: function (xhr) {
                        var json = JSON.parse(xhr.responseText);
                        layer.msg(json.msg, {icon: 2});
                    }
                });
                $('#cv-submit-btn').removeClass('layui-btn-disabled').removeAttr('disabled');
                $emit.addClass('layui-btn-disabled').attr('disabled', 'disabled');
                showCountDown($emit);
            }
        }
    }
);

$account.on('keyup', function () {
    var accountOk;
    var type = $(this).attr('data-type');
    var account = $(this).val();
    if (type === 'phone') {
        accountOk = isPhone(account);
    } else if (type === 'email') {
        accountOk = isEmail(account);
    } else {
        accountOk = isPhone(account) || isEmail(account);
    }
    if (accountOk && !timeCounting) {
        $emit.removeClass('layui-btn-disabled').removeAttr('disabled');
    } else {
        $emit.addClass('layui-btn-disabled').attr('disabled', 'disabled');
    }
});

function showCountDown(obj) {
    var serverTime = new Date().getTime();
    var endTime = serverTime + 60 * 1000;
    layui.util.countdown(endTime, serverTime, function (date, serverTime, timer) {
        var left = date[0] * 86400 + date[1] * 3600 + date[2] * 60 + date[3];
        obj.text(left + '秒');
        if (left === 0) {
            obj.removeClass('layui-btn-disabled').removeAttr('disabled').text('重新发送');
            clearInterval(timer);
            timeCounting = false;
        }
    });
    timeCounting = true;
}

function isEmail(email) {
    return /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/.test(email);
}

function isPhone(phone) {
    return /^1(3|4|5|6|7|8|9)\d{9}$/.test(phone);
}