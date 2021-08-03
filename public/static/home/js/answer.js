layui.use(['jquery', 'form', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var helper = layui.helper;

    form.on('submit(comment_answer)', function (data) {
        var submit = $(this);
        var answerId = $(this).data('answer-id');
        var $commentForm = $('#answer-comment-form-' + answerId);
        var $commentList = $('#answer-comment-list-' + answerId);
        var $textarea = $(data.form).find('.layui-textarea');
        var $commentCount = $('#answer-' + answerId).find('.comment-count');
        var commentCount = $commentCount.data('count');
        submit.attr('disabled', 'disabled').addClass('layui-btn-disabled');
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                $.ajax({
                    type: 'GET',
                    url: '/comment/' + res.comment.id + '/info',
                    success: function (html) {
                        $commentCount.data('count', commentCount).text(commentCount);
                        $commentList.prepend(html);
                    }
                });
                $commentForm.hide();
                $commentList.show();
                $textarea.val('');
                layer.msg('发表评论成功');
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg);
                submit.removeAttr('disabled').removeClass('layui-btn-disabled');
            }
        });
        return false;
    });

    $('body').on('click', '.answer-like', function () {
        var $this = $(this);
        var $likeCount = $this.prev();
        var likeCount = $likeCount.data('count');
        helper.checkLogin(function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
                success: function () {
                    if ($this.hasClass('liked')) {
                        $this.attr('title', '点赞支持').text('点赞').removeClass('liked');
                        likeCount--;
                    } else {
                        $this.attr('title', '取消点赞').text('已赞').addClass('liked');
                        likeCount++;
                    }
                    $likeCount.data('count', likeCount).text(likeCount);
                }
            });
        });
    });

    $('body').on('click', '.answer-edit', function () {
        window.location.href = $(this).data('url');
    });

    $('body').on('click', '.answer-comment', function () {
        var id = $(this).data('id');
        var $block = $('#answer-comment-form-' + id);
        var $textarea = $block.find('textarea');
        $block.toggle();
        $textarea.focus();
    });

    $('body').on('click', '.answer-comment-cancel', function () {
        var id = $(this).data('id');
        $('#answer-comment-form-' + id).hide();
    });

    $('body').on('click', '.answer-comment-toggle', function () {
        var $this = $(this);
        var id = $this.data('id');
        var url = $this.data('url');
        var $commentList = $('#answer-comment-list-' + id);
        if ($commentList.hasClass('loaded')) {
            if ($this.hasClass('expanded')) {
                $this.attr('title', '展开回应').removeClass('expanded');
            } else {
                $this.attr('title', '收起回应').addClass('expanded');
            }
            $commentList.toggle();
        } else {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (html) {
                    $this.attr('title', '收起回应').addClass('expanded');
                    $commentList.addClass('loaded').show();
                    $commentList.html(html);
                }
            });
        }
    });

});