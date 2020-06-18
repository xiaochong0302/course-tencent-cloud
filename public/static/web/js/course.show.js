layui.use(['jquery', 'element'], function () {

    var $ = layui.jquery;

    if ($('#tab-packages').length > 0) {
        var $tabPackages = $('#tab-packages');
        layui.ajaxLoadHtml($tabPackages.attr('data-url'), $tabPackages.attr('id'));
    }
    if ($('#tab-consults').length > 0) {
        var $tabConsults = $('#tab-consults');
        layui.ajaxLoadHtml($tabConsults.attr('data-url'), $tabConsults.attr('id'));
    }
    if ($('#tab-reviews').length > 0) {
        var $tabReviews = $('#tab-reviews');
        layui.ajaxLoadHtml($tabReviews.attr('data-url'), $tabReviews.attr('id'));
    }
    if ($('#sidebar-topics').length > 0) {
        var $sdTopics = $('#sidebar-topics');
        layui.ajaxLoadHtml($sdTopics.attr('data-url'), $sdTopics.attr('id'));
    }
    if ($('#sidebar-recommended').length > 0) {
        var $sdRecommended = $('#sidebar-recommended');
        layui.ajaxLoadHtml($sdRecommended.attr('data-url'), $sdRecommended.attr('id'));
    }
    if ($('#sidebar-related').length > 0) {
        var $sdRelated = $('#sidebar-related');
        layui.ajaxLoadHtml($sdRelated.attr('data-url'), $sdRelated.attr('id'));
    }

});