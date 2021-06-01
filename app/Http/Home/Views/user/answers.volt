{% if pager.total_pages > 0 %}
    <div class="question-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set answer_url = url({'for':'home.answer.show','id':item.id}) %}
                <div class="layui-col-md6">
                    <div class="article-card wrap">
                        <div class="info">
                            <div class="title layui-elip">
                                <a href="{{ answer_url }}" target="_blank">{{ item.question.title }}</a>
                            </div>
                            <div class="summary">{{ substr(item.summary,0,80) }}</div>
                            <div class="meta">
                                <span class="time">{{ item.create_time|time_ago }}</span>
                                <span class="like">{{ item.like_count }} 点赞</span>
                                <span class="comment">{{ item.comment_count }} 评论</span>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}