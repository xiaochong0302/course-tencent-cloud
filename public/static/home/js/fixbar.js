layui.use(['helper', 'util'], function () {

    var helper = layui.helper;
    var util = layui.util;

    util.fixbar({
        bar1: window.im.cs.enabled === '1' ? '&#xe626;' : false,
        bar2: true,
        click: function (type) {
            if (type === 'bar1') {
                helper.cs();
            } else if (type === 'bar2') {
                window.open('/help', 'help');
            }
        }
    });

});