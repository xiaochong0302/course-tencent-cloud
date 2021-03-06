{%- macro publish_status(type) %}
    {% if type == 1 %}
        审核中
    {% elseif type == 2 %}
        已发布
    {% elseif type == 3 %}
        未通过
    {% else %}
        未知
    {% endif %}
{%- endmacro %}

{%- macro answer_card(item,auth_user) %}
    {% set show_url = full_url({'for':'home.answer.show','id':item.id}) %}
    {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
    {% set report_url = url({'for':'home.report.add'},{'item_id':item.id,'item_type':108}) %}
    {% set like_url = url({'for':'home.answer.like','id':item.id}) %}
    {% set edit_url = url({'for':'home.answer.edit','id':item.id}) %}
    {% set delete_url = url({'for':'home.answer.delete','id':item.id}) %}
    <div class="answer-card" id="answer-{{ item.id }}">
        <div class="header">
            <span class="avatar">
                <a href="{{ owner_url }}" title="{{ item.owner.name }}" target="_blank">
                    <img src="{{ item.owner.avatar }}!avatar_160" alt="{{ item.owner.name }}">
                </a>
            </span>
            <span class="name">
                <a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a>
            </span>
        </div>
        <div class="content markdown-body">{{ item.content }}</div>
        <div class="footer">
            <div class="left">
                <div class="column">
                    <span class="time" title="{{ date('Y-m-d H:i',item.create_time) }}">{{ item.create_time|time_ago }}</span>
                </div>
                <div class="column">
                    <span class="like-count" data-count="{{ item.like_count }}">{{ item.like_count }}</span>
                    {% if item.me.liked == 1 %}
                        <span class="action answer-like liked" title="取消点赞" data-url="{{ like_url }}">已赞</span>
                    {% else %}
                        <span class="action answer-like" title="点赞支持" data-url="{{ like_url }}">点赞</span>
                    {% endif %}
                </div>
            </div>
            <div class="right">
                <div class="column">
                    <span class="action kg-copy" title="复制链接" data-clipboard-text="{{ show_url }}">链接</span>
                </div>
                <div class="column">
                    <span class="action kg-report" data-url="{{ report_url }}">举报</span>
                </div>
                {% if auth_user.id == item.owner.id %}
                    <div class="column">
                        <span class="action answer-edit" data-url="{{ edit_url }}">编辑</span>
                    </div>
                    <div class="column">
                        <span class="action kg-delete" data-url="{{ delete_url }}">删除</span>
                    </div>
                {% endif %}
            </div>
        </div>
    </div>
{%- endmacro %}