layui.use(['jquery', 'element', 'helper'], function () {

    var $ = layui.jquery;
    var element = layui.element;
    var helper = layui.helper;

    loadAllTagList();
    loadMyTagList();

    $('body').on('click', '.btn-follow', function () {
        var $this = $(this);
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
                success: function () {
                    if ($this.hasClass('followed')) {
                        $this.text('关注').removeClass('followed').addClass('layui-btn-primary');
                    } else {
                        $this.text('已关注').removeClass('layui-btn-primary').addClass('followed');
                    }
                }
            });
        });
    });

    element.on('tab(tag)', function (data) {
        if (data.index === 0) {
            loadAllTagList();
        } else if (data.index === 1) {
            loadMyTagList();
        }
    });

    function loadAllTagList() {
        var $allTagList = $('#all-tag-list');
        if ($allTagList.length > 0) {
            helper.ajaxLoadHtml($allTagList.data('url'), $allTagList.attr('id'));
        }
    }

    function loadMyTagList() {
        var $myTagList = $('#my-tag-list');
        if ($myTagList.length > 0) {
            helper.ajaxLoadHtml($myTagList.data('url'), $myTagList.attr('id'));
        }
    }

});