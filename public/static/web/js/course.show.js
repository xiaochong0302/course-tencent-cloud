layui.use(['jquery', 'helper'], function () {

    var $ = layui.jquery;
    var helper = layui.helper;

    if ($('#tab-packages').length > 0) {
        var $tabPackages = $('#tab-packages');
        helper.ajaxLoadHtml($tabPackages.data('url'), $tabPackages.attr('id'));
    }

    if ($('#tab-consults').length > 0) {
        var $tabConsults = $('#tab-consults');
        helper.ajaxLoadHtml($tabConsults.data('url'), $tabConsults.attr('id'));
    }

    if ($('#tab-reviews').length > 0) {
        var $tabReviews = $('#tab-reviews');
        helper.ajaxLoadHtml($tabReviews.data('url'), $tabReviews.attr('id'));
    }

    if ($('#sidebar-topics').length > 0) {
        var $sdTopics = $('#sidebar-topics');
        helper.ajaxLoadHtml($sdTopics.data('url'), $sdTopics.attr('id'));
    }

    if ($('#sidebar-recommended').length > 0) {
        var $sdRecommended = $('#sidebar-recommended');
        helper.ajaxLoadHtml($sdRecommended.data('url'), $sdRecommended.attr('id'));
    }

    if ($('#sidebar-related').length > 0) {
        var $sdRelated = $('#sidebar-related');
        helper.ajaxLoadHtml($sdRelated.data('url'), $sdRelated.attr('id'));
    }

});