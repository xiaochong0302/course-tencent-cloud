layui.use(['jquery', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var endTime = $('input[name="countdown.end_time"]').val();
    var serverTime = $('input[name="countdown.server_time"]').val();
    var liveStatusUrl = $('input[name="live.status_url"]').val();

    util.countdown(1000 * parseInt(endTime), 1000 * parseInt(serverTime), function (date) {
        var items = [
            {date: date[0], label: '天'},
            {date: date[1], label: '时'},
            {date: date[2], label: '分'},
            {date: date[3], label: '秒'}
        ];
        var html = '';
        layui.each(items, function (index, item) {
            if (item.date > 0) {
                html += '<span class="value">' + item.date + '</span>';
                html += '<span class="label">' + item.label + '</span>';
            }
        });
        $('.countdown > .timer').html(html);
    });

    setInterval(function () {
        $.get(liveStatusUrl, function (res) {
            if (res.status === 1) {
                window.location.reload();
            }
        });
    }, 30000);

});
