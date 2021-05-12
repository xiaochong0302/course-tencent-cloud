{% set favorite_url = url({'for':'home.question.favorite','id':question.id}) %}
{% set like_url = url({'for':'home.question.like','id':question.id}) %}
{% set favorite_title = question.me.favorited == 1 ? '取消收藏' : '收藏问题' %}
{% set like_title = question.me.liked == 1 ? '取消点赞' : '点赞支持' %}
{% set favorite_class = question.me.favorited == 1 ? 'layui-icon-star-fill' : 'layui-icon-star' %}
{% set like_class = question.me.liked == 1 ? 'active' : '' %}

<div class="toolbar-sticky">
    <div class="item" id="toolbar-like">
        <div class="icon" title="{{ like_title }}" data-url="{{ like_url }}">
            <i class="layui-icon layui-icon-praise icon-praise {{ like_class }}"></i>
        </div>
        <div class="text" data-count="{{ question.like_count }}">{{ question.like_count }}</div>
    </div>
    <div class="item" id="toolbar-answer">
        <div class="icon" title="回答问题">
            <i class="layui-icon layui-icon-reply-fill icon-reply"></i>
        </div>
        <div class="text" data-count="{{ question.answer_count }}">{{ question.answer_count }}</div>
    </div>
    <div class="item" id="toolbar-favorite">
        <div class="icon" title="{{ favorite_title }}" data-url="{{ favorite_url }}">
            <i class="layui-icon icon-star {{ favorite_class }}"></i>
        </div>
        <div class="text" data-count="{{ question.favorite_count }}">{{ question.favorite_count }}</div>
    </div>
</div>