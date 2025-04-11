{%- macro vod_meta_info(course) %}
    <p class="item">
        <span class="key">课程时长</span>
        <span class="value">{{ course.attrs.duration|duration }}</span>
    </p>
    {{ meta_expiry_info(course) }}
    {{ meta_price_info(course) }}
    {{ meta_stats_info(course) }}
{%- endmacro %}

{%- macro live_meta_info(course) %}
    <p class="item">
        <span class="key">直播时间</span>
        <span class="value">{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span>
    </p>
    {{ meta_expiry_info(course) }}
    {{ meta_price_info(course) }}
    {{ meta_stats_info(course) }}
{%- endmacro %}

{%- macro read_meta_info(course) %}
    <p class="item">
        <span class="key">课程时长</span>
        <span class="value">{{ course.attrs.duration|duration }}</span>
    </p>
    {{ meta_expiry_info(course) }}
    {{ meta_price_info(course) }}
    {{ meta_stats_info(course) }}
{%- endmacro %}

{%- macro offline_meta_info(course) %}
    {% set search_url = "https://map.baidu.com/search/%s?querytype=s&wd=%s"|format(course.attrs.location,course.attrs.location) %}
    <p class="item">
        <span class="key">上课时间</span>
        <span class="value">{{ course.attrs.start_date }} ~ {{ course.attrs.end_date }}</span>
    </p>
    <p class="item">
        <span class="key">上课地点</span>
        <span class="value">{{ course.attrs.location }}</span>
        <a class="value" href="{{ search_url }}" title="查看地理位置" target="_blank">
            <i class="layui-icon layui-icon-location"></i>
        </a>
    </p>
    {{ meta_price_info(course) }}
    {{ meta_stats_info(course) }}
{%- endmacro %}

{%- macro meta_expiry_info(course) %}
    <p class="item">
        <span class="key">学习期限</span>
        <span class="value">{{ course.study_expiry }} 个月</span>
        {% if course.refund_expiry > 0 %}
            <span class="key">退款期限</span>
            <span class="value">{{ course.refund_expiry }} 天</span>
        {% else %}
            <span class="key">退款期限</span>
            <span class="value">不支持</span>
        {% endif %}
    </p>
{%- endmacro %}

{%- macro meta_price_info(course) %}
    <p class="item">
        {% if course.market_price > 0 %}
            <span class="key">市场价格</span>
            <span class="value price">{{ '￥%0.2f'|format(course.market_price) }}</span>
        {% else %}
            <span class="key">市场价格</span>
            <span class="value free">免费</span>
        {% endif %}
        {% if course.vip_price > 0 %}
            <span class="key">会员价格</span>
            <span class="value price">{{ '￥%0.2f'|format(course.vip_price) }}</span>
        {% else %}
            <span class="key">会员价格</span>
            <span class="value free">免费</span>
        {% endif %}
    </p>
{%- endmacro %}

{%- macro meta_stats_info(course) %}
    <p class="item">
        <span class="key">难度级别</span>
        <span class="value">{{ level_type(course.level) }}</span>
        <span class="key">学习人次</span>
        <span class="value">{{ course.user_count }}</span>
        <span class="key">综合评分</span>
        <span class="value">{{ "%0.1f"|format(course.ratings.rating) }}</span>
    </p>
{%- endmacro %}

<div class="course-meta wrap">
    <div class="cover">
        <span class="model layui-badge layui-bg-green">{{ model_type(course.model) }}</span>
        <img src="{{ course.cover }}!cover_270" alt="{{ course.title }}">
    </div>
    <div class="info">
        {% if course.model == 1 %}
            {{ vod_meta_info(course) }}
        {% elseif course.model == 2 %}
            {{ live_meta_info(course) }}
        {% elseif course.model == 3 %}
            {{ read_meta_info(course) }}
        {% elseif course.model == 4 %}
            {{ offline_meta_info(course) }}
        {% endif %}
    </div>
    <div class="ratings">
        <p class="item">
            <span class="name">内容实用</span>
            <span class="star" id="rating1" data-value="{{ course.ratings.rating1 }}"></span>
            <span class="score">{{ "%0.1f"|format(course.ratings.rating1) }} 分</span>
        </p>
        <p class="item">
            <span class="name">简洁易懂</span>
            <span class="star" id="rating2" data-value="{{ course.ratings.rating2 }}"></span>
            <span class="score">{{ "%0.1f"|format(course.ratings.rating2) }} 分</span>
        </p>
        <p class="item">
            <span class="name">逻辑清晰</span>
            <span class="star" id="rating3" data-value="{{ course.ratings.rating3 }}"></span>
            <span class="score">{{ "%0.1f"|format(course.ratings.rating3) }} 分</span>
        </p>
    </div>
</div>
