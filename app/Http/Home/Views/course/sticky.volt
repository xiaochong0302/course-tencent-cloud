{% set favorite_url = url({'for':'home.course.favorite','id':course.id}) %}
{% set favorite_title = course.me.favorited == 1 ? '取消收藏' : '收藏课程' %}
{% set favorite_class = course.me.favorited == 1 ? 'layui-icon-star-fill' : 'layui-icon-star' %}

<div class="toolbar-sticky">
    <div class="item">
        <div class="icon" title="学习人次">
            <i class="layui-icon layui-icon-user icon-user"></i>
        </div>
        <div class="text">{{ course.user_count|human_number }}</div>
    </div>
    <div class="item">
        <div class="icon" title="课程评价">
            <i class="layui-icon layui-icon-reply-fill"></i>
        </div>
        <div class="text">{{ course.review_count|human_number }}</div>
    </div>
    <div class="item" id="toolbar-favorite">
        <div class="icon" title="{{ favorite_title }}" data-url="{{ favorite_url }}">
            <i class="layui-icon icon-star {{ favorite_class }}"></i>
        </div>
        <div class="text" data-count="{{ course.favorite_count }}">{{ course.favorite_count|human_number }}</div>
    </div>
</div>
