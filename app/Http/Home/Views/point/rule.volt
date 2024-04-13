{% extends 'templates/layer.volt' %}

{% block content %}

    <table class="layui-table" lay-size="lg" lay-skin="line">
        <colgroup>
            <col>
            <col>
            <col>
        </colgroup>
        <thead>
        <tr>
            <th>奖励类型</th>
            <th>奖励分值</th>
            <th>每日上限</th>
        </tr>
        </thead>
        <tbody>
        {% if consume_rule.enabled == 1 %}
            <tr>
                <td>订单消费</td>
                <td>消费金额 X {{ consume_rule.rate }}</td>
                <td>N/A</td>
            </tr>
        {% endif %}
        {% if event_rule.account_register.enabled == 1 %}
            <tr>
                <td>帐号注册</td>
                <td>{{ event_rule.account_register.point }}</td>
                <td>N/A</td>
            </tr>
        {% endif %}
        {% if event_rule.site_visit.enabled == 1 %}
            <tr>
                <td>站点访问</td>
                <td>{{ event_rule.site_visit.point }}</td>
                <td>N/A</td>
            </tr>
        {% endif %}
        {% if event_rule.course_review.enabled == 1 %}
            <tr>
                <td>课程评价</td>
                <td>{{ event_rule.course_review.point }}</td>
                <td>N/A</td>
            </tr>
        {% endif %}
        {% if event_rule.chapter_study.enabled == 1 %}
            <tr>
                <td>课时学习</td>
                <td>{{ event_rule.chapter_study.point }}</td>
                <td>N/A</td>
            </tr>
        {% endif %}
        {% if event_rule.comment_post.enabled == 1 %}
            <tr>
                <td>发表评论</td>
                <td>{{ event_rule.comment_post.point }}</td>
                <td>{{ event_rule.comment_post.limit }}</td>
            </tr>
        {% endif %}
        {% if event_rule.article_post.enabled == 1 %}
            <tr>
                <td>发表文章</td>
                <td>{{ event_rule.article_post.point }}</td>
                <td>{{ event_rule.article_post.limit }}</td>
            </tr>
        {% endif %}
        {% if event_rule.question_post.enabled == 1 %}
            <tr>
                <td>发布问题</td>
                <td>{{ event_rule.question_post.point }}</td>
                <td>{{ event_rule.question_post.limit }}</td>
            </tr>
        {% endif %}
        {% if event_rule.answer_post.enabled == 1 %}
            <tr>
                <td>回答问题</td>
                <td>{{ event_rule.answer_post.point }}</td>
                <td>{{ event_rule.answer_post.limit }}</td>
            </tr>
        {% endif %}
        {% if event_rule.article_liked.enabled == 1 %}
            <tr>
                <td>文章被赞</td>
                <td>{{ event_rule.article_liked.point }}</td>
                <td>{{ event_rule.article_liked.limit }}</td>
            </tr>
        {% endif %}
        {% if event_rule.question_liked.enabled == 1 %}
            <tr>
                <td>问题被赞</td>
                <td>{{ event_rule.question_liked.point }}</td>
                <td>{{ event_rule.question_liked.limit }}</td>
            </tr>
        {% endif %}
        {% if event_rule.answer_liked.enabled == 1 %}
            <tr>
                <td>回答被赞</td>
                <td>{{ event_rule.answer_liked.point }}</td>
                <td>{{ event_rule.answer_liked.limit }}</td>
            </tr>
        {% endif %}
        </tbody>
    </table>

{% endblock %}