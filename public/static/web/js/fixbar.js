layui.use(['jquery', 'util'], function () {

    var $ = layui.jquery;
    var util = layui.util;

    var csEnabled = $('input[name="im.cs.enabled"]').val();
    var robotEnabled = $('input[name="im.robot.enabled"]').val();

    util.fixbar({
        bar1: csEnabled === '1' ? '&#xe626;' : false,
        bar2: robotEnabled === '1' ? '&#xe684;' : false,
        click: function (type) {
            if (type === 'bar1') {
                window.open('/im/cs', 'cs');
            } else if (type === 'bar2') {
                window.open('/im/robot', 'robot');
            }
        }
    });

});