{% extends 'templates/main.volt' %}

{% block content %}

    <div class="kg-qrcode-block">
        {% if qrcode %}
            <div id="qrcode">
                <img class="kg-qrcode" src="{{ qrcode }}" alt="二维码图片">
            </div>
            <input type="hidden" name="sn" value="{{ sn }}">
            <div id="success-tips" class="kg-success-tips layui-hide">
                <span>支付成功</span>
            </div>
            <div id="error-tips" class="kg-error-tips layui-hide">
                <span>支付失败</span>
            </div>
        {% else %}
            <div class="kg-error-tips">
                <span>生成二维码失败</span>
            </div>
        {% endif %}
    </div>

{% endblock %}

{% block inline_js %}

    {% if qrcode %}
        <script>

            layui.use(['jquery'], function () {

                var $ = layui.jquery;
                var sn = $('input[name=sn]').val();
                var interval = setInterval(function () {
                    $.ajax({
                        type: 'GET',
                        url: '/admin/test/wxpay/status',
                        data: {sn: sn},
                        success: function (res) {
                            if ($.inArray(res.status, [2, 3]) > -1) {
                                $('#success-tips').removeClass('layui-hide');
                                $('#qrcode').addClass('layui-hide');
                                clearInterval(interval);
                            }
                        },
                        error: function () {
                            $('#error-tips').removeClass('layui-hide');
                            $('#qrcode').addClass('layui-hide');
                            clearInterval(interval);
                        }
                    });
                }, 5000);

            });

        </script>
    {% endif %}

{% endblock %}