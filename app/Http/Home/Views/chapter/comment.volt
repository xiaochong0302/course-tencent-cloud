{% set item_type = 1 %}
{% set comment_list_url = url({'for':'home.comment.list'},{'item_id':chapter.id,'item_type':item_type}) %}
{% set comment_create_url = url({'for':'home.comment.create'}) %}

<div class="comment-form" id="comment-form">
    <form class="layui-form" method="post" action="{{ comment_create_url }}">
        <textarea class="layui-textarea" id="comment-content" name="content" placeholder="撰写评论..." lay-verify="required"></textarea>
        <div class="footer" id="comment-footer" style="display:none;">
            <div class="toolbar"></div>
            <div class="action">
                <button class="layui-btn layui-btn-sm" lay-submit="true" lay-filter="add_comment">发布</button>
                <button class="layui-btn layui-btn-sm layui-btn-primary" id="comment-cancel" type="button">取消</button>
            </div>
        </div>
        <input type="hidden" name="item_id" value="{{ chapter.id }}">
        <input type="hidden" name="item_type" value="{{ item_type }}">
    </form>
</div>

<div class="comment-list" id="comment-list" data-url="{{ comment_list_url }}"></div>