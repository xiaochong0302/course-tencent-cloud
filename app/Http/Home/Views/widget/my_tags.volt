{% set type = request.get('type','string','article') %}

{% if type == 'article' %}
    {% set base_url = url({'for':'home.article.list'}) %}
{% elseif type == 'question' %}
    {% set base_url = url({'for':'home.question.list'}) %}
{% endif %}

<div class="layui-card widget-card">
    <div class="more">
        <a href="{{ url({'for':'home.tag.list'}) }}">管理</a>
    </div>
    <div class="layui-card-header">关注标签</div>
    <div class="layui-card-body">
        {% for item in tags %}
            {% set tagged_url = base_url ~ '?tag_id=' ~ item.id %}
            <a class="layui-badge-rim tag-badge" href="{{ tagged_url }}">{{ item.name }}</a>
        {% endfor %}
    </div>
</div>
