{%- macro event_point_info(value) %}
    {% if value > 0 %}
        <span class="layui-badge layui-bg-red point">+{{ value }}</span>
    {% else %}
        <span class="layui-badge layui-bg-green point">{{ value }}</span>
    {% endif %}
{%- endmacro %}

{%- macro event_type_info(value) %}
    {% if value == 1 %}
        <span class="type">订单消费</span>
    {% elseif value == 2 %}
        <span class="type">积分兑换</span>
    {% elseif value == 3 %}
        <span class="type">积分退款</span>
    {% elseif value == 4 %}
        <span class="type">帐号注册</span>
    {% elseif value == 5 %}
        <span class="type">站点访问</span>
    {% elseif value == 6 %}
        <span class="type">课时学习</span>
    {% elseif value == 7 %}
        <span class="type">课程评价</span>
    {% elseif value == 8 %}
        <span class="type">微聊讨论</span>
    {% elseif value == 9 %}
        <span class="type">发布评论</span>
    {% elseif value == 10 %}
        <span class="type">发布文章</span>
    {% elseif value == 11 %}
        <span class="type">发布问题</span>
    {% elseif value == 12 %}
        <span class="type">发布回答</span>
    {% elseif value == 13 %}
        <span class="type">文章被赞</span>
    {% elseif value == 14 %}
        <span class="type">提问被赞</span>
    {% elseif value == 15 %}
        <span class="type">回答被赞</span>
    {% else %}
        <span class="type">N/A</span>
    {% endif %}
{%- endmacro %}

{%- macro event_detail_info(type,info) %}
    {% if type == 1 %}
        <p class="order">{{ info.order.subject }}</p>
    {% elseif type == 2 %}
        {% set gift_url = url({'for':'home.point_gift.show','id':info.point_gift_redeem.gift_id}) %}
        <p class="gift"><a href="{{ gift_url }}" target="_blank">{{ info.point_gift_redeem.gift_name }}</a></p>
    {% elseif type == 3 %}
        {% set gift_url = url({'for':'home.point_gift.show','id':info.point_gift_redeem.gift_id}) %}
        <p class="gift"><a href="{{ gift_url }}" target="_blank">{{ info.point_gift_redeem.gift_name }}</a></p>
    {% elseif type == 4 %}
        <span class="none">N/A</span>
    {% elseif type == 5 %}
        <span class="none">N/A</span>
    {% elseif type == 6 %}
        {% set course_url = url({'for':'home.course.show','id':info.course.id}) %}
        {% set chapter_url = url({'for':'home.chapter.show','id':info.chapter.id}) %}
        <p class="course">课程：<a href="{{ course_url }}" target="_blank">{{ info.course.title }}</a></p>
        <p class="chapter">章节：<a href="{{ chapter_url }}" target="_blank">{{ info.chapter.title }}</a></p>
    {% elseif type == 7 %}
        {% set course_url = url({'for':'home.course.show','id':info.course.id}) %}
        <p class="course"><a href="{{ course_url }}" target="_blank">{{ info.course.title }}</a></p>
    {% elseif type == 8 %}
        <span class="none">N/A</span>
    {% elseif type == 9 %}
        <span class="comment">N/A</span>
    {% elseif type == 10 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p class="article"><a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a></p>
    {% elseif type == 11 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p class="question"><a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
    {% elseif type == 12 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p class="answer"><a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
    {% elseif type == 13 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p class="article"><a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a></p>
    {% elseif type == 14 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p class="question"><a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
    {% elseif type == 15 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p class="answer"><a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
    {% else %}
        <p>N/A</p>
    {% endif %}
{%- endmacro %}