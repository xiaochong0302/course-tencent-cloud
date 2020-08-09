layui.use(['util'], function () {

    var util = layui.util;

    util.fixbar({
        bar1: true,
        click: function (type) {
            if (type === 'bar1') {
                alert('点击了bar1');
            }
        }
    });

});