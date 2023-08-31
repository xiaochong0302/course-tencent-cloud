{% set like_url = url({'for':'home.chapter.like','id':chapter.id}) %}
{% set like_title = chapter.me.liked == 1 ? '取消点赞' : '点赞支持' %}
{% set like_class = chapter.me.liked == 1 ? 'active' : '' %}

<div class="toolbar-sticky">
    <div class="item" id="toolbar-like">
        <div class="icon" title="{{ like_title }}" data-url="{{ like_url }}">
            <i class="layui-icon layui-icon-praise icon-praise {{ like_class }}"></i>
        </div>
        <div class="text" data-count="{{ chapter.like_count }}">{{ chapter.like_count }}</div>
    </div>
    <div class="item">
        <div class="icon" title="学习人次">
            <i class="layui-icon layui-icon-user"></i>
        </div>
        <div class="text">{{ chapter.user_count }}</div>
    </div>
    <div class="item" id="toolbar-comment">
        <div class="icon" title="评论交流">
            <i class="layui-icon layui-icon-reply-fill icon-reply"></i>
        </div>
        <div class="text" data-count="{{ chapter.comment_count }}">{{ chapter.comment_count }}</div>
    </div>
</div>