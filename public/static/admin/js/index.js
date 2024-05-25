layui.use(['jquery', 'element'], function () {

    var $ = layui.jquery;

    $('.kg-nav-module > li').on('click', function () {

        var module = $(this).data('module');

        $('.layui-nav-tree').each(function () {
            if ($(this).data('module') === module) {
                $(this).removeClass('layui-hide');
                window.frames['content'].location.href = $(this).find('a[target=content]:first').attr('href');
            } else {
                $(this).addClass('layui-hide');
            }
        });
    });

    $('.kg-side-menu-bar > a').on('click', function () {

        var icon = $(this).children('.layui-icon');
        var body = $('.layui-body');
        var footer = $('.layui-footer');
        var spreadLeft = 'layui-icon-spread-left';
        var shrinkRight = 'layui-icon-shrink-right';

        $('.layui-side').toggle();

        if (icon.hasClass(spreadLeft)) {
            $(this).attr('title', '打开左侧菜单');
            icon.removeClass(spreadLeft).addClass(shrinkRight);
            body.css('left', 0);
            footer.css('left', 0);
        } else {
            $(this).attr('title', '关闭左侧菜单');
            icon.removeClass(shrinkRight).addClass(spreadLeft);
            body.css('left', '200px');
            footer.css('left', '200px');
        }
    });

});
