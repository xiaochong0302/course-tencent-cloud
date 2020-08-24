<div class="course-meta wrap clearfix">
    <div class="cover">
        <img src="{{ course.cover }}" alt="{{ course.title|e }}">
    </div>
    <div class="info">
        {% if course.model == '1' %}
            <p class="item">
                <span class="key">课程时长</span><span class="value">{{ course.attrs.duration|duration }}</span>
            </p>
        {% elseif course.model == '2' %}
            <p class="item">
                <span class="key">直播时间</span><span>{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span>
            </p>
        {% endif %}
        {% if course.market_price > 0 %}
            <p class="item">
                <span class="key">学习期限</span><span class="value">{{ course.study_expiry }}个月</span>
                <span class="key">退款期限</span><span class="value">{{ course.refund_expiry }}天</span>
            </p>
        {% endif %}
        <p class="item">
            {% if course.market_price > 0 %}
                <span class="key">市场价格</span><span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
            {% else %}
                <span class="key">市场价格</span><span class="free">免费</span>
            {% endif %}
            {% if course.vip_price > 0 %}
                <span class="key">会员价格</span><span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
            {% else %}
                <span class="key">会员价格</span><span class="free">免费</span>
            {% endif %}
        </p>
        <p class="item">
            <span class="key">难度级别</span><span class="value">{{ level_info(course.level) }}</span>
            <span class="key">学习人次</span><span class="value">{{ course.user_count }}</span>
            <span class="key">综合评分</span><span class="value">{{ course.ratings.rating }}</span>
        </p>
    </div>
    <div class="rating">
        <p class="item">
            <span class="name">内容实用</span>
            <span class="star">{{ star_info(course.ratings.rating1) }}</span>
            <span class="score">{{ course.ratings.rating1 }}分</span>
        </p>
        <p class="item">
            <span class="name">简洁易懂</span>
            <span class="star">{{ star_info(course.ratings.rating2) }}</span>
            <span class="score">{{ course.ratings.rating2 }}分</span>
        </p>
        <p class="item">
            <span class="name">逻辑清晰</span>
            <span class="star">{{ star_info(course.ratings.rating3) }}</span>
            <span class="score">{{ course.ratings.rating3 }}分</span>
        </p>
    </div>
</div>