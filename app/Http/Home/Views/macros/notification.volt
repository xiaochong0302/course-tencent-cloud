{%- macro event_info(notify) %}

    {% set sender = notify.sender %}
    {% set type = notify.event_type %}
    {% set info = notify.event_info %}

    {% if type == 0 %}
        <p>N/A类型</p>
    {% elseif type == 147 %}
        {% set course_url = url({'for':'home.course.show','id':info.course.id}) %}
        <p>{{ sender.name }} 喜欢了你在课程 <a href="{{ course_url }}" target="_blank">{{ info.course.title }}</a> 中的咨询</p>
        <p>咨询内容：{{ info.consult.question }}</p>
    {% elseif type == 167 %}
        {% set course_url = url({'for':'home.course.show','id':info.course.id}) %}
        <p>{{ sender.name }} 喜欢了你在课程 <a href="{{ course_url }}" target="_blank">{{ info.course.title }}</a> 中的评价</p>
        <p>评价内容：{{ info.review.content }}</p>
    {% elseif type == 184 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p>你的文章 <a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a> 审核已通过</p>
    {% elseif type == 185 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p>你的文章 <a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a> 审核未通过</p>
        <p>拒绝原因：{{ info.reason }}</p>
    {% elseif type == 187 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p>{{ sender.name }} 评论了你的文章 <a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a></p>
        <p>评论内容：{{ info.comment.content }}</p>
    {% elseif type == 188 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p>{{ sender.name }} 收藏了你的文章 <a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a></p>
    {% elseif type == 189 %}
        {% set article_url = url({'for':'home.article.show','id':info.article.id}) %}
        <p>{{ sender.name }} 喜欢了你的文章 <a href="{{ article_url }}" target="_blank">{{ info.article.title }}</a></p>
    {% elseif type == 204 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>你的提问 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a> 审核已通过</p>
    {% elseif type == 205 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>你的提问 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a> 审核未通过</p>
        <p>拒绝原因：{{ info.reason }}</p>
    {% elseif type == 206 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>{{ sender.name }} 回答了你的提问 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
        <p>回答内容：{{ info.answer.summary }}</p>
    {% elseif type == 208 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>{{ sender.name }} 收藏了你的问题 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
    {% elseif type == 209 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>{{ sender.name }} 喜欢了你的问题 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a></p>
    {% elseif type == 224 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>你对问题 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a> 的回答，审核已通过</p>
        <p>回答内容：{{ info.answer.summary }}</p>
    {% elseif type == 225 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>你对问题 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a> 的回答，审核未通过</p>
        <p>回答内容：{{ info.answer.summary }}</p>
    {% elseif type == 228 %}
        {% set question_url = url({'for':'home.question.show','id':info.question.id}) %}
        <p>{{ sender.name }} 喜欢了你对问题 <a href="{{ question_url }}" target="_blank">{{ info.question.title }}</a>　的回答</p>
        <p>回答内容：{{ info.answer.summary }}</p>
    {% elseif type == 506 %}
        <p>{{ sender.name }} 回复了你的评论：{{ info.comment.content }}</p>
        <p>回复内容：{{ info.reply.content }}</p>
    {% elseif type == 507 %}
        <p>{{ sender.name }} 喜欢了你的评论：{{ info.comment.content }}</p>
    {% endif %}

{%- endmacro %}