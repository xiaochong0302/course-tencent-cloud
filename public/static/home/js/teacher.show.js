layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    if ($('#tab-vod').length > 0) {
        var $tabVod = $('#tab-vod');
        helper.ajaxLoadHtml($tabVod.data('url'), $tabVod.attr('id'));
    }

    if ($('#tab-live').length > 0) {
        var $tabLive = $('#tab-live');
        helper.ajaxLoadHtml($tabLive.data('url'), $tabLive.attr('id'));
    }

    if ($('#tab-read').length > 0) {
        var $tabRead = $('#tab-read');
        helper.ajaxLoadHtml($tabRead.data('url'), $tabRead.attr('id'));
    }

    if ($('#tab-offline').length > 0) {
        var $tabOffline = $('#tab-offline');
        helper.ajaxLoadHtml($tabOffline.data('url'), $tabOffline.attr('id'));
    }

});