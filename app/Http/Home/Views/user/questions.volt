{% if pager.total_pages > 0 %}
    <div class="question-list">
        <div class="layui-row layui-col-space20">
            {% for item in pager.items %}
                {% set question_url = url({'for':'home.question.show','id':item.id}) %}
                <div class="layui-col-md6">
                    <div class="article-card wrap">
                        <div class="info">
                            <div class="title layui-elip">
                                <a href="{{ question_url }}" target="_blank">{{ item.title }}</a>
                            </div>
                            <div class="summary">{{ substr(item.summary,0,80) }}</div>
                            <div class="meta">
                                <span class="time">{{ item.create_time|time_ago }}</span>
                                <span class="view">{{ item.view_count }} 浏览</span>
                                <span class="like">{{ item.like_count }} 点赞</span>
                                <span class="answer">{{ item.answer_count }} 回答</span>
                            </div>
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
    {{ partial('partials/pager_ajax') }}
{% endif %}