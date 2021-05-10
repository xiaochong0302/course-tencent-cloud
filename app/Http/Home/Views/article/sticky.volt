{% set favorite_url = url({'for':'home.article.favorite','id':article.id}) %}
{% set like_url = url({'for':'home.article.like','id':article.id}) %}
{% set favorite_title = article.me.favorited == 1 ? '取消收藏' : '收藏文章' %}
{% set like_title = article.me.liked == 1 ? '取消点赞' : '点赞支持' %}
{% set favorite_class = article.me.favorited == 1 ? 'layui-icon-star-fill' : 'layui-icon-star' %}
{% set like_class = article.me.liked == 1 ? 'active' : '' %}

<div class="toolbar-sticky">
    <div class="item" id="toolbar-like">
        <div class="icon" title="{{ like_title }}" data-url="{{ like_url }}">
            <i class="layui-icon layui-icon-praise icon-praise {{ like_class }}"></i>
        </div>
        <div class="text" data-count="{{ article.like_count }}">{{ article.like_count }}</div>
    </div>
    <div class="item" id="toolbar-comment">
        <div class="icon" title="评论交流">
            <i class="layui-icon layui-icon-reply-fill icon-reply"></i>
        </div>
        <div class="text" data-count="{{ article.comment_count }}">{{ article.comment_count }}</div>
    </div>
    <div class="item" id="toolbar-favorite">
        <div class="icon" title="{{ favorite_title }}" data-url="{{ favorite_url }}">
            <i class="layui-icon icon-star {{ favorite_class }}"></i>
        </div>
        <div class="text" data-count="{{ article.favorite_count }}">{{ article.favorite_count }}</div>
    </div>
</div>