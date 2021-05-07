layui.use(['jquery', 'form', 'layer', 'helper'], function () {

    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    var helper = layui.helper;

    form.on('submit(add_answer)', function (data) {
        var index = parent.layer.getFrameIndex(window.name);
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                parent.layer.close(index);
                parent.location.reload();
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg);
            }
        });
        return false;
    });

    form.on('submit(edit_answer)', function (data) {
        var index = parent.layer.getFrameIndex(window.name);
        $.ajax({
            type: 'POST',
            url: data.form.action,
            data: data.field,
            success: function (res) {
                parent.layer.close(index);
                parent.location.reload();
            },
            error: function (xhr) {
                var res = JSON.parse(xhr.responseText);
                layer.msg(res.msg);
            }
        });
        return false;
    });

    $('body').on('click', '.action-like', function () {
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

    $('body').on('click', '.action-accept', function () {

    });

    $('body').on('click', '.action-edit', function () {
        layer.open({
            type: 2,
            title: '编辑答案',
            content: $(this).data('url'),
            area: ['800px', '600px']
        });
    });

    $('body').on('click', '.action-delete', function () {
        var $this = $(this);
        var id = $this.data('id');
        var parentId = $this.data('parent-id');
        var $answer = $('#answer-' + id);
        var $tbAnswerCount = $('#toolbar-answer > .text');
        var tbAnswerCount = $tbAnswerCount.data('count');
        layer.confirm('确定要删除吗？', function () {
            $.ajax({
                type: 'POST',
                url: $this.data('url'),
                success: function () {
                    if (parentId > 0) {
                        var $replyCount = $('#answer-' + parentId).find('.reply-count');
                        var replyCount = $replyCount.data('count');
                        replyCount--;
                        $replyCount.data('count', replyCount).text(replyCount);
                    }
                    tbAnswerCount--;
                    $tbAnswerCount.data('count', tbAnswerCount).text(tbAnswerCount);
                    $answer.remove();
                    layer.msg('删除评论成功');
                }
            });
        });
    });

});