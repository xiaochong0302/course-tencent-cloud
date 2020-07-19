<div class="course-meta wrap clearfix">
    <div class="cover">
        <img src="{{ course.cover }}" alt="{{ course.title|e }}">
    </div>
    <div class="info">
        {% if course.model == 'vod' %}
            <p>课程时长 <span>{{ course.attrs.duration|total_duration }}</span></p>
        {% elseif course.model == 'live' %}
            <p>直播时间 <span>{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span></p>
        {% endif %}
        {% if course.market_price > 0 %}
            <p>
                学习期限 <span class="expiry">{{ course.study_expiry }}个月</span>
                退款期限 <span class="expiry">{{ course.refund_expiry }}天</span>
            </p>
        {% endif %}
        <p>
            {% if course.market_price > 0 %}
                市场价格 <span class="price">{{ '￥%0.2f'|format(course.market_price) }}</span>
            {% else %}
                市场价格 <span class="free">免费</span>
            {% endif %}
            {% if course.vip_price > 0 %}
                会员价格 <span class="price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
            {% else %}
                会员价格 <span class="free">免费</span>
            {% endif %}
        </p>
        <p>
            难度级别 <span>{{ level_info(course.level) }}</span>
            学习人次 <span>{{ course.user_count }}</span>
            综合评分 <span>{{ course.ratings.rating }}</span>
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