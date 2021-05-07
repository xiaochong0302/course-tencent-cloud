{% if pager.total_pages > 0 %}
    {% for item in pager.items %}
        {% set owner_url = url({'for':'home.user.show','id':item.owner.id}) %}
        {% set like_url = url({'for':'home.comment.like','id':item.id}) %}
        {% set delete_url = url({'for':'home.comment.delete','id':item.id}) %}
        {% set reply_create_url = url({'for':'home.comment.create_reply','id':item.id}) %}
        <div class="comment-box" id="comment-{{ item.id }}">
            <div class="comment-card">
                <div class="avatar">
                    <a href="{{ owner_url }}" title="{{ item.owner.name }}" target="_blank">
                        <img src="{{ item.owner.avatar }}!avatar_160" alt="{{ item.owner.name }}">
                    </a>
                </div>
                <div class="info">
                    <div class="user">
                        <a href="{{ owner_url }}" target="_blank">{{ item.owner.name }}</a>
                        {% if item.to_user.id is defined %}
                            {% set to_user_url = url({'for':'home.user.show','id':item.to_user.id}) %}
                            <span class="separator">回复</span>
                            <a class="{{ to_user_url }}" target="_blank">{{ item.to_user.name }}</a>
                        {% endif %}
                    </div>
                    <div class="content">{{ item.content }}</div>
                    <div class="footer">
                        <div class="left">
                            <div class="column">
                                <span class="time" title="{{ date('Y-m-d H:i',item.create_time) }}">{{ item.create_time|time_ago }}</span>
                            </div>
                            <div class="column">
                                <span class="like-count" data-count="{{ item.like_count }}">{{ item.like_count }}</span>
                                {% if item.me.liked == 1 %}
                                    <span class="action action-like liked" title="取消点赞" data-url="{{ like_url }}">已赞</span>
                                {% else %}
                                    <span class="action action-like" title="点赞支持" data-url="{{ like_url }}">点赞</span>
                                {% endif %}
                            </div>
                        </div>
                        <div class="right">
                            <div class="column">
                                <span class="action action-reply" data-id="{{ item.id }}">回复</span>
                            </div>
                            <div class="column">
                                <span class="action action-report" data-id="{{ item.id }}">举报</span>
                            </div>
                            {% if item.owner.id == auth_user.id %}
                                <div class="column">
                                    <span class="action action-delete" data-id="{{ item.id }}" data-parent-id="{{ item.parent_id }}" data-url="{{ delete_url }}">删除</span>
                                </div>
                            {% endif %}
                        </div>
                    </div>
                </div>
            </div>
            <div class="comment-form" id="comment-form-{{ item.id }}" style="display:none;">
                <form class="layui-form" method="post" action="{{ reply_create_url }}">
                    <textarea class="layui-textarea" name="content" placeholder="撰写评论..." lay-verify="required"></textarea>
                    <div class="footer">
                        <div class="toolbar"></div>
                        <div class="action">
                            <button class="layui-btn layui-btn-sm" lay-submit="true" lay-filter="reply_comment" data-comment-id="{{ item.id }}" data-parent-id="{{ item.parent_id }}">发布</button>
                            <button class="layui-btn layui-btn-sm layui-bg-gray btn-cancel-reply" type="button" data-id="{{ item.id }}">取消</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    {% endfor %}
    {{ partial('partials/pager_ajax') }}
{% endif %}