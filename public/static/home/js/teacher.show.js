layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    if ($('#tab-vod').length > 0) {
        var $tabVod = $('#tab-vod');
        helper.ajaxLoadHtml($tabVod.data('url'), $tabVod.attr('id'));
    }

    if ($('#tab-read').length > 0) {
        var $tabRead = $('#tab-read');
        helper.ajaxLoadHtml($tabRead.data('url'), $tabRead.attr('id'));
    }

});
